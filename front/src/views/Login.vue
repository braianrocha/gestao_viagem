<template>
  <Spinner :show="isLoading" />

  <div class="login-page d-flex justify-content-center align-items-center h-100">
    <div class="col-11 col-sm-8 col-md-6 col-lg-4">
      <div class="surface-card">
        <div class="card-body p-5">
          <div class="text-center mb-5">
            <div class="login-icon mb-3">
              <i class="bi bi-airplane-fill"></i>
            </div>
            <h1 class="fw-bold mb-1 login-title">Pedidos de Viagens</h1>
            <small class="text-muted">Acesse sua conta para gerenciar seus pedidos</small>
          </div>

          <form @submit.prevent="login">
            <div class="mb-4">
              <label class="form-label text-muted small fw-bold text-uppercase">E-mail</label>
              <input v-model="email" type="email" class="form-control form-control-lg bg-light border-0"
                placeholder="nome@exemplo.com" required />
            </div>

            <div class="mb-4">
              <label class="form-label text-muted small fw-bold text-uppercase">Senha</label>
              <input v-model="password" type="password" class="form-control form-control-lg bg-light border-0"
                placeholder="••••••••" required />
            </div>

            <div class="d-grid mt-5">
              <button type="submit" class="btn btn-custom btn-lg shadow-sm" :disabled="isLoading">
                {{ isLoading ? 'Entrando...' : 'Acessar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Spinner from '@/components/Spinner.vue'
import { useToast } from '@/composables/useToast'

export default {
  components: { Spinner },

  data() {
    return {
      email: '',
      password: '',
      isLoading: false,
      toast: null
    }
  },

  mounted() {
    this.toast = useToast()
  },

  methods: {
    async login() {
      this.isLoading = true

      try {
        const res = await fetch('http://localhost:8000/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            email: this.email,
            password: this.password
          })
        })

        const data = await res.json()

        if (!res.ok) {
          this.toast.error(data.error || 'Login ou senha inválidos')
          return
        }

        localStorage.setItem('token', data.access_token)
        this.$router.push({ name: 'dashboard' })

      } catch {
        this.toast.error('Erro ao conectar com o servidor')
      } finally {
        this.isLoading = false
      }
    }
  }
}
</script>

<style scoped>
.login-page {
  background: radial-gradient(circle at top, #eaf1ff 0%, #f4f6fb 60%);
}

.login-icon {
  width: 56px;
  height: 56px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: rgba(13, 110, 253, 0.1);
  color: var(--color-primary);
  font-size: 1.5rem;
}

.login-title {
  color: var(--color-text);
}
</style>
