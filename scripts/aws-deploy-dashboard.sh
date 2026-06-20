#!/bin/bash
set -e

echo "Starting Admin Dashboard deployment process..."

# Define AWS variables
ACCOUNT_ID=$(aws sts get-caller-identity --query Account --output text)
REGION="ap-southeast-1"
BUCKET_NAME="fixla-admin-dashboard-$ACCOUNT_ID"

echo "Step 1: Building the React Dashboard..."
cd dashboard
npm install
npm run build
cd ..

echo "Step 2: Creating S3 Bucket ($BUCKET_NAME) if it doesn't exist..."
if aws s3api head-bucket --bucket "$BUCKET_NAME" 2>/dev/null; then
    echo "Bucket $BUCKET_NAME already exists."
else
    aws s3 mb "s3://$BUCKET_NAME" --region "$REGION"
fi

echo "Step 3: Disabling Block Public Access..."
aws s3api put-public-access-block \
    --bucket "$BUCKET_NAME" \
    --public-access-block-configuration "BlockPublicAcls=false,IgnorePublicAcls=false,BlockPublicPolicy=false,RestrictPublicBuckets=false"

echo "Step 4: Applying Bucket Policy for Public Read..."
cat <<EOF > /tmp/dashboard-policy.json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "PublicReadGetObject",
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::$BUCKET_NAME/*"
    }
  ]
}
EOF
aws s3api put-bucket-policy --bucket "$BUCKET_NAME" --policy file:///tmp/dashboard-policy.json

echo "Step 5: Configuring S3 Static Website Hosting..."
cat <<EOF > /tmp/website.json
{
    "IndexDocument": {
        "Suffix": "index.html"
    },
    "ErrorDocument": {
        "Key": "index.html"
    }
}
EOF
aws s3api put-bucket-website --bucket "$BUCKET_NAME" --website-configuration file:///tmp/website.json

echo "Step 6: Syncing build files to S3..."
aws s3 sync dashboard/dist/ "s3://$BUCKET_NAME/" --delete

echo "================================================="
echo "Dashboard Deployment Complete!"
echo "Your Admin Dashboard is now live at:"
echo "http://$BUCKET_NAME.s3-website-$REGION.amazonaws.com"
echo "================================================="
