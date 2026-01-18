<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0 titulo-dashboard">Pedidos de Viagem</h2>

      <div class="d-flex align-items-center gap-2">
        <AddOrder @created="onOrderCreated" />
        <LogoutButton />
      </div>
    </div>

    <Spinner :show="isLoading" />

    <OrdersFilter @change="status => loadOrders(status)" />

    <OrdersTable :orders="orders" @updateStatus="updateStatus" :isAdmin="!!user?.is_admin" :currentUserId="user?.id" />
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
    const { startPolling } = useNotifications(this.toast)
    startPolling()
  },

  methods: {
    async init() {
      this.toast = useToast()
      this.user = await apiFetch('/me')
      this.loadOrders()

      // 🔔 inicia notificações
      const { startPolling } = useNotifications()
      startPolling()
    },
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
.titulo-dashboard {
  font-weight: 700;
  color: #0d6efd;
  letter-spacing: -0.5px
}
</style>