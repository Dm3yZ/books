Books
========================

Run project
-----
```bash
$ cd docker/
$ docker-compose up -d
```

Usage
-----

Create file `http-client.env.json` from `http-client.env.json.dist` in request directory. 
Run request from `books.http`.

Tests
-----

```bash
$ cd docker/
$ docker-compose exec php-fpm bin/phpunit
```
