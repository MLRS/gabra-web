Web site for Ġabra
------------------

Ġabra is an open lexicon for Maltese.

This repository contains the source code for the Ġabra web site at
<http://mlrs.research.um.edu.mt/resources/gabra/>

## Requirements

- PHP
- MongoDB (see <http://mlrs.research.um.edu.mt/resources/gabra-api/download> for data dumps you can use to get started).
- **Ġabra API** web service running locally, see <https://github.com/MLRS/gabra-api>.

In addition to the data obtainable above, you will also need the following collection:

### `messages`

Example documents:

```
{
    "_id" : ObjectId("..."),
    "type" : "news"
    "key" : "",
    "eng" : "...",
    "mlt" : "...",
    "created" : ...,
    "modified" : ...
}

{
    "_id" : ObjectId("..."),
    "type" : "i18n",
    "key" : "pos.ADJ",
    "eng" : "adjective",
    "mlt" : "aġġettiv"
}

{
    "_id" : ObjectId("..."),
    "type" : "web"
    "key" : "home.title",
    "eng" : "Ġabra: an open lexicon for Maltese",
    "mlt" : "Ġabra lessikali għall-ilsien Malti"
}
```

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
