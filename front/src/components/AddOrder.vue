<template>
  <!-- BOTÃO -->
  <button
    class="btn btn-primary m-2 fw-semibold d-flex align-items-center gap-2"
    @click="open"
  >
    <i class="bi bi-plus-lg"></i>
    Novo Pedido
  </button>

  <!-- MODAL -->
  <Modal v-model="show" title="Novo Pedido de Viagem">
    <form @submit.prevent="submit">
      <div class="mb-3">
        <label class="form-label fw-semibold">Destino</label>
        <input
          v-model="form.destination"
          type="text"
          class="form-control"
        />
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Data de ida</label>
          <input
            v-model="form.departure_date"
            type="date"
            class="form-control"
            :min="today"
            :max="form.return_date"
            :minlength="3"
          />
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Data de volta</label>
          <input
            v-model="form.return_date"
            type="date"
            class="form-control"
            :min="form.departure_date || today"
          />
        </div>
      </div>
    </form>

    <template #footer>
      <button class="btn btn-secondary" @click="close">
        Cancelar
      </button>

      <button
        class="btn btn-primary"
        :disabled="isLoading"
        @click="submit"
      >
        {{ isLoading ? 'Salvando...' : 'Salvar' }}
      </button>
    </template>
  </Modal>
</template>


<script setup>

import { ref } from 'vue'
import Modal from '@/components/Modal.vue'
import { useToast } from '@/composables/useToast'

const emit = defineEmits(['created'])
const today = new Date().toISOString().split('T')[0]
const toast = useToast()

const show = ref(false)
const isLoading = ref(false)

const initialForm = {
  destination: '',
  departure_date: '',
  return_date: ''
}

const form = ref({ ...initialForm })

function open() {
  show.value = true
}

function close() {
  form.value = { ...initialForm }
  show.value = false
}

function validateForm() {
  const { destination, departure_date, return_date } = form.value
  // Destino obrigatório
  if (!destination || destination.trim() === '' || destination.length < 3) {
    return 'Preencha o seu destino ( mínimo 3 caracteres )'
  }

  
  // Datas de ida e volta obrigatórias
  if (!departure_date || !return_date) {
    return 'Preencha as datas de ida e volta'
  }

  if (new Date(departure_date) > new Date(return_date)) {
    return 'A data de ida não pode ser maior que a data de volta'
  }

  return null
}

async function submit() {
  isLoading.value = true
  const error = validateForm()
  if (error) {
    toast.error(error)
    isLoading.value = false
    return
  }

  

  try {
    const res = await fetch('http://localhost:8000/api/travel-orders', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify(form.value)
    })

    const data = await res.json()

    if (!res.ok) {
      throw new Error(data.error || 'Erro ao criar pedido')
    }

    emit('created', data)
    toast.success('Pedido criado com sucesso')
    close()

  } catch {
    toast.error('Erro ao criar pedido')
  } finally {
    isLoading.value = false
  }
}
</script>


