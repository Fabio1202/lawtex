# Volumes 

Some data of lawtex must be persisted between different runs of the program. For docker, this is done by using volumes. The following volumes are used by lawtex:

| Volume       | Description                                    |
|--------------|------------------------------------------------|
| /var/www/env | Used for persisting the .env file between runs |

## /var/www/env

This volume is used for persisting the .env file between runs. This is mostly used for the generated key. The .env file is generated on the first run of the program and is persisted between runs. 
::: info
If the .env file is not persisted between runs, the key will be regenerated with every container. This would cause all magic links to be invalidated.
:::
