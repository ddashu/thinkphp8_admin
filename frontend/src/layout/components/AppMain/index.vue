<template>
  <section class="app-main">
    <transition name="fade-transform" mode="out-in">
      <keep-alive :include="cachedViews">
        <router-view :key="key" />
      </keep-alive>
    </transition>
  </section>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'AppMain',
  computed: {
    ...mapState({
      cachedViews: state => state.tagsView.cachedViews
    }),
    key() {
      return this.$route.path
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.app-main {
  flex: 1;
  overflow: auto;
  background: $bg-page;
  padding: 16px;
}
</style>
