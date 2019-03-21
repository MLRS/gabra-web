Web site for Ġabra
------------------

Ġabra is an open lexicon for Maltese.

This repository contains the source code for the Ġabra web site at
<http://mlrs.research.um.edu.mt/resources/gabra/>

## Requirements

- PHP 7
- MongoDB (see <http://mlrs.research.um.edu.mt/resources/gabra-api/download> for data dumps you can use to get started).
  _This dependency will soon be removed._
- **Ġabra API** web service running locally, see <https://github.com/MLRS/gabra-api>.

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
