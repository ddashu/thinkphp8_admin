<template>
  <div class="sidebar-logo-container" :class="{ collapse: collapse }">
    <router-link class="sidebar-logo-link" to="/">
      <svg class="sidebar-logo" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="32" height="32" rx="8" fill="#4C6EF5"/>
        <path d="M8 10h4l4 6-4 6H8l4-6-4-6zm8 0h4l4 6-4 6h-4l4-6-4-6z" fill="white" opacity="0.9"/>
      </svg>
      <transition name="fade">
        <span v-if="!collapse" class="sidebar-title">OpenClaw AI</span>
      </transition>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'SidebarLogo',
  props: {
    collapse: {
      type: Boolean,
      required: true
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.sidebar-logo-container {
  position: relative;
  width: $sidebar-width;
  height: 56px;
  line-height: 56px;
  background: rgba(255, 255, 255, 0.04);
  overflow: hidden;
  transition: width $transition-duration $transition-timing;

  &.collapse {
    width: $sidebar-collapsed-width;

    .sidebar-logo-link {
      padding-left: 0;
      justify-content: center;
    }

    .sidebar-title {
      display: none;
    }
  }
}

.sidebar-logo-link {
  display: flex;
  align-items: center;
  height: 100%;
  padding-left: 16px;
  text-decoration: none;
}

.sidebar-logo {
  width: 32px;
  height: 32px;
  flex-shrink: 0;
}

.sidebar-title {
  margin-left: 12px;
  color: #fff;
  font-size: 16px;
  font-weight: 700;
  white-space: nowrap;
  letter-spacing: 0.5px;
}

.fade-enter-active {
  transition: opacity 0.3s;
}

.fade-leave-active {
  transition: opacity 0.1s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
