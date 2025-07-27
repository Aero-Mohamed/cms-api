
# 🚀 Setup Instructions
---

- [Prerequisites](#Prerequisites)
- [Using Docker](#Instructions)
- [Manual Installation](#ManualInstallation)

## 🚢 Quick Start with Docker

<a name="Prerequisites"></a>
### Prerequisites
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/)

<a name="Instructions"></a>
### Instructions
- **Clone the repository**
```bash
git clone https://github.com/Aero-Mohamed/cms-api.git
cd cms-api
chmod 644 ./docker/mysql/my.cnf
```
As we are in development mode, we mount the current project directory into the container.
So, we need to change the permissions of the MySQL configuration file.

- **Build and Start Containers**
```bash
docker-compose up -d --build
docker exec -it dynamic_cms bash
```
This builds the Docker containers and starts the Laravel app, MySQL, Redis, and Nginx services.
Then, Open an interactive terminal session inside the Laravel PHP container (called dynamic_cms).

- **Run the Installation Script**
```bash
chmod 755 install.sh
./install.sh
```

## 🖥️ Manual Installation <a name="ManualInstallation"></a>

If you prefer not to use Docker, you can install the project directly on your system. Note that you will need to configure the database and cache manually.

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Redis (for cache)
- Git

### Instructions

- **Clone the repository**
```bash
git clone https://github.com/Aero-Mohamed/cms-api.git
cd cms-api
```

- **Install Dependencies**
```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
```

- **Configure Environment**
```bash
cp .env.example .env
```
Edit the `.env` file and update the following settings:

- Database Configuration
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dynamic_cms
DB_USERNAME=your_mysql_username
DB_PASSWORD=your_mysql_password
```

- Redis Configuration:
```dotenv
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

- **Run the Installation Script**
```bash
chmod 755 install.sh
./install.sh
```

- **Start the Development Server**
```bash
php artisan serve
```

The application should now be running at `http://localhost:8000`.

> **Note:** Make sure both MySQL and Redis servers are running before starting the application. You may need to install and configure these services according to your operating system's requirements.
