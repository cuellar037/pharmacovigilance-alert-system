<template>
  <div>
    <nav class="navbar navbar-dark bg-primary">
      <div class="container-fluid">
        <span class="navbar-brand">🔄 Pharmacovigilance Alert System</span>
        <button class="btn btn-outline-light" @click="logout">
          Cerrar Sesión
        </button>
      </div>
    </nav>
    <div class="container mt-4">
      <div class="card shadow mb-4">
        <div class="card-body">
          <h5 class="card-title">Buscar Órdenes</h5>
          <form @submit.prevent="searchOrders">
            <div class="row g-3 align-items-end">
              <div class="col-md-4">
                <label class="form-label">Número de Lote *</label>
                <input type="text" class="form-control" v-model="filters.lot" required placeholder="Ej: 951357">
              </div>
              <div class="col-md-3">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" v-model="filters.start_date">
              </div>
              <div class="col-md-3">
                <label class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" v-model="filters.end_date">
              </div>
              <div class="col-md-2">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                    <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                    Buscar
                  </button>
                  <button type="button" class="btn btn-success w-100" :disabled="orders.length === 0 || exporting"
                    @click="exportCSV">
                    <span v-if="exporting" class="spinner-border spinner-border-sm me-2"></span>
                    📥 Exportar CSV
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card shadow">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Órdenes Encontradas</h5>
            <div>
              <span class="badge bg-primary me-2">{{ orders.length }} resultados</span>
              <button v-if="selectedOrders.length > 0" class="btn btn-warning btn-sm" @click="showBulkAlertModal">
                📨 Alertar Seleccionados ({{ selectedOrders.length }})
              </button>
            </div>
          </div>

          <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
          </div>

          <div v-else-if="orders.length === 0" class="text-center py-5">
            <p class="text-muted">No se encontraron órdenes para este lote</p>
          </div>

          <div v-else class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th style="width: 40px;">
                    <input type="checkbox" @change="selectAll"
                      :checked="selectedOrders.length === orders.length && orders.length > 0">
                  </th>
                  <th>ID Orden</th>
                  <th>Cliente</th>
                  <th>Contacto</th>
                  <th>Fecha Compra</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="order in orders" :key="order.id">
                  <td>
                    <input type="checkbox" :value="order" v-model="selectedOrders">
                  </td>
                  <td>#{{ order.id }}</td>
                  <td>{{ order.customer.name }}</td>
                  <td>
                    <div>{{ order.customer.email }}</div>
                    <small class="text-muted">{{ order.customer.phone }}</small>
                  </td>
                  <td>{{ formatDate(order.purchase_date) }}</td>
                  <td>
                    <div class="btn-group btn-group-sm">
                      <router-link :to="{ name: 'order.detail', params: { id: order.id } }"
                        class="btn btn-outline-primary">
                        Ver Orden
                      </router-link>
                      <button class="btn btn-outline-warning" @click="showAlertModal(order)">
                        Alertar
                      </button>
                      <router-link :to="{ name: 'customer.detail', params: { id: order.customer.id } }"
                        class="btn btn-outline-info">
                        Ver Cliente
                      </router-link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for Individual Alert -->
    <AlertModal v-if="showModal" :order="selectedOrder" :bulk="false" @confirm="sendSingleAlert" @close="closeModal" />

    <!-- Modal for Mass Alerts -->
    <AlertModal v-if="showBulkModal" :orders="selectedOrders" :bulk="true" :count="selectedOrders.length"
      @confirm="sendBulkAlerts" @close="closeBulkModal" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../axios'
import AlertModal from '../components/AlertModal.vue'
import Swal from 'sweetalert2'
import { alertSuccess, alertError, alertWarning, successModal, errorModal } from '../sweetalert'

const router = useRouter()
const loading = ref(false)
const exporting = ref(false)
const orders = ref([])
const selectedOrders = ref([])

// Modales
const showModal = ref(false)
const showBulkModal = ref(false)
const selectedOrder = ref(null)

// ---------- FILTER PERSISTENCE ---------- //
const saveFilters = () => {
  localStorage.setItem('dashboard_filters', JSON.stringify(filters.value))
}

const loadFilters = () => {
  const saved = localStorage.getItem('dashboard_filters')
  if (saved) {
    try {
      const parsed = JSON.parse(saved)
      filters.value = parsed
      return true
    } catch (e) {
      return false
    }
  }
  return false
}

const filters = ref({
  lot: '951357',
  start_date: getDefaultStartDate(),
  end_date: getDefaultEndDate()
})

const savedFilters = loadFilters()
if (!savedFilters) {
  filters.value = {
    lot: '951357',
    start_date: getDefaultStartDate(),
    end_date: getDefaultEndDate()
  }
}

// Utility functions
function getDefaultStartDate() {
  const date = new Date()
  date.setDate(date.getDate() - 30)
  return date.toISOString().split('T')[0]
}

function getDefaultEndDate() {
  return new Date().toISOString().split('T')[0]
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Search orders
const searchOrders = async () => {
  loading.value = true
  selectedOrders.value = []
  try {
    const response = await api.get('/orders', {
      params: {
        lot: filters.value.lot,
        start_date: filters.value.start_date,
        end_date: filters.value.end_date
      }
    })
    orders.value = response.data.data || []
    saveFilters()
  } catch (error) {
    console.error('Error al buscar órdenes:', error)
    alertError('❌ Error al buscar órdenes')
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  searchOrders()
})

const selectAll = (event) => {
  if (event.target.checked) {
    selectedOrders.value = [...orders.value]
  } else {
    selectedOrders.value = []
  }
}

// ---------- INDIVIDUAL ALERT ---------- //
const showAlertModal = (order) => {
  selectedOrder.value = order
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedOrder.value = null
}

const sendSingleAlert = async () => {
  try {
    const payload = {
      alerts: [
        {
          customer_id: selectedOrder.value.customer.id,
          order_id: selectedOrder.value.id
        }
      ]
    }

    await api.post('/alerts/send', payload)
    alertSuccess('✅ Alerta enviada exitosamente al cliente')
    closeModal()
  } catch (error) {
    console.error('Error:', error.response?.data)
    alertError('❌ Error al enviar la alerta')
  }
}

// ---------- MASS ALERTS ---------- //
const showBulkAlertModal = () => {
  showBulkModal.value = true
}

const closeBulkModal = () => {
  showBulkModal.value = false
}

const sendBulkAlerts = async () => {
  try {
    const alerts = selectedOrders.value.map(order => ({
      customer_id: order.customer.id,
      order_id: order.id
    }))

    await api.post('/alerts/send', { alerts })

    alertSuccess(`✅ ${alerts.length} alertas enviadas exitosamente`)
    selectedOrders.value = []
    closeBulkModal()
  } catch (error) {
    console.error('Error:', error.response?.data)
    alertError('❌ Error al enviar las alertas')
  }
}

// ---------- EXPORT CSV ---------- //
const exportCSV = async () => {
  exporting.value = true
  try {
    const response = await api.get('/orders/export', {
      params: {
        lot: filters.value.lot,
        start_date: filters.value.start_date,
        end_date: filters.value.end_date
      },
      responseType: 'blob'
    })

    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    const timestamp = new Date().toISOString().slice(0, 10)
    link.setAttribute('download', `ordenes_lote_${filters.value.lot}_${timestamp}.csv`)

    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.URL.revokeObjectURL(url)

    alertSuccess('✅ CSV exportado exitosamente')
  } catch (error) {
    console.error('Error al exportar CSV:', error)
    alertError('❌ Error al exportar el archivo')
  } finally {
    exporting.value = false
  }
}

// ---------- LOGOUT ---------- // 
const logout = async () => {
  try {
    // Confirmación antes de cerrar sesión
    const result = await Swal.fire({
      title: '¿Cerrar sesión?',
      text: '¿Estás seguro que deseas salir?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, salir',
      cancelButtonText: 'Cancelar'
    })

    if (result.isConfirmed) {
      localStorage.removeItem('auth_token')
      router.push({ name: 'login' })
    }
  } catch (error) {
    console.error('Error al cerrar sesión:', error)
  }
}
</script>