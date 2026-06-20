#!/bin/bash
set -e

PUBLIC_SUBNET_1A="subnet-05a8a8a9b50a645eb"
EC2_SG_ID="sg-0d9173adb1621c229"

# echo "Creating Key Pair..."
# aws ec2 create-key-pair --key-name fixla-key-new --query 'KeyMaterial' --output text > fixla-key.pem
# chmod 400 fixla-key.pem

echo "Launching EC2 Instance..."
INSTANCE_ID=$(aws ec2 run-instances \
  --image-id ami-0672fd5b9210aa093 \
  --instance-type t3.micro \
  --key-name fixla-key-new \
  --security-group-ids $EC2_SG_ID \
  --subnet-id $PUBLIC_SUBNET_1A \
  --tag-specifications 'ResourceType=instance,Tags=[{Key=Name,Value=fixla-landing}]' \
  --query 'Instances[0].InstanceId' --output text)

echo "Waiting for EC2 to be running..."
aws ec2 wait instance-running --instance-ids $INSTANCE_ID

EC2_PUBLIC_IP=$(aws ec2 describe-instances --instance-ids $INSTANCE_ID \
  --query "Reservations[0].Instances[0].PublicIpAddress" --output text)
  
echo "EC2 Setup Complete!"
echo "INSTANCE_ID=$INSTANCE_ID"
echo "EC2_PUBLIC_IP=$EC2_PUBLIC_IP"
