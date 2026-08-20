<!-- resources/js/components/AlertModal.vue -->
<template>
  <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <span v-if="bulk">📨 Alertas Masivas</span>
            <span v-else>⚠️ Confirmar Alerta</span>
          </h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <!-- Modo Bulk -->
          <div v-if="bulk">
            <p>Estás a punto de enviar alertas a <strong>{{ count }}</strong> clientes:</p>
            <div class="card mb-3">
              <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                <ul class="list-unstyled mb-0">
                  <li v-for="(item, index) in orders" :key="item.id" class="py-1 border-bottom">
                    <strong>{{ index + 1 }}.</strong> {{ item.customer.name }}
                    <span class="text-muted">(Orden #{{ item.id }})</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Modo Individual -->
          <div v-else>
            <p>Estás a punto de enviar una alerta al siguiente cliente:</p>
            <div class="card mb-3">
              <div class="card-body">
                <h6 class="card-title">{{ order?.customer?.name }}</h6>
                <p class="card-text">
                  <strong>Email:</strong> {{ order?.customer?.email || 'No disponible' }}<br>
                  <strong>Teléfono:</strong> {{ order?.customer?.phone || 'No disponible' }}<br>
                  <strong>Orden #:</strong> {{ order?.id || 'N/A' }}<br>
                  <strong>Fecha Compra:</strong> {{ formatDate(order?.purchase_date) }}
                </p>
              </div>
            </div>
          </div>

          <div class="alert alert-warning">
            <strong>Medicamento:</strong> Lote #951357<br>
            <strong>Mensaje:</strong> Alerta de seguridad sobre el medicamento adquirido.
          </div>

          <p class="text-muted small">
            Esta acción enviará un correo electrónico {{ bulk ? 'a cada cliente' : 'al cliente' }}.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="$emit('close')">
            Cancelar
          </button>
          <button type="button" class="btn btn-warning" @click="$emit('confirm')">
            <span v-if="bulk">📨 Enviar {{ count }} Alertas</span>
            <span v-else>Enviar Alerta</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

const props = defineProps({
  order: { type: Object, default: null },
  orders: { type: Array, default: () => [] },
  bulk: { type: Boolean, default: false },
  count: { type: Number, default: 0 }
})

defineEmits(['confirm', 'close'])

function formatDate(date) {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>