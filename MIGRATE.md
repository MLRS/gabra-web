# Notes from migration to Ġabra Web v4 (Vue v3)

From `package.json`:

```json
  "dependencies": {
    "bootstrap": "^4.4.1",
    "core-js": "^3.6.2",
    "json-loader": "^0.5.7",
    "vue-gtag": "^1.2.1",
    "vuex-persist": "^2.2.0",
    "yaml-loader": "^0.5.0"
  },
  "devDependencies": {
    "sass": "^1.24.3",
    "sass-loader": "^8.0.0",
  }
```

Deleted (or completely overwritten) files:

- `.eslintrc.js`
- `tsconfig.json`
- `vue.config.js`

## General TODO

- Persistance of language (in state) to local storage
- GTag
- Use of process.env.NODE_ENV
