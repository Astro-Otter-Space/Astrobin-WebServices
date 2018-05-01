[![Build Status](https://travis-ci.org/HamHamFonFon/Astrobin-API-PHP.svg?branch=master)](https://travis-ci.org/HamHamFonFon/Astrobin-API-PHP)

# Astrobin API for PHP

[TOC]

Version 0.5.5

Caution : API currently in progress, this is not a final version.

## Requirements
* PHP 5.6 min or superior
* API Key and API Secret from [Astrobin](https://www.astrobin.com/api/request-key/)

## Introduction


Astrobin API is a PHP library made to retrieve astrophotography from [Astrobin](http://www.astrobin.com).

## Installing

You can install this API in 3 different ways.

* If you just want to make some issues, make some simple tests etc, juste clone the repository

> `git clone git@github.com:HamHamFonFon/Astrobin-API-PHP.git`


* If you want to add to your own composer.json project :

```json
    [...]
    "require" : {
        [...]
        "hamhamfonfon/astrobin" : "dev-master"
    },
    "repositories" : [{
        "type" : "vcs",
        "url" : "https://github.com/HamHamFonFon/Astrobin-API-PHP.git"
    }],
    [...]
```

Then run
> `composer update hamhamfonfon/astrobin`

* Soon, adding with composer from packagist.


### Usage
-----

With Symfony, you can set WebService class as services :

First, set your keys in parameters.yml :
```yml
parameters:
    astrobin.key: <your_api_key>
    astrobin.secret: <your_secret_key>
```

Symfony 2:

```yml
astrobin.webservice:
    class: Astrobin\AbstractWebService
    abstract: true
    arguments:
      - "%astrobin.key%"
      - "%astrobin.secret%"
# Images WS
astrobin.webservice.getimage:
    class: Astrobin\Services\GetImage
    parent: astrobin.webservice
# Collection WS
astrobin.webservice.getcollection:
    class: Astrobin\Services\GetLocation
    parent: astrobin.webservice
# Location WS
astrobin.webservice.getlocation:
    class: Astrobin\Services\GetLocation
    parent: astrobin.webservice
# Today location
astrobin.webservice.gettodayimage:
    class: Astrobin\Services\GetTodayImage
    parent: astrobin.webservice
```

In your controller :
> Exemple : i want to retrieve 5 photos from Orion Nebula (M42)
```php
$astrobinWs = $this->container->get('astrobin.webservice.getimage');
$data = $astrobinWs->getImagesBySubject('m42', 5);
```


## WebServices

The library expose 4 WebServices, each with these methods below.

### GetImage :

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getImageById()`| `$id`       | `Image` |
| `getImagesBySubject()`| `$subjectId`  `$limit`     | `ListImage`,`Image` |
| `getImagesByDescription()`| `$description`  `$limit`     | `ListImage`,`Image` |
| `getImagesByUser()`| `$userName`  `$limit`     | `ListImage`,`Image` |
| `getImagesByRangeDate()`| `$dateFromStr` (ex: 2018-04-01),   `$dateToStr` (2018-04-31 or null) | `ListImage`,`Image` |

### GetTodayImage :

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getDayImage()`      | `$offset` ,  limit = 1      | `Today` |
| `getTodayDayImage()`   |   | `Today` |

### GetCollection :

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getCollectionById()`| `$id`| `Collection` |
| `getCollectionByUser()`|`$user`,`$limit`| `ListCollection` |

### GetLocation :
*In progress...*

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getLocationById()`      | `$id`       | `Location` |


## Responses

### Image
| Parameter| Description |
| ------------- | ------------------------------ |
| `title`      | Title of image       |
| `subjects`      | Keywords      |
| `description`      | Description      |
| `url_gallery`      | URL of image for gallery       |
| `url_thumb`      | URL of image , thumb size      |
| `url_regular`      | URL of image      |
| `user`      | Username      |

![](https://image.noelshack.com/fichiers/2018/17/5/1524854105-image.png)

### ListImage
| Parameter| Description |
| ------------- | ------------------------------ |
| `listImages`      | List of images       |

![](https://image.noelshack.com/fichiers/2018/18/1/1525117490-list-images.png)

### Collection
| Parameter| Description |
| ------------- | ------------------------------ |
| `id`      | Identifier       |
| `name`      | Name of collection      |
| `description`      | Description      |
| `user`      | User name       |
| `date_created`      | Date of creation      |
| `date_updated`      | Date of modification      |
| `images`      | Path of WS Image      |
| `listImages`      | Path of WS Image      |

![](https://image.noelshack.com/fichiers/2018/18/2/1525187691-collection.png)

### ListCollection
| Parameter| Description |
| ------------- | ------------------------------ |
| `listCollection`      | List of collection with list of images       |
![](https://image.noelshack.com/fichiers/2018/18/2/1525189056-listcollection.png)

### Today
| Parameter| Description |
| ------------- | ------------------------------ |
| `date`      | Date of image       |
| `resource_uri`      | URI of image       |
| `listImages`      | List of images       |

![](https://image.noelshack.com/fichiers/2018/18/1/1525117371-today.png)

### Location
*In progress*

## Running the tests
*In progress*

## Authors
Stéphane Méaudre  - <balistik.fonfon@gmail.com>

## Licence

This project is licensed under the MIT License - see the LICENSE.md file for details
