import request from '@/utils/request'

export function getApiKeyList(params) {
  return request({
    url: '/apikey',
    method: 'get',
    params
  })
}

export function getApiKeyDetail(id) {
  return request({
    url: `/apikey/${id}`,
    method: 'get'
  })
}

export function createApiKey(data) {
  return request({
    url: '/apikey',
    method: 'post',
    data
  })
}

export function updateApiKey(id, data) {
  return request({
    url: `/apikey/${id}`,
    method: 'put',
    data
  })
}

export function deleteApiKey(id) {
  return request({
    url: `/apikey/${id}`,
    method: 'delete'
  })
}

export function updateApiKeyStatus(id, status) {
  return request({
    url: `/apikey/toggle-status/${id}`,
    method: 'put',
    data: { status }
  })
}
