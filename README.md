# Patoche

Welcome to Patoche, a tool to automatically tag and deploy the Onboarder.

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
$ docker-compose run --rm php akeneo:patoche:onboarder-release x.y [organization]
```
where `x.y` is the branch you want to tag from. You can optionally chose an organization (default is `akeneo`) if you want to test Patoche from forked repositories.

You can [dump the workflow](https://symfony.com/doc/current/workflow/dumping-workflows.html) and visualize it as a PNG graphic (other image format are available).
You first need to install `xdot` on your machine. Then run
```bash
$ docker-compose run --rm php bin/console workflow:dump tagging | dot -Tpng -o tagging_workflow.png && xdg-open tagging_workflow.png
```

### Testing the application

You can run the full test suite by running:
```bash
$ docker-compose run --rm php composer tests
```

Each kind of test can be also run individually, please look at the list in [composer.json](https://github.com/akeneo/patrick-tag/blob/master/composer.json).
For instance, to run only `php-cs-fixer`, run:
```bash
$ docker-compose run --rm php composer php-cs-fixer
```

There is also a script to apply `php-cs-fixer` fixes (no dry-run) that is not ran by `composer tests`:
```bash
$ docker-compose run --rm php composer php-cs-fixer-fix
```
