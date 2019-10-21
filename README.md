# Patoche

Welcome to Patoche, a tool to automatically release and deploy the Onboarder.
The whole release process is documented [here](https://github.com/akeneo/onboarder/blob/master/doc/release-process.md).

![Patoche](patoche.jpg)

## How to use it

The following README assume you are using Docker and Docker Compose.

However, it should be quite easy to use it without Docker: all commands used in the Makefile are Symfony commands and other PHP tools. Just run them without `docker-compose run --rm php`.

### Configure the GitHub API

You need to configure two environment variables so Patoche can discuss with the GitHub API (using `knplabs/github-api`).
```dotenv
GITHUB_USERNAME=username
GITHUB_SECRET=password_or_token
```

The easiest method is to set those two variables in a `.env.local` file. This way it will be directly used by Symfony,
regardless of the environment (production, acceptance, integration, etc.) or of your usage of Docker or not.

### Run the application

First, install the dependencies:
```bash
$ make install
```

Then run the application
```bash
$ make onboarder-release x.y
```
where `x.y` is the branch you want to tag from.

You can optionally chose an organization (default is `akeneo`) if you want to test Patoche from forked repositories:
```bash
$ make onboarder-release x.y [organization]
```

You can [dump the workflow](https://symfony.com/doc/current/workflow/dumping-workflows.html)
and visualize it as a PNG graphic (other image format are available).

You first need to install `xdot` on your machine. Then run
```bash
$ make dump-workflow
```

### Testing the application

You can run the full test suite by running:
```bash
$ make tests
```

Each kind of test can be also run individually, please look at the [Makefile](https://github.com/akeneo/patoche/blob/master/Makefile).
For instance, to run only `php-cs-fixer`, run:
```bash
$ make code-style
```

There is also a script to apply `php-cs-fixer` fixes (no dry-run) that is not ran by `make tests`:
```bash
$ make fix-code-style
```

### Debugging

Assuming your IDE is correctly configured for debugging, it is possible to debug the application using the Makefile.

The command `onboarder-release` and all tests command can be debug by setting the environment variable `DEBUG` to true.
For instance, if you want to debug a specification, run the following command:
```bash
$ make specification DEBUG=true
```
