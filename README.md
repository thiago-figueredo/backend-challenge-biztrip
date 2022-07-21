# Requirements 

- PHP >= 7.3
- Laravel 8
- mysql
- composer

---

## Getting Started

---

### 1. Install dependencies

```bash
composer install
```

### 2. Start mysql server

```bash
sudo service mysql start
```

### 3. Create Database

```bash
sudo mysql -u root -p
Enter password: (enter your root password)

mysql> CREATE DATABASE laravel;
mysql> exit;

```

### 4. Update create .env file with following variable

- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=laravel
- DB_USERNAME=root
- DB_PASSWORD=your-password

---

### 5. Copy .env.example to .env

```bash
cp .env.example .env
```


### 6. Run migrations

```bash
php artisan migrate
```

### 7. Initialize server

```bash
php artisan server --port=3000;
```

---

## Other Options

#### If you don't want start application manually, you can update .env file and run start.sh.

```bash
./start.sh "your root password"
```

## Documentation

- http://localhost:3000/swagger

---
