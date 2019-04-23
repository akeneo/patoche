# Patrick Tag

Welcome to Patrick Tag, a tool to automatically tag the Onboarder.

![Patoche](patoche.jpg)

## How to use it

The following README assume you are using Docker and Docker Compose.

However, it should be quite easy to use it without Docker: all commands listed below are Symfony ones and other PHP tools. Simply run them without `docker-compose run --rm php`.

### Building the Docker image

Build the Docker image
```bash
$ docker-compose build --pull
```

### Setup Docker Compose

You can copy `docker-compose.override.yaml.dist` as `docker-compose.override.yaml` and set it up to your liking.
This will allow you to share `composer` cache and configuration between your host and the `php` container.

The YAML override also allows you to activate and configure `xdebug`.
More details about both `composer` and `xdebug` are available directly in `docker-compose.override.yaml.dist`.

### Run the application

Install the dependencies
```bash
$ docker-compose run --rm php composer install
```

Then run the application
```bash
$ # COMING SOON
```

You can [dump the workflow](https://symfony.com/doc/current/workflow/dumping-workflows.html) and visualize it as a PNG graphic (other image format are available).
You first need to install `xdot` on your machine. Then run
```bash
$ docker-compose run --rm php bin/console workflow:dump tagging | dot -Tpng -o tagging_workflow.png && xdg-open tagging_workflow.png
```

### Testing the application

Everything listed here is ran on the CI.

- Code style
  ```bash
  $ docker-compose run --rm php vendor/bin/php-cs-fixer fix --diff --dry-run --config=.php_cs.php
  ```

- Static Analysis
  ```bash
  $ docker-compose run --rm php vendor/bin/phpstan analyse src -l 7
  ```

- Specifications
  ```bash
  $ docker-compose run --rm php vendor/bin/phpspec run
  ```

- Acceptance tests
  ```bash
  $ docker-compose run --rm php vendor/bin/behat -p acceptance
  ```
