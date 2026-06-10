import request from '@/utils/request'

export function getProfile() {
  return request({
    url: '/profile',
    method: 'get'
  })
}

export function updateProfile(data) {
  return request({
    url: '/profile',
    method: 'put',
    data
  })
}

export function updatePassword(data) {
  return request({
    url: '/profile/change-password',
    method: 'post',
    data
  })
}

export function uploadAvatar(data) {
  return request({
    url: '/profile/upload-avatar',
    method: 'post',
    data,
    headers: { 'Content-Type': 'multipart/form-data' }
  })
}
