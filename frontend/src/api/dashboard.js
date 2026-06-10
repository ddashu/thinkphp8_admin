import request from '@/utils/request'

export function getDashboardStats() {
  return request({
    url: '/dashboard/stats',
    method: 'get'
  })
}

export function getUserTrend(params) {
  return request({
    url: '/dashboard/trend',
    method: 'get',
    params
  })
}

export function getApiStats(params) {
  return request({
    url: '/dashboard/api-stats',
    method: 'get',
    params
  })
}

export function getModelDistribution() {
  return request({
    url: '/dashboard/model-distribution',
    method: 'get'
  })
}
