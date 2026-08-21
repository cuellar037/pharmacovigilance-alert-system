<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
        <div>
          <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
            <router-link to="/" class="hover:text-cyan-400 transition-colors">Dashboard</router-link>
            <span>/</span>
            <span class="text-slate-200">Órdenes</span>
          </div>
          <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-3">
            <span>Orden #{{ route.params.id }}</span>
            <span v-if="order"
              class="text-xs font-mono font-normal px-2.5 py-1 bg-cyan-950/80 text-cyan-400 border border-cyan-800/50 rounded-full">
              {{ order.order_items?.length || 0 }} Items
            </span>
          </h1>
        </div>
        <router-link to="/"
          class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-medium text-slate-300 hover:text-white bg-slate-900 hover:bg-slate-800 border border-slate-800 rounded-lg transition-colors w-fit">
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Volver al Dashboard
        </router-link>
      </div>
      <div v-if="loading" class="grid grid-cols-12 gap-6 animate-pulse">
        <div class="col-span-12 lg:col-span-4 bg-slate-900 border border-slate-800 rounded-xl p-6 h-64"></div>
        <div class="col-span-12 lg:col-span-8 bg-slate-900 border border-slate-800 rounded-xl p-6 h-64"></div>
      </div>
      <div v-else-if="order" class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-4 space-y-6">
          <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl"></div>
            <h3 class="text-xs font-mono uppercase tracking-wider text-slate-400 mb-4 pb-3 border-b border-slate-800">
              Datos del Cliente
            </h3>
            <div class="space-y-4 text-xs">
              <div>
                <span class="block text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Nombre</span>
                <router-link v-if="order.customer?.id"
                  :to="{ name: 'customer.detail', params: { id: order.customer.id } }"
                  class="text-sm font-bold text-cyan-400 hover:text-cyan-300 hover:underline transition-colors block">
                  {{ order.customer.name }}
                </router-link>
                <span v-else class="text-sm font-bold text-slate-300">
                  {{ order.customer?.name || 'Cliente no asignado' }}
                </span>
              </div>
              <div>
                <span class="block text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Correo Electrónico</span>
                <span class="text-slate-200 font-medium block truncate">{{ order.customer?.email || 'No disponible'
                }}</span>
              </div>
              <div>
                <span class="block text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Teléfono</span>
                <span class="text-slate-200 font-medium block">{{ order.customer?.phone || 'No disponible' }}</span>
              </div>
            </div>
          </div>
          <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-xl">
            <h3 class="text-xs font-mono uppercase tracking-wider text-slate-400 mb-4 pb-3 border-b border-slate-800">
              Detalles de Compra
            </h3>
            <div class="space-y-4 text-xs">
              <div>
                <span class="block text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Fecha de Compra</span>
                <span class="text-slate-200 font-medium block">{{ formatDate(order.purchase_date) }}</span>
              </div>
              <div>
                <span class="block text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Lote / Registro</span>
                <span
                  class="inline-block px-2 py-0.5 bg-amber-500/10 text-amber-300 border border-amber-500/20 font-mono rounded text-[11px] mt-0.5">
                  {{ order.batch_number || 'Lote #951357' }}
                </span>
              </div>
              <div v-if="order.customer?.id" class="pt-3 border-t border-slate-800">
                <router-link :to="{ name: 'customer.detail', params: { id: order.customer.id } }"
                  class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 text-xs font-medium text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700/80 rounded-lg border border-slate-700 transition-colors">
                  Ver Perfil Completo del Cliente
                </router-link>
              </div>
            </div>
          </div>
        </div>
        <div class="col-span-12 lg:col-span-8">
          <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-xl overflow-hidden">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
              <h3 class="font-bold text-white text-base">Medicamentos Adquiridos</h3>
              <span class="text-xs text-slate-400 font-mono">{{ order.order_items?.length || 0 }} productos</span>
            </div>
            <div v-if="!order.order_items || order.order_items.length === 0" class="p-12 text-center">
              <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
              <p class="text-slate-400 text-sm font-medium">Esta orden no registra medicamentos asociados</p>
            </div>
            <div v-else class="overflow-x-auto">
              <table class="w-full text-left text-xs text-slate-300">
                <thead
                  class="bg-slate-950/50 text-slate-400 uppercase tracking-wider font-mono border-b border-slate-800 text-[11px]">
                  <tr>
                    <th scope="col" class="py-3 px-4 w-12 text-center">#</th>
                    <th scope="col" class="py-3 px-4">Medicamento / Producto</th>
                    <th scope="col" class="py-3 px-4 text-right w-32">Cantidad</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                  <tr v-for="(item, index) in order.order_items" :key="item.id || index"
                    class="hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-mono text-slate-500 text-center">
                      {{ String(index + 1).padStart(2, '0') }}
                    </td>
                    <td class="py-3.5 px-4 font-medium text-white truncate max-w-xs sm:max-w-none">
                      {{ item.medication?.name || item.name || 'Medicamento Sin Nombre' }}
                    </td>

                    <td class="py-3.5 px-4 text-right font-mono">
                      <span
                        class="inline-flex items-center justify-center min-w-[2.5rem] px-2.5 py-0.5 rounded-md bg-slate-800 text-slate-200 border border-slate-700/60 font-semibold">
                        {{ item.quantity || 1 }}
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
    console.log(order);
    
  } catch (error) {
    console.error('Error al obtener el detalle de la orden:', error)
  } finally {
    loading.value = false
  }
})

function formatDate(date) {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

function formatCurrency(amount) {
  if (amount === undefined || amount === null) return 'N/A'
  return new Intl.NumberFormat('es-CO', {
    style: 'currency',
    currency: 'COP',
    maximumFractionDigits: 0
  }).format(amount)
}
</script>