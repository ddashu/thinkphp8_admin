<template>
  <div class="navbar">
    <hamburger class="hamburger-container" />
    <breadcrumb class="breadcrumb-container" />
    <div class="right-menu">
      <el-tooltip content="全屏" placement="bottom" effect="dark">
        <div class="right-menu-item hover-effect" @click="toggleFullscreen">
          <i class="el-icon-full-screen" />
        </div>
      </el-tooltip>
      <user-dropdown class="avatar-container" />
    </div>
  </div>
</template>

<script>
import Hamburger from './Hamburger.vue'
import Breadcrumb from './Breadcrumb.vue'
import UserDropdown from './UserDropdown.vue'

export default {
  name: 'Navbar',
  components: {
    Hamburger,
    Breadcrumb,
    UserDropdown
  },
  methods: {
    toggleFullscreen() {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen()
      } else {
        document.exitFullscreen()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.navbar {
  height: $navbar-height;
  overflow: hidden;
  position: relative;
  background: $bg-navbar;
  box-shadow: 0 1px 4px rgba(0, 21, 41, 0.08);
  display: flex;
  align-items: center;
  padding: 0 16px;
}

.hamburger-container {
  cursor: pointer;
}

.breadcrumb-container {
  margin-left: 8px;
  flex: 1;
}

.right-menu {
  display: flex;
  align-items: center;

  .right-menu-item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    color: $text-regular;
    transition: background-color 0.2s;

    &:hover {
      background-color: $bg-page;
    }
  }
}
</style>
