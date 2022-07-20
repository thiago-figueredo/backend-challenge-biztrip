# Requirements 

- PHP >= 7.3
- Laravel >= 8
- mysql
- composer

# Documentation
See https://documenter.getpostman.com/view/12699485/UzR1J2Me

---

## Getting Started

---

### 1. Install dependencies

```bash
composer install
```

### 2. Copy .env.example to .env

```bash
cp .env.example .env
```

### 3. Start mysql server

```bash
sudo service mysql start
```

### 4. Create Database

```bash
sudo mysql -u root -p
Enter password: (enter your root password)

mysql> CREATE DATABASE laravel;
mysql> exit;

```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Initialize server

```bash
php artisan server --port=3000;
```

---

## Other Options

#### If you don't want start application manually, you can run start.sh and set the root password of your system

```bash
./start.sh "your root password"
```
