#!/bin/bash
set -e

PRIVATE_SUBNET_1A="subnet-06f564d64e251e75e"
PRIVATE_SUBNET_1B="subnet-04242394aafd0f7af"
RDS_SG_ID="sg-0ea7ed103426c3cf8"

# echo "Creating DB Subnet Group..."
# aws rds create-db-subnet-group \
#   --db-subnet-group-name fixla-db-subnet \
#   --db-subnet-group-description "Subnets for FixLA RDS" \
#   --subnet-ids $PRIVATE_SUBNET_1A $PRIVATE_SUBNET_1B > /dev/null

echo "Creating RDS Instance (db.t3.micro)..."
aws rds create-db-instance \
  --db-instance-identifier fixla-db \
  --db-instance-class db.t3.micro \
  --engine postgres \
  --engine-version 16.14 \
  --master-username fixla_admin \
  --master-user-password "GantiPasswordKuatIni123!" \
  --allocated-storage 20 \
  --storage-type gp3 \
  --db-name fixla \
  --vpc-security-group-ids $RDS_SG_ID \
  --db-subnet-group-name fixla-db-subnet \
  --no-publicly-accessible > /dev/null

echo "RDS Instance created. Note: It may take 5-10 minutes to become available."
echo "Waiting for RDS instance to become available..."
aws rds wait db-instance-available --db-instance-identifier fixla-db

echo "Fetching RDS Endpoint..."
RDS_ENDPOINT=$(aws rds describe-db-instances --db-instance-identifier fixla-db \
  --query "DBInstances[0].Endpoint.Address" --output text)
echo "RDS_ENDPOINT=$RDS_ENDPOINT"
