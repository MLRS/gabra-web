Web site for Ġabra
------------------

Ġabra is an open lexicon for Maltese.

This repository contains the source code for the Ġabra web site at
<http://mlrs.research.um.edu.mt/resources/gabra/>

This repository begins in early 2019 with a rewritten version of the site for CakePHP 3 and PHP 7.
The previous version of the site, using CakePHP 2 and PHP 5, can be found in the [`v1` branch](https://github.com/MLRS/gabra-web/tree/v1).

## Requirements

- PHP 7
- Access to **Ġabra API** web service, either locally or live. See <https://github.com/MLRS/gabra-api>.

## Installation

After cloning this repository, you will need to:

- Copy `config/app.php.default` to `config/app.php` and customise its values
- Install plugin dependencies with `composer.phar install`

## Running

You can now either use your machine's webserver to view the default home page, or start up the built-in webserver with:

```
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.
