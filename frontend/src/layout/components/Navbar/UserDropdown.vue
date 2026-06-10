<template>
  <el-dropdown class="avatar-container" trigger="click">
    <div class="avatar-wrapper">
      <div class="avatar-placeholder">
        <i class="el-icon-user-solid" />
      </div>
      <span class="username">{{ username }}</span>
      <i class="el-icon-arrow-down" />
    </div>
    <el-dropdown-menu slot="dropdown" class="user-dropdown">
      <router-link to="/profile">
        <el-dropdown-item>
          <i class="el-icon-user" /> 个人中心
        </el-dropdown-item>
      </router-link>
      <el-dropdown-item divided @click.native="logout">
        <i class="el-icon-switch-button" /> 退出登录
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'UserDropdown',
  computed: {
    ...mapGetters(['userInfo']),
    username() {
      return this.userInfo.username || '管理员'
    }
  },
  methods: {
    async logout() {
      try {
        await this.$store.dispatch('user/logout')
        this.$router.push(`/login?redirect=${this.$route.fullPath}`)
      } catch (e) {
        console.error(e)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.avatar-container {
  cursor: pointer;
}

.avatar-wrapper {
  display: flex;
  align-items: center;
  padding: 4px 8px;
  border-radius: 8px;
  transition: background-color 0.2s;

  &:hover {
    background-color: $bg-page;
  }
}

.avatar-placeholder {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, $primary-color, $primary-light);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 16px;
  flex-shrink: 0;
}

.username {
  margin-left: 8px;
  font-size: 14px;
  color: $text-primary;
  font-weight: 500;
}

.el-icon-arrow-down {
  margin-left: 4px;
  font-size: 12px;
  color: $text-secondary;
}
</style>
