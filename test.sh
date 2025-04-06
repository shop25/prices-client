#!/usr/bin/bash

docker compose run --rm php bash -c "composer install; composer run test"
