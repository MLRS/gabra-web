Web site for Ġabra
------------------

Ġabra is an open lexicon for Maltese.

This repository contains the source code for the Ġabra web site at
<http://mlrs.research.um.edu.mt/resources/gabra/>

## Requirements

- Requires PHP for web app with Mongo database.
- See <http://mlrs.research.um.edu.mt/resources/gabra-api/download> for data dumps you can use to get started.

In addition to the data above, you will also need the following collections:

### `users`

Example document:

```
{
  "_id" : ObjectId("..."),
  "password" : "...",
  "role" : "admin",
  "username" : "..."
}
```

Where `password` is an SHA-1 hash of your salted password.

### `messages`

Example documents:

```
{
    "_id" : ObjectId("..."),
    "type" : "news"
    "key" : "",
    "eng" : "...",
    "mlt" : "...",
    "created" : ISODate("..."),
    "modified" : ISODate("..."),
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
    "mlt" : "Ġabra lessikali għall-ilsien Malti",
    "modified" : ISODate("..."),
    "created" : ISODate("..."),
}
```

## Installation

- After cloning you will need to pull the submodules with `git submodule init` and `git submodule update`.
