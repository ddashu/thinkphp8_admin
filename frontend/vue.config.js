module.exports = {
  publicPath: './',
  outputDir: '../public/dist',
  assetsDir: 'static',
  devServer: {
    port: 8080,
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true
      }
    }
  }
}
