import axios from 'axios'
import { Message } from 'element-ui'
import { getToken, removeToken } from './auth'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

NProgress.configure({ showSpinner: false })

const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API,
  timeout: 30000
})

// Request interceptor
service.interceptors.request.use(
  config => {
    NProgress.start()
    const token = getToken()
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + token
    }
    return config
  },
  error => {
    NProgress.done()
    console.error('Request error:', error)
    return Promise.reject(error)
  }
)

// Response interceptor
service.interceptors.response.use(
  response => {
    NProgress.done()
    const res = response.data
    if (res.code && res.code !== 200) {
      Message({
        message: res.message || '请求失败',
        type: 'error',
        duration: 3000
      })
      if (res.code === 401) {
        removeToken()
        window.location.href = '/#/login'
      }
      return Promise.reject(new Error(res.message || '请求失败'))
    }
    return res
  },
  error => {
    NProgress.done()
    if (error.response) {
      const status = error.response.status
      const messages = {
        401: '登录已过期，请重新登录',
        403: '拒绝访问',
        404: '资源不存在',
        500: '服务器错误'
      }
      const message = messages[status] || '请求失败'
      Message({
        message: message,
        type: 'error',
        duration: 3000
      })
      if (status === 401) {
        removeToken()
        window.location.href = '/#/login'
      }
    } else {
      Message({
        message: '网络错误',
        type: 'error',
        duration: 3000
      })
    }
    return Promise.reject(error)
  }
)

export default service
