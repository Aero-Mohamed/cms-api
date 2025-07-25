
# 🚀 Setup Instructions
---

- [Prerequisites](#Prerequisites)
- [Instructions](#Instructions)

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
