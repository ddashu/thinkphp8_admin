import request from '@/utils/request'

export function getSmsConfigList(params) {
  return request({ url: '/sms/config', method: 'get', params })
}

export function getSmsConfigDetail(id) {
  return request({ url: `/sms/config/${id}`, method: 'get' })
}

export function createSmsConfig(data) {
  return request({ url: '/sms/config', method: 'post', data })
}

export function updateSmsConfig(id, data) {
  return request({ url: `/sms/config/${id}`, method: 'put', data })
}

export function deleteSmsConfig(id) {
  return request({ url: `/sms/config/${id}`, method: 'delete' })
}

export function updateSmsStatus(id, status) {
  return request({ url: `/sms/config/toggle-status/${id}`, method: 'put', data: { status } })
}

export function setDefaultSms(id) {
  return request({ url: `/sms/config/set-default/${id}`, method: 'put' })
}
