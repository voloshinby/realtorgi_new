export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'Реалторги',
    titleTemplate: '%s - Реалторги',
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1'},
      {
        hid: 'description',
        name: 'description',
        content: process.env.npm_package_description || '',
      },
    ],
    link: [{rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'}],
  },

  loading: false,

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '~/plugins/vue-awesome-swiper/swiper.css',
    '~/assets/css/transitions.css'
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    {src: '~/plugins/vue-awesome-swiper/vue-awesome-swiper.js', mode: 'client'},
    {src: '~/plugins/vue-notification/vue-notification.js', ssr: false}
  ],

  chainWebpack: config => {
    if (process.env.NODE_ENV === 'production') {
      config.module.rule('vue').uses.delete('cache-loader');
      config.module.rule('js').uses.delete('cache-loader');
      config.module.rule('ts').uses.delete('cache-loader');
      config.module.rule('tsx').uses.delete('cache-loader');
    }
  },

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: false,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: ["@nuxtjs/axios", "@nuxtjs/proxy", "@nuxtjs/style-resources",'@nuxtjs/dotenv'],

  styleResources: {
    scss: [
      'swiper/swiper.scss',
    ]
  },

  axios: {
    proxy: true
  },

  proxy: {
    "/api/": process.env["BE_URL"] || "http://localhost:8000/"
  },


  build: {
    extend(config, ctx) {
      config.module.rules.push({
        test: /\.(ogg|mp3|wav|mpe?g)$/i,
        loader: 'file-loader',
        options: {
          name: '[path][name].[ext]'
        }
      })
    }
  },

  vue: {
    config: {
      productionTip: false,
      devtools: true
    }
  },
}
