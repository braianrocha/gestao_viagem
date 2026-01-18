<template>
  <table class="table table-striped">
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
        <td>#{{ order.id }}</td>
        <td>{{ formatDate(order.created_at) }}</td>
        <td>{{ order.destination }}</td>
        <td>{{ formatDate(order.departure_date) }}</td>
        <td>{{ formatDate(order.return_date) }}</td>
        <td>
          <span :class="badgeClass(order.status)">
            {{ order.status }}
          </span>
        </td>
        <td v-if="canEdit()">
          <button v-if="order.status === 'solicitado'" class="btn btn-sm btn-success me-1 fosco" :disabled="isLoading(order.id)" @click="handleAction(order.id, 'aprovado')">
            Aprovar
          </button>

          <button v-if="order.status === 'solicitado'" class="btn btn-sm btn-danger fosco" :disabled="isLoading(order.id)" @click="handleAction(order.id, 'cancelado')">
            Cancelar
          </button>
        </td>
      </tr>
    </tbody>
  </table>
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
  font-size: 0.9rem;
  font-weight: 700;
  text-transform: uppercase;
}

.fosco {
  opacity: 0.8;
}

/* azul fosco */
.status-solicitado {
  color: #1f28c7;
}

/* verde fosco */
.status-aprovado {
  color: rgb(17, 192, 76);
}

/* vermelho fosco */
.status-cancelado {
  color: rgba(220, 38, 38);
}
</style>