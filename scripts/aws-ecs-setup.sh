#!/bin/bash
set -e

ACCOUNT_ID="890105072685"
REGION="ap-southeast-1"
VPC_ID="vpc-0219386f2f747fce0"
PUBLIC_SUBNET_1A="subnet-05a8a8a9b50a645eb"
PUBLIC_SUBNET_1B="subnet-0c7cd11a3acbe7f45"
ALB_SG_ID="sg-09e62545e87b08a71"
ECS_SG_ID="sg-06b131ba12e01a0d7"
RDS_ENDPOINT="fixla-db.cx4wuoko8miw.ap-southeast-1.rds.amazonaws.com"
BUCKET_NAME="fixla-uploads-890105072685"
APP_KEY="base64:mIS+DYoz/ErR7cKJ1RPJulvOwq0r3PFPkQ8C45Td+y8="

echo "1. Creating ECR Repository..."
aws ecr create-repository --repository-name fixla-backend --region $REGION || true

echo "2. Logging into ECR..."
aws ecr get-login-password --region $REGION | docker login --username AWS --password-stdin $ACCOUNT_ID.dkr.ecr.$REGION.amazonaws.com

echo "3. Tagging and Pushing Docker Image..."
docker tag fixla-backend:latest $ACCOUNT_ID.dkr.ecr.$REGION.amazonaws.com/fixla-backend:latest
docker push $ACCOUNT_ID.dkr.ecr.$REGION.amazonaws.com/fixla-backend:latest

echo "4. Creating ECS Cluster..."
aws ecs create-cluster --cluster-name fixla-cluster --region $REGION > /dev/null

echo "5. Creating IAM Roles..."
cat <<EOF > /tmp/ecs-trust.json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Principal": {"Service": "ecs-tasks.amazonaws.com"},
      "Action": "sts:AssumeRole"
    }
  ]
}
EOF

aws iam create-role --role-name fixla-ecs-execution-role --assume-role-policy-document file:///tmp/ecs-trust.json || true
aws iam attach-role-policy --role-name fixla-ecs-execution-role --policy-arn arn:aws:iam::aws:policy/service-role/AmazonECSTaskExecutionRolePolicy

aws iam create-role --role-name fixla-ecs-task-role --assume-role-policy-document file:///tmp/ecs-trust.json || true
aws iam attach-role-policy --role-name fixla-ecs-task-role --policy-arn arn:aws:iam::aws:policy/AmazonS3FullAccess

echo "6. Creating ALB..."
ALB_ARN=$(aws elbv2 create-load-balancer --name fixla-alb \
  --subnets $PUBLIC_SUBNET_1A $PUBLIC_SUBNET_1B \
  --security-groups $ALB_SG_ID \
  --scheme internet-facing \
  --type application \
  --query 'LoadBalancers[0].LoadBalancerArn' --output text)

ALB_DNS_NAME=$(aws elbv2 describe-load-balancers --load-balancer-arns $ALB_ARN \
  --query 'LoadBalancers[0].DNSName' --output text)
echo "ALB_DNS_NAME=$ALB_DNS_NAME"

echo "7. Creating Target Group..."
TG_ARN=$(aws elbv2 create-target-group --name fixla-backend-tg \
  --protocol HTTP --port 80 --vpc-id $VPC_ID \
  --target-type ip \
  --health-check-path /api/v1/reports \
  --health-check-interval-seconds 30 \
  --query 'TargetGroups[0].TargetGroupArn' --output text)

echo "8. Creating Listener..."
aws elbv2 create-listener --load-balancer-arn $ALB_ARN \
  --protocol HTTP --port 80 \
  --default-actions Type=forward,TargetGroupArn=$TG_ARN > /dev/null

echo "Waiting for ALB to become active..."
aws elbv2 wait load-balancer-available --load-balancer-arns $ALB_ARN

echo "9. Creating ECS Task Definition..."
cat <<EOF > /tmp/task-def.json
{
  "family": "fixla-backend",
  "networkMode": "awsvpc",
  "requiresCompatibilities": ["FARGATE"],
  "cpu": "512",
  "memory": "1024",
  "executionRoleArn": "arn:aws:iam::$ACCOUNT_ID:role/fixla-ecs-execution-role",
  "taskRoleArn": "arn:aws:iam::$ACCOUNT_ID:role/fixla-ecs-task-role",
  "containerDefinitions": [
    {
      "name": "fixla-backend",
      "image": "$ACCOUNT_ID.dkr.ecr.$REGION.amazonaws.com/fixla-backend:latest",
      "portMappings": [
        {
          "containerPort": 80,
          "protocol": "tcp"
        }
      ],
      "environment": [
        {"name": "APP_NAME", "value": "FixLA"},
        {"name": "APP_ENV", "value": "production"},
        {"name": "APP_DEBUG", "value": "false"},
        {"name": "APP_KEY", "value": "$APP_KEY"},
        {"name": "APP_URL", "value": "http://$ALB_DNS_NAME"},
        {"name": "DB_CONNECTION", "value": "pgsql"},
        {"name": "DB_HOST", "value": "$RDS_ENDPOINT"},
        {"name": "DB_PORT", "value": "5432"},
        {"name": "DB_DATABASE", "value": "fixla"},
        {"name": "DB_USERNAME", "value": "fixla_admin"},
        {"name": "DB_PASSWORD", "value": "GantiPasswordKuatIni123!"},
        {"name": "FILESYSTEM_DISK", "value": "s3"},
        {"name": "AWS_DEFAULT_REGION", "value": "$REGION"},
        {"name": "AWS_BUCKET", "value": "$BUCKET_NAME"}
      ],
      "logConfiguration": {
        "logDriver": "awslogs",
        "options": {
          "awslogs-group": "/ecs/fixla-backend",
          "awslogs-region": "$REGION",
          "awslogs-stream-prefix": "ecs",
          "awslogs-create-group": "true"
        }
      }
    }
  ]
}
EOF
aws ecs register-task-definition --cli-input-json file:///tmp/task-def.json > /dev/null

echo "10. Creating ECS Service..."
aws ecs create-service \
  --cluster fixla-cluster \
  --service-name fixla-backend-service \
  --task-definition fixla-backend \
  --desired-count 1 \
  --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[$PUBLIC_SUBNET_1A,$PUBLIC_SUBNET_1B],securityGroups=[$ECS_SG_ID],assignPublicIp=ENABLED}" \
  --load-balancers "targetGroupArn=$TG_ARN,containerName=fixla-backend,containerPort=80" > /dev/null

echo "ECS Setup Complete!"
echo "ALB_DNS_NAME=$ALB_DNS_NAME"
