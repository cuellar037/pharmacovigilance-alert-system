<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
        <div>
          <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
            <router-link to="/" class="hover:text-cyan-400 transition-colors">Dashboard</router-link>
            <span>/</span>
            <span class="text-slate-200">Clientes</span>
          </div>
          <h1 class="text-2xl font-bold text-white tracking-tight">Detalle del Cliente</h1>
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
      <div v-else-if="customer" class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-4 space-y-6">
          <div class="bg-slate-900 border border-slate-800 rounded-xl p-6 shadow-xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-800">
              <div
                class="w-12 h-12 rounded-full bg-cyan-950 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-bold text-lg shrink-0">
                {{ getInitials(customer.name) }}
              </div>
              <div class="truncate">
                <h2 class="text-lg font-bold text-white truncate">{{ customer.name }}</h2>
                <span
                  class="text-xs font-mono text-cyan-400 bg-cyan-950/60 border border-cyan-800/40 px-2 py-0.5 rounded">
                  ID: #{{ customer.id }}
                </span>
              </div>
            </div>
            <div class="space-y-4 text-xs">
              <div>
                <span class="block text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Correo Electrónico</span>
                <div class="flex items-center gap-2 text-slate-200 font-medium truncate">
                  <svg class="w-4 h-4 text-slate-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <span class="truncate">{{ customer.email || 'No registrado' }}</span>
                </div>
              </div>
              <div>
                <span class="block text-[10px] uppercase tracking-wider text-slate-500 mb-0.5">Teléfono</span>
                <div class="flex items-center gap-2 text-slate-200 font-medium">
                  <svg class="w-4 h-4 text-slate-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <span>{{ customer.phone || 'No registrado' }}</span>
                </div>
              </div>
              <div class="pt-4 border-t border-slate-800 flex items-center justify-between">
                <span class="text-slate-400">Total de Órdenes</span>
                <span
                  class="text-sm font-bold font-mono text-white bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700">
                  {{ customer.orders?.length || 0 }}
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-span-12 lg:col-span-8">
          <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-xl overflow-hidden">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
              <h3 class="font-bold text-white text-base">Historial de Compras</h3>
              <span class="text-xs text-slate-400 font-mono">{{ customer.orders?.length || 0 }} registros</span>
            </div>
            <div v-if="!customer.orders || customer.orders.length === 0" class="p-12 text-center">
              <svg class="w-12 h-12 text-slate-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              <p class="text-slate-400 text-sm font-medium">Este cliente no tiene órdenes registradas</p>
            </div>
            <div v-else class="overflow-x-auto">
              <table class="w-full text-left text-xs text-slate-300">
                <thead
                  class="bg-slate-950/50 text-slate-400 uppercase tracking-wider font-mono border-b border-slate-800 text-[11px]">
                  <tr>
                    <th scope="col" class="py-3 px-4">Orden</th>
                    <th scope="col" class="py-3 px-4">Fecha</th>
                    <th scope="col" class="py-3 px-4">Medicamentos</th>
                    <th scope="col" class="py-3 px-4 text-right">Acción</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                  <tr v-for="order in customer.orders" :key="order.id" class="hover:bg-slate-800/40 transition-colors">
                    <td class="py-3.5 px-4 font-mono font-medium text-cyan-400">
                      #{{ order.id }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-300">
                      {{ formatDate(order.purchase_date) }}
                    </td>
                    <td class="py-3.5 px-4">
                      <span
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-slate-800 border border-slate-700/60 text-slate-300 text-[11px]">
                        {{ order.orderItems?.length || 0 }} items
                      </span>
                    </td>
                    <td class="py-3.5 px-4 text-right">
                      <router-link :to="{ name: 'order.detail', params: { id: order.id } }"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-medium text-cyan-400 hover:text-cyan-300 bg-cyan-950/50 hover:bg-cyan-950 border border-cyan-800/50 rounded-md transition-colors">
                        Ver Orden
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                          stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                      </router-link>
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
const customer = ref(null)

onMounted(async () => {
  try {
    const response = await api.get(`/customers/${route.params.id}`)
    customer.value = response.data
  } catch (error) {
    console.error('Error:', error)
  } finally {
    loading.value = false
  }
})

function getInitials(name) {
  if (!name) return 'C'
  return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase()
}

function formatDate(date) {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>