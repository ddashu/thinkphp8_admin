<template>
  <div :class="classObj" class="app-wrapper">
    <div v-if="device === 'mobile' && sidebar.opened" class="drawer-bg" @click="handleClickOutside" />
    <sidebar class="sidebar-container" />
    <div class="main-container" :class="{ 'has-tags-view': showTagsView }">
      <div :class="{ 'fixed-header': fixedHeader }">
        <navbar />
        <tags-view v-if="showTagsView" />
      </div>
      <app-main />
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import Sidebar from './components/Sidebar/index.vue'
import Navbar from './components/Navbar/index.vue'
import TagsView from './components/TagsView/index.vue'
import AppMain from './components/AppMain/index.vue'

export default {
  name: 'Layout',
  components: {
    Sidebar,
    Navbar,
    TagsView,
    AppMain
  },
  computed: {
    ...mapState({
      sidebar: state => state.app.sidebar,
      device: state => state.app.device,
      showTagsView: state => state.settings.showTagsView,
      fixedHeader: state => state.settings.fixedHeader
    }),
    classObj() {
      return {
        hideSidebar: !this.sidebar.opened,
        openSidebar: this.sidebar.opened,
        mobile: this.device === 'mobile'
      }
    }
  },
  methods: {
    handleClickOutside() {
      this.$store.dispatch('app/closeSidebar', { withoutAnimation: false })
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.app-wrapper {
  position: relative;
  height: 100%;
  width: 100%;
  display: flex;

  &.mobile {
    .sidebar-container {
      transition: transform 0.3s;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1001;
    }

    &.hideSidebar {
      .sidebar-container {
        transform: translateX(-$sidebar-width);
      }
    }
  }
}

.drawer-bg {
  background: #000;
  opacity: 0.3;
  width: 100%;
  top: 0;
  height: 100%;
  position: absolute;
  z-index: 1000;
}

.main-container {
  min-height: 100%;
  transition: margin-left $transition-duration;
  margin-left: $sidebar-width;
  position: relative;
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.hideSidebar .main-container {
  margin-left: $sidebar-collapsed-width;
}

.fixed-header {
  position: sticky;
  top: 0;
  z-index: 9;
  width: 100%;
  transition: width $transition-duration;
}
</style>
