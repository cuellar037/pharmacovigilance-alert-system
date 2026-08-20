
<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script setup></script>

<style>
/* Estilos globales */
body {
  background-color: #f8f9fa;
}

.btn-group .btn {
  font-size: 0.75rem;
}

/* Estilos para el modal */
.modal.fade.show {
  display: block !important;
  background-color: rgba(0, 0, 0, 0.5) !important;
}
</style>