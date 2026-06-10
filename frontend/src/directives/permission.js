import store from '@/store'

export default {
  inserted(el, binding) {
    const { value } = binding
    const permissions = store.getters && store.getters.permissions

    if (value && value instanceof Array && value.length > 0) {
      const hasPermission = permissions.some(permission => {
        return value.includes(permission)
      })

      if (!hasPermission) {
        el.parentNode && el.parentNode.removeChild(el)
      }
    } else if (value) {
      throw new Error('需要权限！例如 v-permission="[\'system:user:create\']"')
    }
  }
}
