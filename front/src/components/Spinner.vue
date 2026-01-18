<template>
  <transition name="fade">
    <div
      v-if="show"
      class="spinner-overlay"
    >
      <div class="spinner-wrapper">
        <div class="spinner-border text-primary" role="status"></div>
        <span v-if="text" class="spinner-text">{{ text }}</span>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Spinner',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    text: {
      type: String,
      default: 'Carregando...'
    }
  }
}
</script>

<style scoped>
.spinner-overlay {
  position: fixed;
  inset: 0;
  background: rgba(255, 255, 255, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.spinner-wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}

.spinner-text {
  font-size: 0.9rem;
  color: #555;
}

/* animação suave */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
