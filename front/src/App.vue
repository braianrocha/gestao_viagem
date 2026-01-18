<script setup>
import { ref, provide } from 'vue'
import AppToast from '@/components/AppToast.vue'

const toastRef = ref(null)

const toast = {
  success(message, duration = 3000) {
    toastRef.value?.show(message, 'success', duration)
  },
  error(message, duration = 3000) {
    toastRef.value?.show(message, 'error', duration)
  },
  hide() {
    toastRef.value?.hide()
  }
}

provide('toast', toast)
</script>

<template>
  <RouterView />
  <AppToast ref="toastRef" />
</template>
