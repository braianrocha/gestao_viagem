<template>
  <div class="d-flex align-items-center gap-2 pb-3 shadow-sm">

    <div class="position-relative">
      <i class="bi bi-funnel position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>

      <select class="form-select ps-5" v-model="selected" @change="$emit('change', selected)">
        <option value="">Todos</option>
        <option value="solicitado">Solicitado</option>
        <option value="aprovado">Aprovado</option>
        <option value="cancelado">Cancelado</option>
      </select>
    </div>
  </div>
</template>

<script>
export default {
  emits: ['change'],
  data() {
    return {
      selected: ''
    }
  }
}
</script>
