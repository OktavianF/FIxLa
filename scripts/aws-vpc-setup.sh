#!/bin/bash
set -e

echo "Creating VPC..."
VPC_ID=$(aws ec2 create-vpc --cidr-block 10.0.0.0/16 \
  --tag-specifications 'ResourceType=vpc,Tags=[{Key=Name,Value=fixla-vpc}]' \
  --query 'Vpc.VpcId' --output text)
echo "VPC_ID=$VPC_ID"

echo "Enabling DNS support and hostnames..."
aws ec2 modify-vpc-attribute --vpc-id $VPC_ID --enable-dns-support "{\"Value\":true}"
aws ec2 modify-vpc-attribute --vpc-id $VPC_ID --enable-dns-hostnames "{\"Value\":true}"

echo "Creating Internet Gateway..."
IGW_ID=$(aws ec2 create-internet-gateway \
  --tag-specifications 'ResourceType=internet-gateway,Tags=[{Key=Name,Value=fixla-igw}]' \
  --query 'InternetGateway.InternetGatewayId' --output text)
echo "IGW_ID=$IGW_ID"

echo "Attaching IGW to VPC..."
aws ec2 attach-internet-gateway --internet-gateway-id $IGW_ID --vpc-id $VPC_ID

echo "Creating Public Subnet 1A..."
PUBLIC_SUBNET_1A=$(aws ec2 create-subnet --vpc-id $VPC_ID --cidr-block 10.0.1.0/24 \
  --availability-zone ap-southeast-1a \
  --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=fixla-public-1a}]' \
  --query 'Subnet.SubnetId' --output text)
echo "PUBLIC_SUBNET_1A=$PUBLIC_SUBNET_1A"

echo "Creating Public Subnet 1B..."
PUBLIC_SUBNET_1B=$(aws ec2 create-subnet --vpc-id $VPC_ID --cidr-block 10.0.2.0/24 \
  --availability-zone ap-southeast-1b \
  --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=fixla-public-1b}]' \
  --query 'Subnet.SubnetId' --output text)
echo "PUBLIC_SUBNET_1B=$PUBLIC_SUBNET_1B"

echo "Creating Private Subnet 1A..."
PRIVATE_SUBNET_1A=$(aws ec2 create-subnet --vpc-id $VPC_ID --cidr-block 10.0.10.0/24 \
  --availability-zone ap-southeast-1a \
  --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=fixla-private-1a}]' \
  --query 'Subnet.SubnetId' --output text)
echo "PRIVATE_SUBNET_1A=$PRIVATE_SUBNET_1A"

echo "Creating Private Subnet 1B..."
PRIVATE_SUBNET_1B=$(aws ec2 create-subnet --vpc-id $VPC_ID --cidr-block 10.0.11.0/24 \
  --availability-zone ap-southeast-1b \
  --tag-specifications 'ResourceType=subnet,Tags=[{Key=Name,Value=fixla-private-1b}]' \
  --query 'Subnet.SubnetId' --output text)
echo "PRIVATE_SUBNET_1B=$PRIVATE_SUBNET_1B"

echo "Creating Route Table for Public Subnets..."
RT_ID=$(aws ec2 create-route-table --vpc-id $VPC_ID \
  --tag-specifications 'ResourceType=route-table,Tags=[{Key=Name,Value=fixla-public-rt}]' \
  --query 'RouteTable.RouteTableId' --output text)
echo "RT_ID=$RT_ID"

echo "Creating Route to IGW..."
aws ec2 create-route --route-table-id $RT_ID \
  --destination-cidr-block 0.0.0.0/0 --gateway-id $IGW_ID > /dev/null

echo "Associating Route Table with Public Subnets..."
aws ec2 associate-route-table --route-table-id $RT_ID --subnet-id $PUBLIC_SUBNET_1A > /dev/null
aws ec2 associate-route-table --route-table-id $RT_ID --subnet-id $PUBLIC_SUBNET_1B > /dev/null

echo "Enabling auto-assign public IPs for Public Subnets..."
aws ec2 modify-subnet-attribute --subnet-id $PUBLIC_SUBNET_1A --map-public-ip-on-launch
aws ec2 modify-subnet-attribute --subnet-id $PUBLIC_SUBNET_1B --map-public-ip-on-launch

echo "VPC Setup Complete!"
