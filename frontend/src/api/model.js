import request from '@/utils/request'

export function getModelList(params) {
  return request({
    url: '/model',
    method: 'get',
    params
  })
}

export function getModelDetail(id) {
  return request({
    url: `/model/${id}`,
    method: 'get'
  })
}

export function createModel(data) {
  return request({
    url: '/model',
    method: 'post',
    data
  })
}

export function updateModel(id, data) {
  return request({
    url: `/model/${id}`,
    method: 'put',
    data
  })
}

export function deleteModel(id) {
  return request({
    url: `/model/${id}`,
    method: 'delete'
  })
}

export function updateModelStatus(id, status) {
  return request({
    url: `/model/toggle-status/${id}`,
    method: 'put',
    data: { status }
  })
}
