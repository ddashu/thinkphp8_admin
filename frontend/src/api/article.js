import request from '@/utils/request'

export function getArticleList(params) {
  return request({ url: '/article', method: 'get', params })
}

export function getArticleDetail(id) {
  return request({ url: `/article/${id}`, method: 'get' })
}

export function createArticle(data) {
  return request({ url: '/article', method: 'post', data })
}

export function updateArticle(id, data) {
  return request({ url: `/article/${id}`, method: 'put', data })
}

export function deleteArticle(id) {
  return request({ url: `/article/${id}`, method: 'delete' })
}

export function toggleArticleStatus(id, status) {
  return request({ url: `/article/toggle-status/${id}`, method: 'put', data: { status } })
}

export function toggleArticleTop(id) {
  return request({ url: `/article/toggle-top/${id}`, method: 'put' })
}

export function toggleArticleRecommend(id) {
  return request({ url: `/article/toggle-recommend/${id}`, method: 'put' })
}

export function getCategoryTree() {
  return request({ url: '/article/category/tree', method: 'get' })
}

export function getAllCategories() {
  return request({ url: '/article/category/all', method: 'get' })
}

export function createCategory(data) {
  return request({ url: '/article/category', method: 'post', data })
}

export function updateCategory(id, data) {
  return request({ url: `/article/category/${id}`, method: 'put', data })
}

export function deleteCategory(id) {
  return request({ url: `/article/category/${id}`, method: 'delete' })
}
