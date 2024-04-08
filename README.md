<p align="center">
<img src="https://raw.githubusercontent.com/Fabio1202/lawtex/main/public/images/logo-no-background.svg#gh-dark-mode-only" width=500/>
<img src="https://raw.githubusercontent.com/Fabio1202/lawtex/main/public/images/logo-black.svg#gh-light-mode-only" width=500/>
</p>

# lawtex

## Description

`lawtex` is a PHP application that allows to manage law texts for an latex document. Multiple projects can contain different laws. The parsed file can be downloaded or embedded, e.g. in Overleaf, via a magic link.

## Running the app
The app is dockerized with each release and includes a nginx server running the application. You can find the docker image on [Docker Hub](https://hub.docker.com/r/fabioboi/lawtex). An example [docker-compose.yaml](deployment/docker-compose.yml) file is provided in the repository in the `deployment` folder.

## Currently supported parsers

We currently support only german law texts. The following sources are supported:

- [x] [gesetze-im-internet.de](https://www.gesetze-im-internet.de) (v0.1)
- [x] [dsgvo-gesetz.de](https://dsgvo-gesetz.de) (v0.2)

If you want to add a new parser, please open an issue or submit a pull request.


## Install local development environment

The application is built with Laravel and uses Laravel Sail for local development. Laravel Sail is a light-weight command-line interface for interacting with Laravel's default Docker development environment. Sail provides a great starting point for building a Laravel application with Docker. To install the local development environment, follow these steps:

1. Clone the repository:
    ```bash
    git clone https://github.com/Fabio1202/lawtex.git
    ```

2. Navigate to the project directory:
    ```bash
    cd lawtex
    ```

3. Install PHP dependencies, including Laravel Sail:
    ```bash
    composer install
    ```

4. Run Laravel Sail's up command. This command starts all of your Sail's containers:
    ```bash
    ./vendor/bin/sail up
    ```

5. Install JavaScript dependencies:
    ```bash
    ./vendor/bin/sail npm install
    ```

6. Copy the `.env.example` file to create your own environment configuration:
    ```bash
    cp .env.example .env
    ```

7. The `.env` file should already be configured to work with Laravel Sail. Update the `.env` file with your database and other configuration details if necessary.


8. Generate an application key:
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

9. Run database migrations:
    ```bash
    ./vendor/bin/sail artisan migrate
    ```
   
10. Start vite to serve the frontend:
    ```bash
    ./vendor/bin/sail npm run dev
    ```

11. The application should now be running on [http://localhost](http://localhost).

## Contributing

Contributions are welcome. Please open an issue or submit a pull request on GitHub.

## License

This project is licensed under the MIT License.
