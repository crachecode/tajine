Tajine
==========

Tajine is a thumbnail generator suitable for any web project following an Apache / PHP approach. It is based on the [Intervention Image](https://github.com/Intervention/image) library.

## Features

Tajine allows you to resize images at any dimensions, in a flexible way, and cache the generated image.
This image can be called directly at a specific size by passing parameters in the URL. This URL can be used in a HTML file as the ```src``` parameter of an ```img``` tag e.g.

## Requirements

Tajine requires PHP 5.6 or higher. Its cache functionality can make use of Apache mod_rewrite, it also allows simpler image URLs, but Apache should not be mandatory. Tajine has not been tested with any other HTTP server though.

## Installation

 1. Clone the repository using Git :  
 `git clone https://github.com/crachecode/tajine.git`
 
 2. Enter the new directory :  
 `cd tajine`

 3. Install dependencies using Composer :  
 `composer update`

 4. Allow writing on the cache directory :  
 `chmod 777 public/img/cache`

 5. Define either the ```tajine``` or ```tajine/public``` directory as your VirtualHost root.

## Using Prestajine

Original Images should be placed in ```tajine/public/img/originals``` directory.

Image at any dimension can then be accessed following one of these syntaxes :

with apache and mod_rewrite :  
```name```.```width```x```height```.```method```.```quality```.```upsize```.```extension```  
e.g. :  
`image.1280x1024.basic.90.false.jpg`  
`image.x1024.jpg`

without rewriting module :  
```name```.```extension```?w=```width```&h=```height```&m=```method```&q=```quality```&u=```upsize```  
e.g. :  
`image.jpg?w=1280&h=1024&m=basic&q=90&u=false`  
`image.jpg?h=1024`

### Parameters

| name            | value type                          | description                                                                   | default       |
| ---             | ---                                 | ---                                                                           | ---           |
| ```name```      | string                              | filename as accessible in ```tajine/public/img/originals``` without extension | n/a, required |
| ```extension ```| string                              | extension of filename as accessible in ```tajine/public/img/originals```      | n/a, required |
| ```width```     | integer                             | thumbnail width (in pixel)                                                    | n/a           |
| ```height```    | integer                             | thumbnail height (in pixel)                                                   | n/a           |
| ```method```    | ```basic```, ```fit``` or ```max``` | resizing behaviour, see next paragraph                                        | ```fit```     |
| ```quality```   | integer, ```0``` to ```100```       | thumbnail quality, bigger is better but files are heavier                     | ```85```      |
| ```upsize```    | boolean                             | whether or not small images should be enlarged with larger thumbnail size     | ```true```    |

**Method** can be set to :
* `basic` : image will be resized to the exact dimension, without keeping aspect ratio.
* `fit` : image will be resized to fit in specified width and / or height, keeping aspect ratio.  
If only one dimension is specified, unspecified dimension (width or height) will be adjusted depending on the other dimension.  
If both are specified, image will be cropped if necessary.
* `max` : image will be resized to fit in specified width and / or height, keeping aspect ratio, without cropping.

### Notes

Generated thumbnails are saved as image files in ```tajine/public/img/cache``` directory.  
When using mod_rewrite these files names are the same string as the URL provided for images generation. Therefore Apache doesn't even need to process PHP to display the cached version.  
They can safely be deleted to process the generation again.