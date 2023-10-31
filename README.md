# Exercise PP, pregenerated promo codes.

### Prerequisites

- Docker
- Docker Compose
- Make

## Installation

Clone the repo locally, then

```sh
make init
make migrate-db
make generate-import-promos
```

This will build, initialize and start the development server on port 8080 of your local machine. 
You can now access your application by visiting http://127.0.0.1:8080 in your web browser.

To uninstall the project, run:

```sh
make remove
```

### Commands reference

| Command                       | Description                                                   |
|-------------------------------|---------------------------------------------------------------|
| `make up`                     | Boot up docker containers                                     |
| `make down`                   | Stop and remove docker containers and networks                |
| `make restart`                | Restart docker containers                                     |
| `make test`                   | Run test suite                                                |
| `make fix`                    | Run php-cs-fixer to fix codebase according PER standards      |
| `make ssh`                    | SSH into app container                                        |
| `make remove`                 | Unsintall the project, remove all containers and volumes      |
| `make init`                   | Initialize the project, run containers, install composer deps |
| `make migrate-db`             | Apply DB migrations to dev and test environments              |
| `make generate-import-promos` | Generates a CSV file with promo codes and imports it into DB  |

### You're ready to go! 
