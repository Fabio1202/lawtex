<p align="center">
<img src="./public/images/logo-no-background.svg#gh-dark-mode-only" width=500/>
<img src="https://raw.githubusercontent.com/Fabio1202/lawtex/main/public/images/logo-black.svg?token=GHSAT0AAAAAACP7TOYGW74QYXVBHHVM3CCAZQSYDMA#gh-light-mode-only" width=500/>
</p>

# lawtex

## Description

`lawtex` is a PHP application that allows to manage law texts for an latex document. Multiple projects can contain different laws. The parsed file can be downloaded or embedded, e.g. in Overleaf, via a magic link.

## Install local development environment

1. Clone the repository:
    ```
    git clone https://github.com/Fabio1202/lawtex.git
    ```

2. Navigate to the project directory:
    ```
    cd lawtex
    ```

3. Install PHP dependencies, including Laravel Sail:
    ```
    composer install
    ```

4. Run Laravel Sail's up command. This command starts all of your Sail's containers:
    ```
    ./vendor/bin/sail up
    ```

5. Install JavaScript dependencies:
    ```
    ./vendor/bin/sail npm install
    ```

6. Copy the `.env.example` file to create your own environment configuration:
    ```
    cp .env.example .env
    ```

7. The `.env` file should already be configured to work with Laravel Sail. Update the `.env` file with your database and other configuration details if necessary.


8. Generate an application key:
    ```
    ./vendor/bin/sail artisan key:generate
    ```

9. Run database migrations:
    ```
    ./vendor/bin/sail artisan migrate
    ```
   
10. Start vite to serve the frontend:
    ```
    ./vendor/bin/sail npm run dev
    ```

## Usage

To use the application, run the following command:

## Contributing

Contributions are welcome. Please open an issue or submit a pull request on GitHub.

## License

This project is licensed under the MIT License.
