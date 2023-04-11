![Build Status](https://github.com/HamHamFonFon/Astrobin-WebServices/actions/workflows/php.yml/badge.svg)
[![codecov.io](https://codecov.io/gh/HamHamFonFon/Astrobin-WebServices/branch/master/graphs/badge.svg?branch=master)](https://codecov.io/gh/HamHamFonFon/Astrobin-WebServices/branch/master/graphs/badge.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/hamhamfonfon/astrobin-ws/v)](//packagist.org/packages/hamhamfonfon/astrobin-ws)
[![Total Downloads](https://poser.pugx.org/hamhamfonfon/astrobin-ws/downloads)](https://packagist.org/packages/hamhamfonfon/astrobin-ws)
[![License](https://poser.pugx.org/hamhamfonfon/astrobin-ws/license)](https://packagist.org/packages/hamhamfonfon/astrobin-ws)
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
   * [GetUser](#getuser)
 * [Responses](#responses)
   * [Image](#image)
   * [ListImage](#listimage)
   * [Collection](#collection)
   * [ListCollection](#listcollection)
   * [Today](#today)
   * [User](#user)
 * [Running the tests](#running-the-tests)
 * [Contributes](#contributes)
 * [Bugs and issues](#bugs-and-issues)
 * [Authors](#authors)
 * [Licence](#licence)

Version 2.5.0

## Requirements
* PHP 8.1 min or superior (oldest versions are no longer supported)
* API Key and API Secret from [Astrobin](https://www.astrobin.com/api/request-key/)

## Introduction

Astrobin's WebServices is a PHP library for request Astrobin's API Rest and get amazing astrophotographies hosted on [Astrobin](http://www.astrobin.com).
Please read API section in ["Terms of service"](https://welcome.astrobin.com/terms-of-service)

## Installing

You can install this package in 2 different ways.

* Basic installation; just install package from composer :

> `composer require hamhamfonfon/astrobin-ws`

Update to the newest version :
> `composer update hamhamfonfon/astrobin-ws`

* If you just want to make some issues, make some simple tests etc, juste clone the repository

> `git clone git@github.com:HamHamFonFon/Astrobin-Webservices.git`

If you're using old PHP versions:
- PHP 7.4 | 8.0
> `composer require hamhamfonfon/astrobin-ws:2.4`
- PHP 7.3
> `composer require hamhamfonfon/astrobin-ws:2.3`

Caution, these versions are not maintained anymore. Only 2.5.* will be maintained and will have new features.

### Usage

First, set your keys in .env file :
```yml
ASTROBIN_API_KEY=PutHereYourOwnApiKey
ASTROBIN_API_SECRET=PutHereYourOwnApiSecret
```

Example without framework:
```php 
# Get variables
$astrobinApiKey = getenv('ASTROBIN_API_KEY');
$astrobinApiSecret = getenv('ASTROBIN_API_SECRET');

# Get data from Astrobin
$imageWs = new GetImage($astrobinApiKey, $astrobinApiSecret);
$astrobinImage = $imageWs->getById('astrobinImageId');
```

Example with Symfony:
```yml
parameters:
   astrobinApiKey: '%env(ASTROBIN_API_KEY)%'
   astrobinApiSecret: '%env(ASTROBIN_API_SECRET)%'
   
   # default configuration for services in *this* file
   _defaults:
      autowire: true      # Automatically injects dependencies in your services.
      autoconfigure: true # Automatically registers your services as commands, event subscribers, etc.
      bind:
         $astrobinApiKey: '%astrobinApiKey%'
         $astrobinApiSecret: '%astrobinApiSecret%'    
```

```php
use AstrobinWs\Response\DTO\AstrobinResponse;use AstrobinWs\Services\GetImage;

final class MyImageService
{
    private GetImage $astrobinImage;

    /**
     * MyImageService constructor.
     * @param string|null $astrobinApiKey
     * @param string|null $astrobinApiSecret
    */
    public function __construct(?string $astrobinApiKey, ?string $astrobinApiSecret)
    {
        $this->astrobinImage = new GetImage($astrobinApiKey, $astrobinApiSecret);
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
        $imagesBySiovene = $this->astrobinImage->getImagesByUser('siovene', 10);
        
        return $imagesBySiovene;
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
| `getById()`| `$id` | `Image` |
| `getImageById()`| `$id` | `Image` |
| `getImagesBySubject()`| `$subjectId`  `$limit`| `ListImage`,`Image`|
| `getImagesByTitle()` | `$title` `$limit` | `ListImage`,`Image`|
| `getImagesByDescription()`| `$description`  `$limit`| `ListImage`,`Image`|
| `getImagesByUser()`| `$userName`  `$limit`| `ListImage`,`Image` |
| `getImagesByRangeDate()`| `$dateFromStr` (ex: 2018-04-01), `$dateToStr` (2018-04-31 or null) | `ListImage`,`Image` |
| `getImageBy()`| `$filters` `$limit`| `ListImage`,`Image` |
`getImageById()` is an alias of `getById()` for version 1.0.0. retro-compatibility.

List of filters that can be used in `getImageBy()` :

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
| `getById()`| `$id` | `Collection` |
| `getCollectionById()`| `$id`| `Collection` |
| `getCollectionByUser()`|`$user`,`$limit`| `ListCollection` |

Parameter `$limit` is mandatory and must be an integer.
`getCollectionById()` is an alias of `getById()` for retro-compatibility of version 1.0.0.
### GetUser

| Function name    | Parameter   | Response |
|------------------|-------------|----------------------------- |
| `getById()`      | `$id`       | `User` |
| `getByUername()` | `$username` | `User` |
/!\ For all webservices, parameter `$id` must be a string and not an integer or float.

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

### ListImage
| Parameter| Description |
| ------------- | ------------------------------ |
| `listImages`      | List of images       |


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

### ListCollection
| Parameter| Description |
| ------------- | ------------------------------ |
| `listCollection`| List of collection with list of images|

### Today
| Parameter| Description                                |
| ------------- |--------------------------------------------|
| `date`| Date of image (Y-m-d format)               |
| `image`| URI of selected image                      |
| `resource_uri`| URI of today image                         |
| `listImages`| List of images (instances of Image::class) |

### User
| Parameter     | Description |
|---------------| ------------------------------ |
| `id`          | |
| `username`    | |
| `avatar`      | |
| `about`       | |
| `image_count` | |
| `job`         | |
| `hobbies`     | |
| `language`    | |
| `website`     | |

## Contributes
I accept contributions, please fork the project and submit pull requests.

## Bugs and issues
In case you find some bugs or have question about Astrobin-WebServices, open an issue and I will answer you as soon as possible.

### Install package for debugging
#### Retrieve code-source
Clone repository from GitHub
```bash
git clone git@github.com:HamHamFonFon/Astrobin-WebServices.git
```

#### Run docker
Build, compile and up docker container
```bash 
docker-compose build --no-cache
docker-compose up -d
docker exec -ti php_astrobin_ws bash
```

#### Installation
Install dependencies
```bash
composer install
```

#### Run Rector
```bash
# Init
./vendor/bin/rector init

# Run
./vendor/bin/rector process src --dry-run
```
#### Run PHP CodeSnifer
```
php ./vendor/bin/phpcs -p -n --standard=PSR12 src
```

Apply PHPCBF (fix and beautify PHPCS errors):
```
php ./vendor/bin/phpcbf src/path/to/file.php
```

## Authors
Stéphane Méaudre  - <balistik.fonfon@gmail.com> - 2023

## Licence
This project is licensed under the MIT License - see the LICENSE.md file for details