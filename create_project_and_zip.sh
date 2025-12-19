#!/usr/bin/env bash
# سكربت: ينشئ مشروع Laravel مبدئي ثم ينسخ الملفات ويصنع zip
# متطلبات: php, composer, node, npm, zip, unzip, git
set -e

APP_NAME="hospital-system"
ZIP_NAME="${APP_NAME}.zip"
TMP_DIR="./${APP_NAME}_tmp"

if [ -d "$TMP_DIR" ]; then
  echo "Removng existing tmp dir..."
  rm -rf "$TMP_DIR"
fi

mkdir "$TMP_DIR"
cd "$TMP_DIR"

echo "Creating Laravel project skeleton (composer create-project)..."
composer create-project laravel/laravel "$APP_NAME" --quiet
cd "$APP_NAME"

echo "Installing required composer packages..."
# breeze (for auth/inertia/vue), websockets, maatwebsite/excel (export), dompdf
composer require laravel/breeze --dev --quiet
php artisan breeze:install vue
composer require beyondcode/laravel-websockets pusher/pusher-php-server
composer require maatwebsite/excel barryvdh/laravel-dompdf

echo "Installing npm packages..."
npm install

# Copy bundled files into project (these files MUST be created by you from the blocks provided in this message)
# The following expects that when you run this script you will paste the extra files into this project manually,
# or you can edit the script to create files using heredocs.
#
# For convenience, we'll create a directory 'bootstrap_extra' where you'll place the files provided in the chat,
# then this script will copy them into the proper places.
#
echo "Please place the extra files provided (migrations, services, controllers, resources) inside ${PWD}/bootstrap_extra and press ENTER"
read -p "Press ENTER after copying files..."

if [ ! -d "./bootstrap_extra" ]; then
  echo "Error: bootstrap_extra not found. Create directory and copy files into it as instructed in the message."
  exit 1
fi

echo "Copying extra files into project..."
rsync -a ./bootstrap_extra/ .

echo "Running npm build..."
npm run build

echo "Running artisan migrations (without seeder) — ensure DB config set in .env if you want real migrations now"
# Create .env from example, user must edit DB values later
cp .env.example .env
php artisan key:generate

echo "Packaging project into zip: ../${ZIP_NAME}"
cd ..
zip -r "../${ZIP_NAME}" "${APP_NAME}" >/dev/null

echo "Done. Zip created at: ../${ZIP_NAME}"
echo "Move the zip to your server, extract and follow README.md for final setup."