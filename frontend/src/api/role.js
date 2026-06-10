import request from '@/utils/request'

export function getRoleList(params) {
  return request({
    url: '/role',
    method: 'get',
    params
  })
}

export function getAllRoles() {
  return request({
    url: '/role/all',
    method: 'get'
  })
}

export function getRoleDetail(id) {
  return request({
    url: `/role/${id}`,
    method: 'get'
  })
}

export function createRole(data) {
  return request({
    url: '/role',
    method: 'post',
    data
  })
}

export function updateRole(id, data) {
  return request({
    url: `/role/${id}`,
    method: 'put',
    data
  })
}

export function deleteRole(id) {
  return request({
    url: `/role/${id}`,
    method: 'delete'
  })
}

export function assignMenusToRole(id, menuIds) {
  return request({
    url: `/role/assign-menus/${id}`,
    method: 'post',
    data: { menu_ids: menuIds }
  })
}

export function updateRoleStatus(id, status) {
  return request({
    url: `/role/toggle-status/${id}`,
    method: 'put',
    data: { status }
  })
}

export function assignPermissions(data) {
  return request({
    url: `/role/assign-menus/${data.id}`,
    method: 'post',
    data: data
  })
}

export function getRolePermissions(id) {
  return request({
    url: `/role/${id}`,
    method: 'get'
  })
}
