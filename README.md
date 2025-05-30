# Ġabra Web Frontend, v4

Ġabra is an open lexicon for Maltese.

This repository contains the source code for the frontend Ġabra web site at
<http://mlrs.research.um.edu.mt/resources/gabra/>

For the backend code, see [gabra-api](https://github.com/MLRS/gabra-api).

Previous versions of the Ġabra site are available in these branches:

- [`v1`](https://github.com/MLRS/gabra-web/tree/v1) (CakePHP 2 and PHP 5)
- [`v2`](https://github.com/MLRS/gabra-web/tree/v2) (CakePHP 3 and PHP 7)
- [`v3`](https://github.com/MLRS/gabra-web/tree/v3) (Vue.js v2)

## Requirements

- Node.js
- Access to **Ġabra API** web service, either locally or live. See <https://github.com/MLRS/gabra-api>.

The path to the API is specified in the `.env*` files.
For documentation about these, see [Vue.js docs on environment variables](https://cli.vuejs.org/guide/mode-and-env.html#environment-variables).

## Project setup

Install all depedencies needed for building app.

```sh
npm install
```

### Compile with hot-reload for development

```sh
npm run serve
```

Then open  `http://localhost:8080` in a browser.

### Compile and minifies for production

```sh
npm run build
```

The contents of `dist` can then be served statically.

### Lint and fix files

```sh
npm run lint
```
