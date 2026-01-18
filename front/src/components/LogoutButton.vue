<template>
  <button
    class="btn btn-outline-danger d-flex align-items-center gap-2"
    :disabled="isLoading"
    @click="logout"
  >
    <span
      v-if="isLoading"
      class="spinner-border spinner-border-sm"
      role="status"
    ></span>

    <i v-else class="bi bi-box-arrow-right"></i>

    <span>{{ isLoading ? 'Saindo...' : 'Sair' }}</span>
  </button>
</template>

<script>
import { apiFetch } from '@/services/api'

export default {
  data() {
    return {
      isLoading: false
    }
  },

  methods: {
    async logout() {
      if (this.isLoading) return

      this.isLoading = true

      try {
        await apiFetch('/logout', { method: 'POST' })
      } catch {
        // mesmo se falhar, força logout local
      } finally {
        localStorage.removeItem('token')
        this.$router.push('/')
        this.isLoading = false
      }
    }
  }
}
</script>
