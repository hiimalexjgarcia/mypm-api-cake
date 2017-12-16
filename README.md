# MyPM API Service

This is a super basic project management system, accommodating projects and simple tasks, and that's all at the moment. It's implemented in [CakePHP](https://github.com/cakephp), utilizing the [Friends Of Cake](https://github.com/friendsofcake) plugins friendsofcake/crud, friendsofcake/crud-json-api for [JSON API](http://jsonapi.org/) support, and friendsofcake/search. A MySQL database server is required.

## Installation

1. Download [Composer](https://getcomposer.org/doc/00-intro.md) or, if Composer is already installed globally, update `composer self-update`.
2. Run `php composer.phar install` or, if Composer is installed globally, `composer install
`.
3. Create two MySQL databases and dedicated user:
```bash
CREATE DATABASE mypm;
CREATE DATABASE test_mypm;
GRANT ALL ON mypm.* to 'mypmuser'@'%' IDENTIFIED BY 'mypmpass';
GRANT ALL ON test_mypm.* to 'mypmuser'@'%';
```
4. Set the following environment variables:
```bash
export APP_NAME="mypm" # this is used as a reference for the database name, see DATABASE_URL and DATABASE_TEST_URL below
export DEBUG="true"
export APP_ENCODING="UTF-8"
export APP_DEFAULT_LOCALE="en_US"
export SECURITY_SALT="77a73ac88eaf1cbea1a8f9b9f00ab8c933c33b53adcec42218e0c95594d96f00" # set this to something unique
export CACHE_DURATION="+2 minutes"
export CACHE_DEFAULT_URL="file://./tmp/cache/?prefix=${APP_NAME}_default&duration=${CACHE_DURATION}"
export CACHE_CAKECORE_URL="file://./tmp/cache/persistent?prefix=${APP_NAME}_cake_core&serialize=true&duration=${CACHE_DURATION}"
export CACHE_CAKEMODEL_URL="file://./tmp/cache/models?prefix=${APP_NAME}_cake_model&serialize=true&duration=${CACHE_DURATION}"
export DATABASE_URL="mysql://mypmuser:mypmpass@your_mysql_service_ip/${APP_NAME}?encoding=utf8&timezone=UTC&cacheMetadata=true&quoteIdentifiers=false&persistent=false"
export DATABASE_TEST_URL="mysql://mypmuser:mypmpass@your_mysql_service_ip/test_${APP_NAME}?encoding=utf8&timezone=UTC&cacheMetadata=true&quoteIdentifiers=false&persistent=false"
```
5. Run the migrations script to build and seed the database:
```bash
bin/cake migrations migrate
bin/cake migrations seed
```
6. Run the local development server:
```bash
bin/cake server -H 0.0.0.0 # to make the service available on all local IPs
```
(I've included a BASH script in the root directory, "cake" that does these exports for now. Edit it and run it ala `./cake migrations migrate`.)

You should see the service available at http://localhost:6785.

## Usage

To get a list of projects:
```bash
curl -H "Accept: application/vnd.api+json" "http://localhost:8765/api/projects"
```
```
{
    "data": [
        {
            "type": "projects",
            "id": "1",
            "attributes": {
                "title": "Default Project",
                "description": null,
                "created": "2017-12-09T02:51:26+00:00",
                "modified": "2017-12-09T02:51:26+00:00"
            },
            "links": {
                "self": "\/api\/projects\/1"
            }
        }
    ]
}
```

Likewise, to get a list of tasks:
```bash
curl -H "Accept: application/vnd.api+json" "http://localhost:8765/api/tasks"
```
```
{
    "data": [
        {
            "type": "tasks",
            "id": "1",
            "attributes": {
                "title": "Do something.",
                "description": "Do something. Anything.",
                "created": "2017-12-09T02:51:26+00:00",
                "modified": "2017-12-09T02:51:26+00:00"
            },
            "links": {
                "self": "\/api\/projects\/1"
            }
        }
    ]
}
```

To get all tasks for a project:
```bash
curl -H "Accept: application/vnd.api+json" "http://localhost:8765/api/tasks?filter[projects]=1"
```
```
{
    "data": [
        {
            "type": "tasks",
            "id": "1",
            "attributes": {
                "title": "Do something.",
                "description": "Do something. Anything.",
                "complete": false,
                "created": "2017-12-09T06:11:54+00:00",
                "modified": "2017-12-09T06:11:54+00:00"
            },
            "links": {
                "self": "/api/tasks/1"
            }
        }
    ]
}
```

To get all incomplete tasks for a project:
```bash
curl -g -s -H "Accept: applcation/vnd.api+json" "http://localhost:8765/api/tasks?filter[projects]=1&filter[complete]=false"
```
```
{
    "data": [
        {
            "type": "tasks",
            "id": "1",
            "attributes": {
                "title": "Do something.",
                "description": "Do something. Anything.",
                "complete": false,
                "created": "2017-12-09T06:11:54+00:00",
                "modified": "2017-12-09T06:11:54+00:00"
            },
            "links": {
                "self": "/api/tasks/1"
            }
        }
    ]
}
```

To get a specific task with associated project:
```bash
curl -H "Accept: application/vnd.api+json" "http://localhost:8765/api/tasks/1?include=projects"
```
```
{
    "data": {
        "type": "tasks",
        "id": "1",
        "attributes": {
            "title": "Do something.",
            "description": "Do something. Anything.",
            "complete": false,
            "created": "2017-12-09T06:11:54+00:00",
            "modified": "2017-12-09T06:11:54+00:00"
        },
        "relationships": {
            "project": {
                "data": {
                    "type": "projects",
                    "id": "1"
                },
                "links": {
                    "self": "/api/projects/1"
                }
            }
        },
        "links": {
            "self": "/api/tasks/1"
        }
    },
    "included": [
        {
            "type": "projects",
            "id": "1",
            "attributes": {
                "title": "Default Project",
                "description": null,
                "created": "2017-12-09T02:51:26+00:00",
                "modified": "2017-12-09T02:51:26+00:00"
            },
            "links": {
                "self": "/api/projects/1"
            }
        }
    ]
}
```

(more examples to come...)