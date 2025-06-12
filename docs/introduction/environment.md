# Environment variables

To configure the application, you can pass environment variables to the application. lawtex offers different configuration possibilities.


## Required environment variables

| Variable      | Description                                                           |
|---------------|-----------------------------------------------------------------------|
| ADMIN_EMAIL   | E-Mail Address of the Admin user                                      |
| APP_URL       | The URL of the application                                            |
| ASSET_URL     | Should have the same value as APP_URL                                 |
| DB_CONNECTION | The database connector that should be used (e.g., sqlite, mysql, ...) |


## Admin user

On the first start of the application, an admin user is created. You can set the following environment variables to configure the admin user:

**Required**
- `ADMIN_EMAIL`: The email of the admin user

**Optional**
- `ADMIN_PASSWORD`: The password of the admin user

If you do not set the `ADMIN_PASSWORD` variable, a random password is generated.

::: warning Remember the password!
If no `ADMIN_PASSWORD` is set, the password is generated randomly and only displayed once in the logs. Make sure to save the password.
:::

## Hosting and Domain

You can set the following environment variables to configure the URL of the application:

**Required**
- `APP_URL`: The URL of the application
- `ASSET_URL`: Should be set to the same value as `APP_URL`

**Optional**
- `APP_PORT`: The port of the application (default: `80`)
- `FORCE_HTTPS`: If set to `true`, the application will redirect all requests to HTTPS (default: `false`)
- `APP_DEBUG`: If set to `true`, the application will show debug information (default: `false`)

::: danger `APP_DEBUG` in production
You should never set `APP_DEBUG` to `true` in production environments. This could expose sensitive information and could make your application vulnerable for attacks.
:::

## Registration

You can set the following environment variables to configure the registration of new users:

**Optional**
- `ALLOW_REGISTRATION`: If set to `true`, users can register themselves (default: `true`)

## Database

You can set the following environment variables to configure the database connection:

**Required**
- `DB_CONNECTION`: The database connector that should be used (e.g., sqlite, mysql, ...)

**Optional**
- `DB_HOST`: The host of the database
- `DB_PORT`: The port of the database
- `DB_DATABASE`: The name of the database
- `DB_USERNAME`: The username for the database
- `DB_PASSWORD`: The password for the database

## Enlightn <Badge type="tip" text="^v0.2" />

Enlightn is a package that helps you to improve the security and performance of your Laravel application. You can set the following environment variables to configure Enlightn:

**Optional**
- `ENLIGHTN_RUN_ON_PRODUCTION`: If set to `true`, Enlightn will run once on every start (default: `false`)
- `ENLIGHTN_USERNAME`: The username for the Enlightn Web UI. Only needed when a report should be uploaded
- `ENLIGHTN_API_TOKEN`: The API token for the Enlightn Web UI. Only needed when a report should be uploaded
