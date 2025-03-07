# ACADALYZE

Welcome to **ACADALYZE**, a web application designed to analyze educational data and provide intuitive insights for students and educators. This project uses a PHP backend, a MySQL database, and includes a Docker-based development environment for easy setup and deployment.

## Table of Contents

- [Overview](#overview)
- [Prerequisites](#prerequisites)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Running the Application](#running-the-application)
- [Development](#development)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## Overview

ACADALYZE is built to provide a robust, scalable solution for tracking academic performance, generating reports, and offering data-driven recommendations. The application is structured with a PHP backend (powered by Apache), a MySQL database for data storage, and optional phpMyAdmin for database management. Docker is used to containerize the services, making it easy to develop, test, and deploy.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- **Docker** and **Docker Compose** (latest stable versions)
- **Git** (for cloning the repository)
- **Composer** (for PHP dependency management)
- **Node.js** and **npm** (for frontend development, if applicable)
- Optional: A code editor (e.g., Visual Studio Code, IntelliJ IDEA)

## Project Structure

```
Acadalyze/
├── backend/           # PHP source code and configuration
├── frontend/          # Frontend assets (if applicable, e.g., HTML, JS, CSS)
├── docker/            # Docker configuration files (e.g., Dockerfile, vhost.conf)
├── docker-compose.yml # Docker Compose configuration
├── .gitignore         # Files and directories to ignore in version control
├── README.md          # Documentation for whole project
```

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/aryanmalla19/Acadalyze
   cd Acadalyze
   ```

2. Install backend dependencies using Composer:

   ```bash
   cd backend
   ```

3. Install frontend dependencies using npm:

   ```bash
   cd ../frontend
   npm install -g yarn

   for running
   yarn dev
   ```

4. Build and start the Docker containers:
   ```bash
   docker-compose up
   ```
   This will start the PHP backend (on port 8080), MySQL database (on port 3306, if exposed), and phpMyAdmin (on port 8081).

## Running the Application

- Access the PHP backend at [http://localhost:8080](http://localhost:8080) in your web browser.
- The MySQL database is accessible internally by the backend as `mysql-data`.

## Development

### Modifying Code

- Place your PHP code in the `backend/` directory. Changes in this directory are automatically reflected in the running container due to the volume mount in `docker-compose.yml`.
- If your frontend is in `frontend/`, update or build assets as needed (e.g., using Node.js, Webpack, or similar tools).

### Adding Dependencies

- For PHP dependencies, use Composer in the `backend/` directory and commit the `composer.json` and `composer.lock` files (but ignore `vendor/` in `.gitignore`).
- For frontend dependencies, use npm or Yarn in the `frontend/` directory, committing `package.json` and `package-lock.json` (but ignoring `node_modules/`).

## Contact

For questions or support, reach out to [ aryanmalla19@gmail.com ].

Happy coding with **ACADALYZE**! 🚀
