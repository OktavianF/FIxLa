#!/bin/bash
set -e

ALB_DNS="fixla-alb-486742336.ap-southeast-1.elb.amazonaws.com"

cd /var/www/fixla

# Create .env file
cp .env.example .env
sed -i "s|APP_URL=http://localhost|APP_URL=http://localhost|g" .env
php artisan key:generate

# Replace API URL in auth.js (if it's not compiled by Vite)
if [ -f "public/js/auth.js" ]; then
  sed -i "s|http://localhost:8000|http://$ALB_DNS|g" public/js/auth.js
fi
if [ -f "resources/js/auth.js" ]; then
  sed -i "s|http://localhost:8000|http://$ALB_DNS|g" resources/js/auth.js
fi

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci || npm install
npm run build || true

# Setup Nginx
cat <<EOF | sudo tee /etc/nginx/sites-available/default
server {
    listen 80 default_server;
    listen [::]:80 default_server;
    server_name _;
    root /var/www/fixla/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html index.htm;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Set permissions
sudo chown -R www-data:www-data /var/www/fixla/storage /var/www/fixla/bootstrap/cache
sudo chmod -R 775 /var/www/fixla/storage /var/www/fixla/bootstrap/cache

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm

echo "Deployment finished successfully!"
