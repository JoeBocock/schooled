## About Schooled

Schooled is an example Laravel project that consumes school data and presents certain information about entities often found within a school. E.G. Classes, Students and Employees.

## Getting Started

Schooled uses Laravel Sail to power both the local environment as well as the GitHub workflows. To get up and running locally please follow the below steps. Please note that `docker` must be installed (along with Docker Compose V2).

As a final note, please make sure nothing is running on the common Sail ports. They can be found within the root level `docker-compose.yml`.

First, clone the repository and enter the directory.
```sh
git clone git@github.com:JoeBocock/schooled.git

cd schooled
```

<br>

Next, install the composer deps.
```sh
make install
```

<br>

Thirdly, copy the example env.
```sh
cp .env.example .env
```

<br>

Then bring up sail.
```sh
make up
```

<br>

Next, generate the application key.
```sh
make key
```

<br>

Run the migrations.
```sh
make migrate
```

<br>

Install NPM packages.
```sh
make npm-install
```

<br>

Build the FE assets with vite
```sh
make vite-build
```

<br>

Finally, you should set you environment variables. See `{provider}_KEY` & `{provider}_SCHOOL_ID`

## Extra Commands

There are a few other common commands that the makefile supports.

- `make test` will run the test suite.
- `make lint` will lint the codebase.
- `make format` will format the codebase.
- `make stan` will run static analysis on the codebase.

Also note that Sail is available for any other commands you might need.
- `./vendor/bin/sail artisan list`
