<!-- resources/js/views/OrderDetail.vue -->
<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Detalle de Orden #{{ order?.id }}</h2>
      <router-link to="/" class="btn btn-secondary">
        ← Volver al Dashboard
      </router-link>
    </div>
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary"></div>
    </div>
    <div v-else-if="order" class="row">
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-header">
            <h5 class="mb-0">Información del Cliente</h5>
          </div>
          <div class="card-body">
            <h6>{{ order.customer?.name }}</h6>
            <p class="mb-1"><strong>Email:</strong> {{ order.customer?.email }}</p>
            <p><strong>Teléfono:</strong> {{ order.customer?.phone }}</p>
            <router-link :to="{ name: 'customer.detail', params: { id: order.customer?.id } }"
              class="btn btn-outline-info btn-sm">
              Ver Perfil Completo
            </router-link>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Detalles de Compra</h5>
          </div>
          <div class="card-body">
            <p><strong>Fecha:</strong> {{ formatDate(order.purchase_date) }}</p>
            <p><strong>Estado:</strong> <span class="badge bg-success">Completada</span></p>
            <p><strong>Total de Items:</strong> {{ order.items?.length || 0 }}</p>
          </div>
        </div>
      </div>
      <div class="col-12 mt-3">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Medicamentos en la Orden</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Medicamento</th>
                    <th>Lote</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in order.items" :key="item.id">
                    <td>{{ item.medication?.name || 'Sin nombre' }}</td>
                    <td>
                      <span class="badge bg-warning">
                        {{ item.medication?.lot_number || 'N/A' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../axios'

const route = useRoute()
const loading = ref(true)
const order = ref(null)

onMounted(async () => {
  try {
    const response = await api.get(`/orders/${route.params.id}`)
    order.value = response.data
  } catch (error) {
    console.error('Error:', error)
    alert('Error al cargar la orden')
  } finally {
    loading.value = false
  }
})

function formatDate(date) {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>