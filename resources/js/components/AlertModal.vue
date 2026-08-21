<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
        tabindex="-1" role="dialog" aria-modal="true" @click.self="handleClose" @keydown.esc="handleClose">
        <div
          class="relative w-full max-w-lg bg-slate-900 border border-slate-800 rounded-xl shadow-2xl overflow-hidden text-slate-100">

          <div class="flex items-start justify-between p-5 border-b border-slate-800">
            <div class="flex items-center gap-3">
              <div class="flex items-center justify-center w-10 h-10 rounded-full shrink-0"
                :class="bulk ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20'">
                <svg v-if="bulk" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <svg v-else class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-bold text-white leading-snug">
                  {{ bulk ? 'Notificación Masiva de Alerta' : 'Confirmar Envío de Alerta' }}
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">
                  {{ bulk ? `Selección múltiple: ${targetCount} destinatarios` : 'Notificación directa a cliente' }}
                </p>
              </div>
            </div>
            <button type="button"
              class="text-slate-400 hover:text-white hover:bg-slate-800 p-1.5 rounded-lg transition-colors disabled:opacity-50"
              :disabled="loading" @click="handleClose">
              <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="p-5 space-y-4">
            <div
              class="p-3.5 bg-amber-500/10 border border-amber-500/20 rounded-lg text-amber-300 flex gap-3 items-start text-xs">
              <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <span class="font-semibold text-amber-200">Medicamento / Lote:</span>
                <span
                  class="ml-1.5 px-2 py-0.5 bg-amber-500/20 text-amber-300 font-mono text-[11px] rounded border border-amber-500/30">
                  {{ batchNumber }}
                </span>
                <p class="mt-1 text-slate-300">{{ alertMessage }}</p>
              </div>
            </div>
            <div v-if="!bulk" class="bg-slate-950/60 border border-slate-800 rounded-lg p-4 space-y-3">
              <div class="flex items-center justify-between">
                <h4 class="font-semibold text-white text-sm">
                  {{ order?.customer?.name || 'Cliente sin nombre' }}
                </h4>
                <span class="text-xs font-mono bg-cyan-950 text-cyan-400 border border-cyan-800/60 px-2 py-0.5 rounded">
                  Orden #{{ order?.id || 'N/A' }}
                </span>
              </div>
              <div class="grid grid-cols-2 gap-3 text-xs border-t border-slate-800/80 pt-3 text-slate-400">
                <div>
                  <span class="block text-[10px] uppercase tracking-wider text-slate-500">Correo Electrónico</span>
                  <span class="text-slate-200 truncate block font-medium mt-0.5">{{ order?.customer?.email || 'No disponible' }}</span>
                </div>
                <div>
                  <span class="block text-[10px] uppercase tracking-wider text-slate-500">Teléfono</span>
                  <span class="text-slate-200 block font-medium mt-0.5">{{ order?.customer?.phone || 'No disponible'
                  }}</span>
                </div>
                <div class="col-span-2">
                  <span class="block text-[10px] uppercase tracking-wider text-slate-500">Fecha de Compra</span>
                  <span class="text-slate-200 block font-medium mt-0.5">{{ formatDate(order?.purchase_date) }}</span>
                </div>
              </div>
            </div>
            <div v-else class="space-y-2">
              <p class="text-xs text-slate-400">
                Se enviará notificación individual a los siguientes <strong class="text-slate-200">{{ targetCount
                }}</strong> clientes:
              </p>
              <div
                class="max-h-48 overflow-y-auto border border-slate-800 bg-slate-950/60 rounded-lg divide-y divide-slate-800/60">
                <div v-for="(item, index) in orders" :key="item.id || index"
                  class="flex items-center justify-between px-3 py-2 text-xs">
                  <div class="flex items-center gap-2 truncate pr-2">
                    <span class="font-mono text-slate-500 text-[11px]">{{ String(index + 1).padStart(2, '0') }}.</span>
                    <span class="text-slate-200 truncate">{{ item.customer?.name || 'Sin Nombre' }}</span>
                  </div>
                  <span class="text-[11px] font-mono text-slate-400 bg-slate-800 px-1.5 py-0.5 rounded shrink-0">
                    #{{ item.id }}
                  </span>
                </div>
              </div>
            </div>
            <p class="text-[11px] text-slate-400 flex items-center gap-1.5">
              <svg class="w-4 h-4 text-slate-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Esta acción registrará la notificación e iniciará el despacho por correo.
            </p>
          </div>
          <div class="flex items-center justify-end gap-3 p-4 bg-slate-950/40 border-t border-slate-800">
            <button type="button"
              class="px-4 py-2 text-xs font-medium text-slate-300 hover:text-white bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 rounded-lg transition-colors disabled:opacity-50"
              :disabled="loading" @click="handleClose">
              Cancelar
            </button>
            <button type="button"
              class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-amber-950 bg-amber-400 hover:bg-amber-300 rounded-lg shadow-lg shadow-amber-500/10 transition-colors disabled:opacity-50"
              :disabled="loading" @click="handleConfirm">
              <svg v-if="loading" class="animate-spin w-3.5 h-3.5 text-amber-950" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
              </svg>
              <span>
                {{ loading ? 'Enviando...' : (bulk ? `Enviar ${targetCount} Alertas` : 'Enviar Alerta') }}
              </span>
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  order: { type: Object, default: null },
  orders: { type: Array, default: () => [] },
  bulk: { type: Boolean, default: false },
  count: { type: Number, default: 0 },
  loading: { type: Boolean, default: false },
  batchNumber: { type: String, default: 'Lote #951357' },
  alertMessage: { type: String, default: 'Alerta de seguridad sobre el medicamento adquirido.' }
})

const emit = defineEmits(['confirm', 'close'])

const targetCount = computed(() => props.count || props.orders.length || 0)

function handleClose() {
  if (!props.loading) emit('close')
}

function handleConfirm() {
  if (!props.loading) emit('confirm')
}

function formatDate(date) {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Bloquea el scroll del fondo cuando el modal está abierto
onMounted(() => {
  document.body.style.overflow = 'hidden'
})

onUnmounted(() => {
  document.body.style.overflow = ''
})
</script>

<style scoped>
/* Transiciones suaves de entrada y salida */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
  transform: scale(0.96);
}
</style>