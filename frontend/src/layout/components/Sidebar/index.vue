<template>
  <div :class="{ 'has-logo': showLogo }" class="sidebar-container">
    <logo v-if="showLogo" :collapse="isCollapse" />
    <el-scrollbar wrap-class="scrollbar-wrapper">
      <el-menu
        :default-active="activeMenu"
        :collapse="isCollapse"
        :collapse-transition="false"
        mode="vertical"
        background-color="#1A1A2E"
        text-color="rgba(255,255,255,0.65)"
        active-text-color="#FFFFFF"
      >
        <sidebar-item
          v-for="route in routes"
          :key="route.path"
          :item="route"
          :base-path="route.path"
        />
      </el-menu>
    </el-scrollbar>
    <div class="sidebar-footer">
      <div class="footer-company">量子软件工作室</div>
      <div class="footer-info">微信：yework2016</div>
      <div class="footer-info">
        官网：<a href="http://www.dglzsoft.com" target="_blank" rel="noopener">www.dglzsoft.com</a>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapState } from 'vuex'
import Logo from './Logo.vue'
import SidebarItem from './SidebarItem.vue'

export default {
  name: 'Sidebar',
  components: { Logo, SidebarItem },
  computed: {
    ...mapGetters(['sidebar']),
    ...mapState('settings', ['showSidebarLogo']),
    routes() {
      return this.$router.options.routes
    },
    activeMenu() {
      const route = this.$route
      const { meta, path } = route
      if (meta.activeMenu) {
        return meta.activeMenu
      }
      return path
    },
    showLogo() {
      return this.showSidebarLogo
    },
    isCollapse() {
      return !this.sidebar.opened
    }
  }
}
</script>

<style lang="scss">
@import '@/styles/sidebar.scss';

.sidebar-container {
  display: flex;
  flex-direction: column;
}

.sidebar-container .el-scrollbar {
  flex: 1;
}

.sidebar-footer {
  padding: 16px 20px;
  text-align: center;
  border-top: 1px solid rgba(255, 255, 255, 0.06);
  background-color: #1A1A2E;
  flex-shrink: 0;

  .footer-company {
    font-size: 13px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 6px;
  }

  .footer-info {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.4);
    line-height: 1.8;
    word-break: break-all;

    a {
      color: rgba(255, 255, 255, 0.55);
      text-decoration: none;
      transition: color 0.2s;

      &:hover {
        color: #748FFC;
        text-decoration: underline;
      }
    }
  }
}
</style>
