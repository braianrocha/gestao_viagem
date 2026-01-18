<template>
  <div v-if="visible" class="app-toast" :class="type">
    {{ message }}
  </div>
</template>

<script setup>
import { ref } from 'vue'

const visible = ref(false)
const message = ref('')
const type = ref('error')
let timeoutId = null

function show(msg, toastType = 'error', duration = 3000) {
  if (!msg) return
  message.value = msg
  type.value = toastType
  visible.value = true

  clearTimeout(timeoutId)

  timeoutId = setTimeout(() => {
    visible.value = false
  }, duration)
}

function hide() {
  visible.value = false
  clearTimeout(timeoutId)
}

defineExpose({ show, hide })
</script>

<style scoped>
.app-toast {
  position: fixed;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  padding: 14px 20px;
  border-radius: 8px;
  color: #fff;
  font-weight: 600;
  box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
  z-index: 9999;
}

.app-toast.error {
  background: rgba(220, 38, 38, 0.85);
}

.app-toast.success {
  background: rgba(22, 163, 74, 0.85);
}
</style>
