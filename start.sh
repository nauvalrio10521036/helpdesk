#!/bin/bash

# Install dependencies
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node dependencies
echo "Installing NPM dependencies..."
npm install

# Setup environment
echo "Setting up environment..."
cp .env.example .env

# Generate app key
echo "Generating application key..."
php artisan key:generate

# Run migrations
echo "Running database migrations..."
php artisan migrate:fresh --seed

# Start Laravel server
echo "Starting Laravel development server..."
php artisan serve --host=0.0.0.0 --port=8000
