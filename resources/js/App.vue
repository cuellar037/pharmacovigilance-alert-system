<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script setup></script>

<style>
body {
  background-color: #f8f9fa;
}

.btn-group .btn {
  font-size: 0.75rem;
}

.modal.fade.show {
  display: block !important;
  background-color: rgba(0, 0, 0, 0.5) !important;
}
</style>