# Patrick Tag

Welcome to Patrick Tag, a tool to automatically tag the Onboarder.

![Patoche](patoche.jpg)

## How to use it with Docker

Build the Docker image
```bash
$ docker-compose build --pull
```

Optionally copy `docker-compose.override.yaml.dist` as `docker-compose.override.yaml` and setup it as your liking.

Install the dependencies
```bash
$ docker-compose run --rm php composer install
```

## How to use it without Docker

Just do the same, without any call to `docker-compose` :slightly_smiling_face:.
