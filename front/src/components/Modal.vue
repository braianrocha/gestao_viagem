<script setup>
import { watch, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  title: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

function close() {
  emit('update:modelValue', false)
}

function handleEsc(e) {
  if (e.key === 'Escape') close()
}

onMounted(() => {
  document.addEventListener('keydown', handleEsc)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleEsc)
})
</script>

<template>
  <Teleport to="body">
    <div v-if="modelValue" class="modal-backdrop-custom">
      <div class="modal-custom">
        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title">{{ title }}</h5>
          <button class="btn-close" @click="close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <slot />
        </div>

        <!-- Footer -->
        <div class="modal-footer">
          <slot name="footer">
            <button class="btn btn-secondary" @click="close">
              Fechar
            </button>
          </slot>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<style scoped>
.modal-backdrop-custom {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 9998;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-custom {
  background: #fff;
  border-radius: 12px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 10px 30px rgba(0,0,0,.2);
  animation: fadeIn 0.2s ease;
}

.modal-header,
.modal-footer {
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #eee;
}

.modal-footer {
  border-top: 1px solid #eee;
  border-bottom: none;
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}

.modal-body {
  padding: 1.25rem;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
