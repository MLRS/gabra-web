module.exports = {
  chainWebpack: config => {
    // YAML Loader
    config.module
      .rule('yaml')
      .test(/\.ya?ml$/)
      .use('json-loader')
        .loader('json-loader')
        .end()
      .use('yaml-loader')
        .loader('yaml-loader')
        .end()
  }
}
