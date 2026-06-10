import request from '@/utils/request'

export function getUserList(params) {
  return request({
    url: '/user',
    method: 'get',
    params
  })
}

export function getUserDetail(id) {
  return request({
    url: `/user/${id}`,
    method: 'get'
  })
}

export function createUser(data) {
  return request({
    url: '/user',
    method: 'post',
    data
  })
}

export function updateUser(id, data) {
  return request({
    url: `/user/${id}`,
    method: 'put',
    data
  })
}

export function deleteUser(id) {
  return request({
    url: `/user/${id}`,
    method: 'delete'
  })
}

export function toggleUserStatus(id, status) {
  return request({
    url: `/user/toggle-status/${id}`,
    method: 'put',
    data: { status }
  })
}

export function updateUserStatus(id, status) {
  return toggleUserStatus(id, status)
}

export function resetUserPassword(id, password) {
  return request({
    url: `/user/reset-password/${id}`,
    method: 'post',
    data: { password }
  })
}
