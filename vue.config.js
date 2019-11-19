module.exports = {
  chainWebpack: config => {
    // YAML Loader
    config.module
      .rule('yaml')
      .test(/\.ya?ml$/)
      .use('yaml-loader')
      .loader('yaml-loader')
      .end()
  }
}
