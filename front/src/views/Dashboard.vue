<template>
  <div class="dashboard-page">
    <header class="dashboard-topbar">
      <div class="container d-flex justify-content-between align-items-center py-3">
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-airplane-fill text-primary fs-4"></i>
          <div>
            <h1 class="mb-0 titulo-dashboard">Pedidos de Viagem</h1>
            <small v-if="user" class="text-muted">Olá, {{ user.name }}</small>
          </div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <AddOrder @created="onOrderCreated" />
          <LogoutButton />
        </div>
      </div>
    </header>

    <Spinner :show="isLoading" />

    <main class="container py-4">
      <div class="surface-card p-3 p-md-4">
        <OrdersFilter @change="status => loadOrders(status)" />

        <OrdersTable :orders="orders" @updateStatus="updateStatus" :isAdmin="!!user?.is_admin" :currentUserId="user?.id" />
      </div>
    </main>
  </div>
</template>


<script>
import { useToast } from '@/composables/useToast'
import { apiFetch } from '@/services/api'
import { useNotifications } from '@/composables/useNotifications'
import OrdersTable from '@/components/OrdersTable.vue'
import OrdersFilter from '@/components/OrdersFilter.vue'
import LogoutButton from '@/components/LogoutButton.vue'
import Spinner from '@/components/Spinner.vue'
import AddOrder from '@/components/AddOrder.vue'

export default {
  components: {
    OrdersTable,
    OrdersFilter,
    LogoutButton,
    Spinner,
    AddOrder
  },

  data() {
    return {
      user: null,
      isLoading: false,
      orders: [],
      toast: null
    }
  },

  async mounted() {
    this.toast = useToast()
    try {
      this.user = await apiFetch('/me')
    } catch {
      this.$router.push({ name: 'login' })
      return
    }
    this.loadOrders()
    const { startPolling } = useNotifications(this.toast, () => {
      this.loadOrders('')
    })
    startPolling()
  },

  methods: {
    onOrderCreated() {
      this.isLoading = true

      setTimeout(() => {
        this.loadOrders('')
      }, 1500)
    },
    async loadOrders(status = '') {
      if (typeof status === 'object') return

      this.isLoading = true
      try {
        const query = status ? `?status=${status}` : ''
        this.orders = await apiFetch('/travel-orders' + query)
      } finally {
        this.isLoading = false
      }
    },

    async updateStatus(id, status) {
      try {
        const res = await apiFetch(`/travel-orders/${id}/status`, {
          method: 'PATCH',
          body: JSON.stringify({ status })
        })

        this.toast.success(res?.message || 'Situação atualizada com sucesso')
        this.loadOrders()
      } catch {
        this.toast.error('Erro ao atualizar status')
      }
    }
  }
}
</script>


<style scoped>
.dashboard-page {
  min-height: 100%;
}

.dashboard-topbar {
  background: var(--color-surface);
  border-bottom: 1px solid var(--color-border);
}

.titulo-dashboard {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-text);
  letter-spacing: -0.3px;
}
</style>