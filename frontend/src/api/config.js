import request from '@/utils/request'

export function getConfigList(params) {
  return request({
    url: '/config',
    method: 'get',
    params
  })
}

export function getConfigByGroup(group) {
  return request({
    url: '/config',
    method: 'get',
    params: { group }
  })
}

export function batchUpdateConfig(data) {
  return request({
    url: '/config/batch-update',
    method: 'post',
    data
  })
}
