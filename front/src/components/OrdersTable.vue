<template>
  <div v-if="!orders || orders.length === 0" class="empty-state text-center py-5">
    <i class="bi bi-inbox display-6 text-muted d-block mb-2"></i>
    <p class="text-muted mb-0">Nenhum pedido encontrado</p>
  </div>

  <div v-else class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>Código</th>
          <th>Data do pedido</th>
          <th>Destino</th>
          <th>Data de Ida</th>
          <th>Data de Volta</th>
          <th>Situação</th>
          <th v-if="canEdit()">Ações</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="order in orders" :key="order.id">
          <td class="text-muted">#{{ order.id }}</td>
          <td>{{ formatDate(order.created_at) }}</td>
          <td class="fw-semibold">{{ order.destination }}</td>
          <td>{{ formatDate(order.departure_date) }}</td>
          <td>{{ formatDate(order.return_date) }}</td>
          <td>
            <span :class="badgeClass(order.status)">
              {{ order.status }}
            </span>
          </td>
          <td v-if="canEdit()">
            <button v-if="order.status === 'solicitado'" class="btn btn-sm btn-success me-1" :disabled="isLoading(order.id)" @click="handleAction(order.id, 'aprovado')">
              Aprovar
            </button>

            <button v-if="order.status === 'solicitado'" class="btn btn-sm btn-outline-danger" :disabled="isLoading(order.id)" @click="handleAction(order.id, 'cancelado')">
              Cancelar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>

import { formatDate } from '@/utils/formatters';

export default {
  data() {
    return {
      loadingIds: []
    }
  },
  props: {
    orders: Array,
    isAdmin: Boolean,
    currentUserId: Number
  },
  methods: {
    formatDate,
    badgeClass(status) {
      return {
        solicitado: 'status-badge status-solicitado',
        aprovado: 'status-badge status-aprovado',
        cancelado: 'status-badge status-cancelado'
      }[status]
    },

    canEdit() {
      return this.isAdmin
    },
    handleAction(orderId, status) {
      if (this.loadingIds.includes(orderId)) return

      this.loadingIds.push(orderId)

      // avisa o pai que começou loading
      this.$emit('loading', true)

      this.$emit('updateStatus', orderId, status)
    },
    isLoading(orderId) {
      return this.loadingIds.includes(orderId)
    },
  },
  watch: {
    orders() {
      this.loadingIds = []
      this.$emit('loading', false)
    }
  }

}
</script>


<style scoped>
.status-badge {
  display: inline-block;
  padding: 0.35em 0.75em;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.03em;
  text-transform: uppercase;
}

.status-solicitado {
  color: #1f28c7;
  background: rgba(31, 40, 199, 0.1);
}

.status-aprovado {
  color: #0f9d4a;
  background: rgba(15, 157, 74, 0.12);
}

.status-cancelado {
  color: #dc2626;
  background: rgba(220, 38, 38, 0.1);
}

.empty-state {
  color: var(--color-muted);
}

thead th {
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  color: var(--color-muted);
  border-bottom-width: 1px;
}
</style>