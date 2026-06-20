#!/bin/bash
set -e

VPC_ID="vpc-0219386f2f747fce0"

echo "Creating ALB Security Group..."
ALB_SG_ID=$(aws ec2 create-security-group --group-name fixla-alb-sg \
  --description "ALB for Backend API" --vpc-id $VPC_ID \
  --query 'GroupId' --output text)
echo "ALB_SG_ID=$ALB_SG_ID"

aws ec2 authorize-security-group-ingress --group-id $ALB_SG_ID \
  --protocol tcp --port 80 --cidr 0.0.0.0/0 > /dev/null

echo "Creating ECS Security Group..."
ECS_SG_ID=$(aws ec2 create-security-group --group-name fixla-ecs-sg \
  --description "ECS Backend API" --vpc-id $VPC_ID \
  --query 'GroupId' --output text)
echo "ECS_SG_ID=$ECS_SG_ID"

aws ec2 authorize-security-group-ingress --group-id $ECS_SG_ID \
  --protocol tcp --port 80 --source-group $ALB_SG_ID > /dev/null

echo "Creating EC2 Security Group..."
EC2_SG_ID=$(aws ec2 create-security-group --group-name fixla-ec2-sg \
  --description "EC2 Landing Page" --vpc-id $VPC_ID \
  --query 'GroupId' --output text)
echo "EC2_SG_ID=$EC2_SG_ID"

aws ec2 authorize-security-group-ingress --group-id $EC2_SG_ID \
  --protocol tcp --port 80 --cidr 0.0.0.0/0 > /dev/null
aws ec2 authorize-security-group-ingress --group-id $EC2_SG_ID \
  --protocol tcp --port 22 --cidr 0.0.0.0/0 > /dev/null

echo "Creating RDS Security Group..."
RDS_SG_ID=$(aws ec2 create-security-group --group-name fixla-rds-sg \
  --description "RDS PostgreSQL" --vpc-id $VPC_ID \
  --query 'GroupId' --output text)
echo "RDS_SG_ID=$RDS_SG_ID"

aws ec2 authorize-security-group-ingress --group-id $RDS_SG_ID \
  --protocol tcp --port 5432 --source-group $ECS_SG_ID > /dev/null
aws ec2 authorize-security-group-ingress --group-id $RDS_SG_ID \
  --protocol tcp --port 5432 --source-group $EC2_SG_ID > /dev/null

echo "Security Groups Setup Complete!"
