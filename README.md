# SEOMagic
How do you get SEO working when you get tricked in to making an SPA? Magic.

## Installation

```
$ composer require roberthucks\seo-magic
$ npm install @nesk/puphpeteer
```

## Usage

```php
<?php

include 'vendor/autoload.php';

use roberthucks\SEOMagic\SEOMagic;

$magic = new SEOMagic();
```

### fetchPage(string $uri, bool $fresh = false)
`fetchPage()` will return a `PageResponse` object containing the HTML content of the requested URI.
Pages are cached for 24 hours by default, but this is customisable through the variable `redis_cache_default_ttl`.
Setting `$fresh` to true will force a fresh copy of the page. Beware though, this isn't the fastest thing in the world.

```php
$magic = new SEOMagic();
$magic->fetchPage('https://example.com');
```

### cachePage(string $uri)
`cachePage()` will store the requested URI in the cache. This is useful for preemptively storing the page in the cache to speed up results of legitimate page requests.

```php
$magic = new SEOMagic();
$magic->cachePage('https://example.com');
```

## Response Object
This is the object that will be returned from the `fetchPage()` function and is used internally to store the page response.

```php
public $raw_html;
public $render_time;
protected $from_cache;
```

### $raw_html
This is a string that contains the raw HTML content of the page (without scripts or style tags). This is the bread and butter of the response.

```php
$magic = new SEOMagic();
$magic->fetchPage('https://example.com');
$magic->raw_html; //<h1>Hello</h1>
```

### $render_time
This is a float that represents the time it took to render the page. This is mainly for performance metrics.

```php
$magic = new SEOMagic();
$magic->fetchPage('https://example.com');
$magic->render_time; //1.56423
```

### $from_cache
This is a boolean that states whether or not the page is being fetched from the cache or not. This can be used to check whether your caching system is working correctly and how many hits/misses you are getting.

```php
$magic = new SEOMagic();
$magic->fetchPage('https://example.com');
$magic->isFromCache(); //true
```
