<!-- resources/js/components/AlertModal.vue -->
<template>
  <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">⚠️ Confirmar Alerta</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <p>Enviar alerta al cliente:</p>
          <div class="card mb-3">
            <div class="card-body">
              <h6>{{ order.customer.name }}</h6>
              <p class="mb-1">Email: {{ order.customer.email }}</p>
              <p>Orden #{{ order.id }}</p>
            </div>
          </div>
          <div class="alert alert-warning">
            <strong>Lote #951357</strong> - Alerta de seguridad
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
          <button class="btn btn-warning" @click="$emit('confirm')">Enviar Alerta</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({ order: Object })
defineEmits(['confirm', 'close'])
</script>