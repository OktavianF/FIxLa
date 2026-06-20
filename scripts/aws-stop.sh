#!/bin/bash
set -e

# Stop EC2 Instance (Landing Page)
echo "Menghentikan EC2 Instance (Landing Page)..."
INSTANCE_ID=$(aws ec2 describe-instances --filters "Name=tag:Name,Values=fixla-landing" --query "Reservations[0].Instances[0].InstanceId" --output text)
if [ "$INSTANCE_ID" != "None" ] && [ -n "$INSTANCE_ID" ]; then
    aws ec2 stop-instances --instance-ids $INSTANCE_ID > /dev/null
    echo "EC2 Instance $INSTANCE_ID berhasil dihentikan."
else
    echo "EC2 Instance tidak ditemukan."
fi

# Stop RDS Instance (Database)
echo "Menghentikan RDS Database..."
aws rds stop-db-instance --db-instance-identifier fixla-db > /dev/null || echo "RDS mungkin sudah berhenti atau sedang proses."
echo "RDS Database sedang dihentikan."

# Scale down ECS Fargate Tasks to 0 (Backend API)
echo "Menurunkan kapasitas ECS Fargate (Backend) ke 0..."
aws ecs update-service --cluster fixla-cluster --service fixla-backend-service --desired-count 0 > /dev/null
echo "ECS Fargate tasks berhasil dihentikan."

echo "=========================================="
echo "Semua service komputasi telah dihentikan!"
echo "Penyimpanan (S3) dan Load Balancer (ALB) tetap ada karena masuk dalam kuota gratis (Free Tier 750 jam)."
echo "Data Anda tetap aman dan bisa dinyalakan kembali kapan saja."
