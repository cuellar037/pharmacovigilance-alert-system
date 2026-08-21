<template>
  <div
    class="relative min-h-screen bg-[#0b132b] text-slate-200 font-sans selection:bg-[#00f2fe] selection:text-[#0b132b]">
    <div class="fixed inset-0 z-0 opacity-15 pointer-events-none"
      style="background-image: radial-gradient(#00f2fe 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="fixed -top-40 -right-40 w-96 h-96 bg-[#00f2fe]/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed top-1/2 -left-40 w-96 h-96 bg-[#00c853]/10 rounded-full blur-3xl pointer-events-none"></div>
    <header class="relative z-20 border-b border-[#00f2fe]/20 bg-[#1c2541]/80 backdrop-blur-md sticky top-0">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#00f2fe] to-[#4facfe] flex items-center justify-center shadow-[0_0_15px_rgba(0,242,254,0.3)] text-[#0b132b]">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
          <div>
            <h1 class="text-base font-bold text-white tracking-wide leading-tight">PHARMA-GUARD</h1>
            <p class="text-[10px] text-[#00f2fe] font-mono tracking-widest uppercase">Alert & Vigilance System</p>
          </div>
        </div>
        <div class="flex items-center gap-4">
          <div
            class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-[#0b132b]/60 border border-slate-700/60 text-xs">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-slate-300 font-mono">ADMIN_SESSION_ACTIVE</span>
          </div>
          <button @click="logout"
            class="px-3.5 py-1.5 rounded-lg border border-rose-500/30 text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 hover:border-rose-500/60 transition-all text-xs font-semibold flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span>Cerrar Sesión</span>
          </button>
        </div>
      </div>
    </header>
    <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
      <section class="bg-[#1c2541]/70 backdrop-blur-xl border border-[#00f2fe]/20 rounded-2xl p-6 shadow-2xl">
        <div class="flex items-center gap-2 mb-4 border-b border-slate-700/50 pb-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#00f2fe]" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <h2 class="text-sm font-bold uppercase tracking-wider text-white">Búsqueda de Órdenes y Lotes</h2>
        </div>
        <form @submit.prevent="searchOrders">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-4">
              <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                Número de Lote <span class="text-[#00f2fe]">*</span>
              </label>
              <input type="text" v-model="filters.lot" required placeholder="Ej: 951357"
                class="w-full bg-[#0b132b]/80 border border-slate-700/60 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#00f2fe] focus:ring-1 focus:ring-[#00f2fe] transition-all" />
            </div>
            <div class="md:col-span-3">
              <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Fecha
                Inicio</label>
              <input type="date" v-model="filters.start_date"
                class="w-full bg-[#0b132b]/80 border border-slate-700/60 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#00f2fe] focus:ring-1 focus:ring-[#00f2fe] transition-all color-scheme-dark" />
            </div>
            <div class="md:col-span-3">
              <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Fecha Fin</label>
              <input type="date" v-model="filters.end_date"
                class="w-full bg-[#0b132b]/80 border border-slate-700/60 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-[#00f2fe] focus:ring-1 focus:ring-[#00f2fe] transition-all color-scheme-dark" />
            </div>
            <div class="md:col-span-2 flex flex-col gap-2">
              <button type="submit" :disabled="loading"
                class="w-full py-2.5 px-4 rounded-xl font-bold text-xs uppercase tracking-wider text-[#0b132b] bg-gradient-to-r from-[#00f2fe] to-[#4facfe] hover:shadow-[0_0_20px_rgba(0,242,254,0.4)] active:scale-[0.98] transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                <svg v-if="loading" class="animate-spin h-4 w-4 text-[#0b132b]" xmlns="http://www.w3.org/2000/svg"
                  fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
                <span>{{ loading ? 'Buscando...' : 'Buscar' }}</span>
              </button>
              <button type="button" :disabled="orders.length === 0 || exporting" @click="exportCSV"
                class="w-full py-2 px-3 rounded-xl font-semibold text-xs text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 hover:bg-emerald-500/20 hover:border-emerald-500/60 transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-1.5">
                <svg v-if="exporting" class="animate-spin h-4 w-4 text-emerald-400" xmlns="http://www.w3.org/2000/svg"
                  fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                  </path>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Exportar CSV</span>
              </button>
            </div>
          </div>
        </form>
      </section>
      <section class="bg-[#1c2541]/70 backdrop-blur-xl border border-[#00f2fe]/20 rounded-2xl p-6 shadow-2xl">
        <div
          class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-700/50">
          <div class="flex items-center gap-3">
            <h3 class="text-base font-bold text-white">Órdenes Encontradas</h3>
            <span
              class="px-2.5 py-0.5 rounded-full bg-[#00f2fe]/10 border border-[#00f2fe]/30 text-[#00f2fe] text-xs font-mono font-semibold">
              {{ orders.length }} Registros
            </span>
          </div>
          <button v-if="selectedOrders.length > 0" @click="showBulkAlertModal"
            class="px-4 py-2 rounded-xl bg-amber-500/20 border border-amber-500/40 text-amber-300 hover:bg-amber-500/30 hover:border-amber-500/70 transition-all text-xs font-bold flex items-center justify-center gap-2 shadow-[0_0_15px_rgba(245,158,11,0.2)] animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>Alertar Seleccionados ({{ selectedOrders.length }})</span>
          </button>
        </div>
        <div v-if="loading" class="flex flex-col items-center justify-center py-16 gap-3">
          <div class="w-10 h-10 border-2 border-[#00f2fe] border-t-transparent rounded-full animate-spin"></div>
          <span class="text-xs text-slate-400 font-mono tracking-widest uppercase">Consultando base de datos...</span>
        </div>
        <div v-else-if="orders.length === 0"
          class="text-center py-16 border border-dashed border-slate-700/60 rounded-xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-600 mx-auto mb-3" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
          </svg>
          <p class="text-slate-400 text-sm font-medium">No se encontraron órdenes asociadas a este lote o rango de
            fechas</p>
        </div>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead>
              <tr
                class="border-b border-slate-700/60 text-xs font-semibold text-slate-400 uppercase tracking-wider bg-[#0b132b]/40">
                <th class="p-3.5 w-10 text-center">
                  <input type="checkbox" @change="selectAll"
                    :checked="selectedOrders.length === orders.length && orders.length > 0"
                    class="rounded border-slate-700 bg-[#0b132b] text-[#00f2fe] focus:ring-[#00f2fe] focus:ring-offset-0 cursor-pointer" />
                </th>
                <th class="p-3.5">ID Orden</th>
                <th class="p-3.5">Cliente</th>
                <th class="p-3.5">Contacto</th>
                <th class="p-3.5">Fecha Compra</th>
                <th class="p-3.5 text-center">Acciones de Alerta</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/60">
              <tr v-for="order in orders" :key="order.id" class="hover:bg-[#00f2fe]/5 transition-colors group">
                <td class="p-3.5 text-center">
                  <input type="checkbox" :value="order" v-model="selectedOrders"
                    class="rounded border-slate-700 bg-[#0b132b] text-[#00f2fe] focus:ring-[#00f2fe] focus:ring-offset-0 cursor-pointer" />
                </td>
                <td class="p-3.5 font-mono font-bold text-[#00f2fe]">
                  #{{ order.id }}
                </td>
                <td class="p-3.5 font-semibold text-white">
                  {{ order.customer.name }}
                </td>
                <td class="p-3.5">
                  <div class="text-slate-200 text-xs">{{ order.customer.email }}</div>
                  <div class="text-slate-500 text-[11px] font-mono">{{ order.customer.phone }}</div>
                </td>
                <td class="p-3.5 text-slate-300 text-xs">
                  {{ formatDate(order.purchase_date) }}
                </td>
                <td class="p-3.5">
                  <div class="flex items-center justify-center gap-1.5">
                    <router-link :to="{ name: 'order.detail', params: { id: order.id } }"
                      class="px-2.5 py-1 rounded-lg border border-slate-700 bg-[#0b132b]/60 text-slate-300 hover:border-[#00f2fe] hover:text-[#00f2fe] transition-all text-xs"
                      title="Ver Detalles de la Orden">
                      Ver Orden
                    </router-link>
                    <button @click="showAlertModal(order)"
                      class="px-2.5 py-1 rounded-lg border border-amber-500/40 bg-amber-500/10 text-amber-300 hover:bg-amber-500/20 hover:border-amber-500/80 transition-all text-xs font-semibold">
                      Alertar
                    </button>
                    <router-link :to="{ name: 'customer.detail', params: { id: order.customer.id } }"
                      class="px-2.5 py-1 rounded-lg border border-cyan-500/30 bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500/20 hover:border-cyan-500/60 transition-all text-xs"
                      title="Perfil del Cliente">
                      Cliente
                    </router-link>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>

    <AlertModal v-if="showModal" :order="selectedOrder" :bulk="false" @confirm="sendSingleAlert" @close="closeModal" />

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
import { alertSuccess, alertError } from '../sweetalert'

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
    const result = await Swal.fire({
      title: '¿Cerrar sesión?',
      text: '¿Estás seguro que deseas salir?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#00f2fe',
      cancelButtonColor: '#f43f5e',
      confirmButtonText: 'Sí, salir',
      cancelButtonText: 'Cancelar',
      background: '#1c2541',
      color: '#ffffff'
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

<style scoped>
/* Clase utilitaria para que los inputs tipo date de HTML5 adopten color oscuro en iconos nativos */
.color-scheme-dark {
  color-scheme: dark;
}
</style>