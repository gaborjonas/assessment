## URL shortener assignment

The app was built using Sail, therefore it requires docker and compose.

**Setting up the application**

Clone the repository
```sh
git clone git@github.com:gaborjonas/assessment.git && cd assessment
```

The containers use the default ports (80, 6379).
If these are already used, update `APP_PORT`, `REDIS_PORT` and FORWARD_REDIS_PORT` in `.env.example` before running the containers.


Install composer packages, create .env, run the containers and generate app key
```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs && \
    cp .env.example .env && \
    ./vendor/bin/sail up --remove-orphans -d && \
    ./vendor/bin/sail artisan key:generate
```

**Running checks and tests**
```sh
./vendor/bin/sail run composer test
```

**Testing endpoints**

[Postman collection](https://github.com/gaborjonas/assessment/blob/main/Assesment.postman_collection.json)

Using curl
```sh
curl --location 'http://localhost/api/encode' \
--header 'Content-Type: application/json' \
--data '{
    "url": "https://www.thisisalongdomain.com/with/some/parameters?and=here_too"
}'
```

```sh
curl --location 'http://localhost/api/decode' \
--header 'Content-Type: application/json' \
--data '{
    "url": "https://short.est/N2IzMTl"
}'
```
