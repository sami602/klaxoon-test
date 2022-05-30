# Klaxoon Test

You only need `docker` and `docker-compose` installed

## Start server

The following command will start the development server at URL http://127.0.0.1:8000/:

```bash
./dc up # dc is a wrapper around docker-compose that allows services to be run under the current user
```

## Init Database

```bash

# Execute initial database migration
./dc exec php bin/console do:mi:mi

```
# Documentation

## List Links

    GET /links

**Response**

* `200` Links list

## Add a link

    POST /links/add
    
    {
        "url": "<Vimeo or Flickr URL>"
    }

**Responses**

* `201` Link saved
* `400` Bad Request
* `404` Media not found
* `501` Provider not implemented


## Delete a Link

    DELETE /links/{id}
    
**Response**

* `204`

    
# Improvement points
 * Todo add authentication and voters to manage write and read permissions to have a totally secure api
 * Todo refactor link creation in a factory
 * Todo Add phpunit tests (current testing have only been done manually with Postman)

## Useful commands

```bash
# Composer is installed into the php container, it can be invoked as such:
./dc exec php composer [command]

# This is a Symfony Flex project, you can install any Flex recipe
./dc exec php composer req annotations

# Symfony console
./dc exec php bin/console

# Start the MySQL cli
./dc exec mysql mysql symfony

# Stop all services
./dc down
```
