import request from '@/utils/request'

export function getOperationLogList(params) {
  return request({
    url: '/log',
    method: 'get',
    params
  })
}

export function getOperationLogDetail(id) {
  return request({
    url: `/log/${id}`,
    method: 'get'
  })
}
