import request from '@/utils/request'

export function getFileList(params) {
  return request({
    url: '/file',
    method: 'get',
    params
  })
}

export function uploadFile(data) {
  return request({
    url: '/file/upload',
    method: 'post',
    data,
    headers: { 'Content-Type': 'multipart/form-data' }
  })
}

export function deleteFile(id) {
  return request({
    url: `/file/${id}`,
    method: 'delete'
  })
}
