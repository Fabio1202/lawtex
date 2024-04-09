# Run the application

The application can be run in a docker container. The image can be found on [Docker Hub](https://hub.docker.com/r/fabioboi/lawtex).

## Docker Compose

The following `docker-compose.yml` file can be used to run the application:


<!-- example yml -->
<<< ../../deployment/docker-compose.yml{11,14-15,17,31}

The marked environment values and the volume are required and must be set. If you would like to know more about the environment values, you can find them on the [configuration page](/environment).
