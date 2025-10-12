# Back-end Labs (Laravel project)

## About project
This project is a simple Laravel application with a `/healthcheck` endpoint that returns a JSON response. 

## Requirements
To run this project, make sure you have one of the following installed:

1. Local option:
    - [PHP 8.2+](https://www.php.net/downloads)  
    - [Composer](https://getcomposer.org/download/)   
2. Docker option:
    - [Docker](https://www.docker.com/get-started) 
    - [Docker Compose](https://docs.docker.com/compose/install/)

## General setup

1. **Clone the repository:**
    ```bash
    git clone https://github.com/ErmishinS/backend-labs.git
    cd backend-labs
    ```

2.	**Copy and configure environment file:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

### Local Setup (without Docker)

1.	**Install dependencies:**
    ```bash
    composer install
    ```

2.	**Start the Laravel development server:**
    ```bash
    php artisan serve --host=0.0.0.0 --port=8000
    ```

3. You can check the application at `http://localhost:8000`

### Run with Docker

1.	**Build the Docker image:**
    ```bash
    docker-compose build
    ```

2.	**Start the container:**
    ```bash
    docker-compose up
    ```

3.	You can check the application at `http://localhost:8000`

---
To test `/healthcheck` route you can use curl from terminal:
```bash
curl http://localhost:8000/healthcheck
```

    



