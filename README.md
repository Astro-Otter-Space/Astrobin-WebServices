[![Build Status](https://travis-ci.org/HamHamFonFon/Astrobin-WebServices.svg?branch=master)](https://travis-ci.org/HamHamFonFon/Astrobin-WebServices) [![codecov.io](https://codecov.io/gh/HamHamFonFon/Astrobin-WebServices/branch/master/graphs/badge.svg?branch=master)](https://codecov.io/gh/HamHamFonFon/Astrobin-WebServices/branch/master/graphs/badge.svg?branch=master)
# WebServices for Astrobin's API REST

## Table of contents

 * [Requirements](#requirements)
 * [Introduction](#introduction)
 * [Installing](#installing)
   * [Usage](#usage)
 * [WebServices](#webservices)
   * [GetImage](#getimage)
   * [GetTodayImage](#gettodayimage)
   * [GetCollection](#getcollection)
 * [Responses](#responses)
   * [Image](#image)
   * [ListImage](#listimage)
   * [Collection](#collection)
   * [ListCollection](#listcollection)
   * [Today](#today)
 * [Running the tests](#running-the-tests)
 * [Authors](#authors)
 * [Licence](#licence)

Version 2.1.0

## Requirements
* PHP 7.3 min or superior (oldest version of PHP is not supported)
* API Key and API Secret from [Astrobin](https://www.astrobin.com/api/request-key/)

## Introduction

Astrobin's WebServices is a PHP library for request Astrobin's API Rest and get amazing astrophotographies hosted on [Astrobin](http://www.astrobin.com).
Please read API section in ["Terms of service"](https://welcome.astrobin.com/terms-of-service)

## Installing

You can install this package in 2 different ways.

* Basic installation; just install package from composer :

> `composer require hamhamfonfon/astrobin-ws`

For update :
> `composer update hamhamfonfon/astrobin-ws`
>
* If you just want to make some issues, make some simple tests etc, juste clone the repository

> `git clone git@github.com:HamHamFonFon/Astrobin-Webservices.git`


* [OLD] If you want to add to your own composer.json project :

```json
    [...]
    "require" : {
        [...]
        "hamhamfonfon/astrobin-ws" : "dev-master"
    },
    "repositories" : [{
        "type" : "vcs",
        "url" : "https://github.com/HamHamFonFon/Astrobin-API-PHP.git"
    }],
    [...]
```

### Usage

First, set your keys in .env file :
```yml
ASTROBIN_API_KEY=PutHereYourOwnApiKey
ASTROBIN_API_SECRET=PutHereYourOwnApiSecret
```

Exemple with Symfony 4:
```php
use AstrobinWs\Response\AstrobinResponse;
use AstrobinWs\Services\GetImage;

class MyImageService
{
    /** @var GetImage **/ 
    private $astrobinImage;

    public function __construct()
    {
        $this->astrobinImage = new GetImage();
    }
    
    public function getImageById(): ?AstrobinResponse
    {
        return $this->astrobinImage->getImageById('1234');
    }

    public function getOrionNebula(): ?AstrobinResponse
    {
        $orionNebula = $this->astrobinImage->getImagesBySubject('m42', 10);
        // ...
        return $orionNebula;
    }
    
    public function getImagesOfSiovene(): ?AstrobinResponse
    {
        $listImages = $this->astrobinImage->getImagesByUser('siovene', 10);
        
        return $listImages;
    }
    
    public function getImagesByManyFilters(): ?AstrobinResponse
    {
        $filters = [
            'user' => 'toto',
            'subjects' => 'm31',
            'description__icontains' => 'wind'
        ];

        $listImages = $this->astrobinImage->getImageBy($filters, 10);
        
        return $listImages;
    }
}
```

## WebServices

The library expose 3 WebServices, each with these methods below.

### GetImage :

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getImageById()`| `$id` | `Image` |
| `getImagesBySubject()`| `$subjectId`  `$limit`| `ListImage`,`Image`|
| `getImagesByTitle()` | `$title` `$limit` | `ListImage`,`Image`|
| `getImagesByDescription()`| `$description`  `$limit`| `ListImage`,`Image`|
| `getImagesByUser()`| `$userName`  `$limit`| `ListImage`,`Image` |
| `getImagesByRangeDate()`| `$dateFromStr` (ex: 2018-04-01), `$dateToStr` (2018-04-31 or null) | `ListImage`,`Image` |
| `getImageBy()`| `$filters` `$limit`| `ListImage`,`Image` |

List of filters could be used in `getImageBy()` :

| Filter name | Comment| 
| ------------- | ------------------------------ |
| `subjects`| Used in `getImagesBySubject()` method, search by subject | 
| `user`| Used in `getImagesByUser()` method, search by username |
| `title__icontains`| Used in `getImagesByTitle()` method, search by case-insensitive, partial title |
| `description__icontains`| Used in `getImagesByDescription()` method, search by case-insensitive, partial description  |
| `__startswith`| |
| `__endswith`|  |
| `__contains`| |
| `__istartswith` | |
| `__iendswith` | |

### GetTodayImage :

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getDayImage()`| `$offset` , limit = 1| `ListToday` |
| `getTodayImage()`|| `Today` |

### GetCollection :

| Function name | Parameter| Response |
| ------------- | ------------------------------ |----------------------------- |
| `getCollectionById()`| `$id`| `Collection` |
| `getCollectionByUser()`|`$user`,`$limit`| `ListCollection` |


## Responses

### Image
| Parameter| Description |
| ------------- | ------------------------------ |
| `title`| Title of image|
| `subjects`| Keywords|
| `description`| Description|
| `url_gallery`| URL of image for gallery|
| `url_thumb`| URL of image , thumb size|
| `url_regular`| URL of image|
| `user`| Username|
| `url_histogram` | URL to histogram |
| `url_skyplot` | URL to skyplot |

![](https://image.noelshack.com/fichiers/2018/17/5/1524854105-image.png)

### ListImage
| Parameter| Description |
| ------------- | ------------------------------ |
| `listImages`      | List of images       |

![](https://image.noelshack.com/fichiers/2018/18/1/1525117490-list-images.png)

### Collection
| Parameter| Description |
| ------------- | ------------------------------ |
| `id`| Identifier|
| `name`| Name of collection|
| `description`| Description|
| `user` User name|
| `date_created`| Date of creation|
| `date_updated`| Date of modification|
| `images`| Path of WS Image|
| `listImages`| Path of WS Image|

![](https://image.noelshack.com/fichiers/2018/18/2/1525187691-collection.png)

### ListCollection
| Parameter| Description |
| ------------- | ------------------------------ |
| `listCollection`| List of collection with list of images|

![](https://image.noelshack.com/fichiers/2018/18/2/1525189056-listcollection.png)

### Today
| Parameter| Description |
| ------------- | ------------------------------ |
| `date`| Date of image       |
| `image`| URI of image|
| `resource_uri`| URI of today|
| `listImages`| List of images|

![](https://image.noelshack.com/fichiers/2018/18/1/1525117371-today.png)

## Running the tests

```
php ./vendor/bin/phpcs -p -n --standard=PSR12 src
```

Apply PHPCBF (fix and beautify PHPCS errors):
```
php ./vendor/bin/phpcbf src/path/to/file.php
```

## Authors
Stéphane Méaudre  - <balistik.fonfon@gmail.com>

## Licence

This project is licensed under the MIT License - see the LICENSE.md file for details
