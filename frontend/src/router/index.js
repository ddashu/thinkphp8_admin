import Vue from 'vue'
import VueRouter from 'vue-router'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import Layout from '@/layout/index.vue'
import { getToken } from '@/utils/auth'

Vue.use(VueRouter)

export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path(.*)',
        component: () => import('@/views/redirect/index.vue')
      }
    ]
  },
  {
    path: '/login',
    component: () => import('@/views/login/index.vue'),
    hidden: true,
    meta: { title: '登录' }
  },
  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [
      {
        path: 'dashboard',
        name: 'Dashboard',
        component: () => import('@/views/dashboard/index.vue'),
        meta: { title: '数据看板', icon: 'el-icon-data-board', affix: true }
      }
    ]
  },
  {
    path: '/system',
    component: Layout,
    redirect: '/system/user',
    name: 'System',
    meta: { title: '系统管理', icon: 'el-icon-setting' },
    children: [
      {
        path: 'user',
        name: 'User',
        component: () => import('@/views/system/user/index.vue'),
        meta: { title: '用户管理', icon: 'el-icon-user' }
      },
      {
        path: 'role',
        name: 'Role',
        component: () => import('@/views/system/role/index.vue'),
        meta: { title: '角色管理', icon: 'el-icon-s-custom' }
      },
      {
        path: 'menu',
        name: 'Menu',
        component: () => import('@/views/system/menu/index.vue'),
        meta: { title: '菜单管理', icon: 'el-icon-menu' }
      },
      {
        path: 'config',
        name: 'Config',
        component: () => import('@/views/system/config/index.vue'),
        meta: { title: '系统配置', icon: 'el-icon-s-tools' }
      }
    ]
  },
  {
    path: '/ai',
    component: Layout,
    redirect: '/ai/apikey',
    name: 'AI',
    meta: { title: 'AI管理', icon: 'el-icon-cpu' },
    children: [
      {
        path: 'apikey',
        name: 'ApiKey',
        component: () => import('@/views/ai/apikey/index.vue'),
        meta: { title: 'API密钥', icon: 'el-icon-key' }
      },
      {
        path: 'model',
        name: 'Model',
        component: () => import('@/views/ai/model/index.vue'),
        meta: { title: 'AI模型', icon: 'el-icon-connection' }
      }
    ]
  },
  {
    path: '/content',
    component: Layout,
    redirect: '/content/article',
    name: 'Content',
    meta: { title: '内容管理', icon: 'el-icon-document' },
    children: [
      {
        path: 'article',
        name: 'ContentArticle',
        component: () => import('@/views/content/article/index.vue'),
        meta: { title: '文章管理', icon: 'el-icon-document' }
      },
      {
        path: 'payment',
        name: 'ContentPayment',
        component: () => import('@/views/content/payment/index.vue'),
        meta: { title: '支付配置', icon: 'el-icon-money' }
      },
      {
        path: 'sms',
        name: 'ContentSms',
        component: () => import('@/views/content/sms/index.vue'),
        meta: { title: '短信配置', icon: 'el-icon-chat-dot-round' }
      }
    ]
  },
  {
    path: '/log',
    component: Layout,
    redirect: '/log/operation',
    name: 'Log',
    meta: { title: '日志管理', icon: 'el-icon-document' },
    children: [
      {
        path: 'operation',
        name: 'OperationLog',
        component: () => import('@/views/log/operation/index.vue'),
        meta: { title: '操作日志', icon: 'el-icon-tickets' }
      }
    ]
  },
  {
    path: '/file',
    component: Layout,
    children: [
      {
        path: '',
        name: 'File',
        component: () => import('@/views/file/index.vue'),
        meta: { title: '文件管理', icon: 'el-icon-folder' }
      }
    ]
  },
  {
    path: '/profile',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '',
        name: 'Profile',
        component: () => import('@/views/profile/index.vue'),
        meta: { title: '个人中心', icon: 'el-icon-user' }
      }
    ]
  }
]

const createRouter = () => new VueRouter({
  mode: 'hash',
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

const whiteList = ['/login']

router.beforeEach(async (to, from, next) => {
  NProgress.start()
  const hasToken = getToken()

  if (hasToken) {
    if (to.path === '/login') {
      next({ path: '/' })
    } else {
      next()
    }
  } else {
    if (whiteList.indexOf(to.path) !== -1) {
      next()
    } else {
      next(`/login?redirect=${to.path}`)
    }
  }
})

router.afterEach(() => {
  NProgress.done()
})

export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher
}

export default router
