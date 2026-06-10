import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

// ElementUI
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/zh-CN'

// Global styles
import './styles/reset.scss'
import './styles/global.scss'
import './styles/transition.scss'
import './styles/sidebar.scss'

// Directives
import permission from './directives/permission'

Vue.use(ElementUI, { size: 'small', locale })

Vue.directive('permission', permission)

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
