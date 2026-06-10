import request from '@/utils/request'

export function getPaymentConfig() {
  return request({ url: '/payment/config', method: 'get' })
}

export function savePaymentConfig(data) {
  return request({ url: '/payment/config/save', method: 'put', data })
}

export function testPayment(data) {
  return request({ url: '/payment/test', method: 'post', data })
}

export function getAlipayConfig() {
  return request({ url: '/alipay/config', method: 'get' })
}

export function saveAlipayConfig(data) {
  return request({ url: '/alipay/config', method: 'put', data })
}

export function testAlipay() {
  return request({ url: '/alipay/test', method: 'post' })
}
