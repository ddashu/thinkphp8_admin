<template>
  <div class="hamburger-container" @click="toggleClick">
    <svg
      :class="{ 'is-active': isActive }"
      class="hamburger"
      viewBox="0 0 1024 1024"
      xmlns="http://www.w3.org/2000/svg"
      width="20"
      height="20"
    >
      <path d="M408 442h480c8.8 0 16-7.2 16-16v-56c0-8.8-7.2-16-16-16H408c-8.8 0-16 7.2-16 16v56c0 8.8 7.2 16 16 16zm-8 204c0 8.8 7.2 16 16 16h480c8.8 0 16-7.2 16-16v-56c0-8.8-7.2-16-16-16H416c-8.8 0-16 7.2-16 16v56zm504-486H120c-8.8 0-16 7.2-16 16v56c0 8.8 7.2 16 16 16h784c8.8 0 16-7.2 16-16v-56c0-8.8-7.2-16-16-16zm0 632H120c-8.8 0-16 7.2-16 16v56c0 8.8 7.2 16 16 16h784c8.8 0 16-7.2 16-16v-56c0-8.8-7.2-16-16-16zM142.4 545.6L256 659.2c12.8 12.8 32 12.8 44.8 0l113.6-113.6c12.8-12.8 3.2-35.2-12.8-35.2H155.2c-16 0-25.6 22.4-12.8 35.2z" fill="currentColor" />
    </svg>
  </div>
</template>

<script>
export default {
  name: 'Hamburger',
  computed: {
    isActive() {
      return this.$store.getters.sidebar.opened
    }
  },
  methods: {
    toggleClick() {
      this.$store.dispatch('app/toggleSidebar')
    }
  }
}
</script>

<style lang="scss" scoped>
@import '@/styles/variables.scss';

.hamburger-container {
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  transition: background-color 0.2s;

  &:hover {
    background-color: $bg-page;
  }
}

.hamburger {
  display: inline-block;
  vertical-align: middle;
  color: $text-regular;
  transition: transform 0.3s;

  &.is-active {
    transform: rotate(180deg);
  }
}
</style>
