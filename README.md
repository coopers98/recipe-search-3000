# Recipe Search 3000

A web-based application that enables users to search recipes by keywords, ingredients, and author email.

## Features

- Search recipes by keywords, ingredients, or author email
- View detailed recipe information including ingredients and steps
- Normalized database structure for improved data integrity and search capabilities
- Responsive design for all devices
- Pagination for search results
- Laravel API Resources for standardized JSON responses

## Documentation

- [Recommendations](RECOMMENDATIONS.md) - Suggestions for future improvements

## Getting started

### Pre-requisites
- docker
- docker-compose

### Check out this repository
`git clone git@github.com:coopers98/recipe-search-3000.git`

`cd skeleton-app`

### Run composer to kickstart laravel sail

```bash
docker run --rm \
    --pull=always \
    -v "$(pwd)":/opt \
    -w /opt \
    laravelsail/php82-composer:latest \
    bash -c "composer install"
```

### Run the application
`cp .env.example .env`

`./vendor/bin/sail up -d`

`./vendor/bin/sail artisan key:generate`

`./vendor/bin/sail artisan migrate`

`./vendor/bin/sail artisan recipes:seed`

### Kickstart the nuxt frontend
`./vendor/bin/sail npm install --prefix frontend`

### Run the frontend
`./vendor/bin/sail npm run dev --prefix frontend`

### Confirm your application
visit the frontend http://localhost:3000

visit the backend http://localhost:8888


### Connecting to your database from localhost
`docker exec -it laravel-mysql-1 bash -c "mysql -uroot -ppassword"`

Or use any database GUI and connect to 127.0.0.1 port 3333


## Testing

This project includes test suites for both backend and frontend components.

### Prerequisites for Testing

#### Node.js Version Requirements

This sail installation is using node 22

### Running Tests

#### Backend Tests
```bash
# Run all backend tests
./vendor/bin/sail artisan test

# Run specific test file
./vendor/bin/sail artisan test tests/Unit/RecipeModelTest.php

# Run with coverage
./vendor/bin/sail artisan test --coverage
```

#### Frontend Tests
```bash
# Run all frontend tests
./vendor/bin/sail npm test --prefix frontend

# Run tests in watch mode (for development)
./vendor/bin/sail npm run test:watch --prefix frontend

# Run specific test file
./vendor/bin/sail npm test --prefix frontend -- tests/components/RecipeCard.test.js
```


### Other tips
`./vendor/bin/sail down` to bring down the stack

Sometimes it's necessary to restart the nuxt app when adding new routes. Simply `ctrl+c` on the npm command execute
`./vendor/bin/sail npm run dev --prefix frontend` again
