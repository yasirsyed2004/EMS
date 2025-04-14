<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Mini Employee Management System

This is a simple Laravel-based mini Employee Management System, built to manage users, employees, departments, roles, and permissions. It uses [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) for role and permission management.

### Requirements

- PHP "7.3|8.0"
- Composer >= 2.0
- MySQL

### Installation

1. **Clone the repository**

```bash
git clone https://github.com/yasirsyed2004/EMS.git
cd your-repo

```

2. **Install Composer dependencies**

```bash
composer update

```
3. **Create .env file**

```bash
cp .env.example .env

```
4. **Configure environment variables**

Open the .env file and update your database credentials:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=

```
5. **Generate application key**

```bash
php artisan key:generate

```
6. **Run database migrations**

```bash
php artisan migrate
```
7. **Seed the database with dummy data**

```bash
php artisan db:seed

```
This will insert sample data into the following tables:

- Users
- Departments
- Roles
- Permissions (via Spatie)
- Employees

**Features Implemented**
```bash
Laravel 8.83 support with PHP 8.0

```
**Role and permission management using Spatie Laravel Permission**

Database migrations and seeders for:

- Users
- Roles
- Permissions
- Departments
- Employees

## Authentication (Laravel Passport)

This project uses [Laravel Passport](https://laravel.com/docs/10.x/passport) for API authentication.

### 8. Install Passport

<!-- ```bash
composer require laravel/passport -->


### 8. Frontend Setup

```bash
npm install

```
### 9. 🛠 Development
Run these commands in separate terminal tabs:

**Tab 1 - Laravel Server:**

```bash
php artisan serve

```
**Tab 2 - Frontend Compilation:**

```bash
npm run watch
```
Or for Hot Module Replacement (HMR):
```bash
npm run hot
```
### 10. 📂 Project Structure
├── app/        
├── database/ 
├── Modules/           
├── resources/
│   ├── css/            
│   │   ├── app.css/    # Global assets
│   ├── js/            # Vue 3 components
│   │   ├── components/ # components
│   │   ├── router/    # Vue Router config
│   │   ├── stores/    # Pinia stores
│   │   ├── layout/    # layout view
│   │   ├── service/    # api service
│   │   └── views/     # Page components
│   └──
├── routes/
│   └── web.php        # Web route
├── webpack.mix.js     # Frontend build config

### 11. 🌟 Frontend Features

- Vue 3 with Composition API

- Pinia for centralized state management

- Vue Router for SPA navigation

- Axios for API communication

- Custom Bootstrap

### 12. 🔒 Authentication Flow

- User logs in via /login

- Backend returns passport token

- Token stored in Pinia store

- Axios interceptor adds token to all requests

- Protected routes check authentication state
