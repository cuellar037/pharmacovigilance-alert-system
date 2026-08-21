<template>
  <div
    class="relative min-h-screen w-full bg-[#0b132b] text-slate-200 flex items-center justify-center p-4 overflow-hidden font-sans">
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none"
      style="background-image: radial-gradient(#00f2fe 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#00f2fe]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#00c853]/15 rounded-full blur-3xl pointer-events-none"></div>
    <div
      class="relative z-10 w-full max-w-md bg-[#1c2541]/70 backdrop-blur-xl border border-[#00f2fe]/20 rounded-2xl p-8 shadow-[0_0_50px_rgba(0,0,0,0.6)]">
      <div class="flex flex-col items-center text-center mb-8">
        <div
          class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#00f2fe]/10 border border-[#00f2fe]/30 mb-4">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span>
          <span class="text-[10px] font-mono tracking-widest text-[#00f2fe] uppercase font-semibold">
            System Online • Pharma-Guard v2.4
          </span>
        </div>
        <div
          class="w-14 h-14 mb-3 rounded-2xl bg-gradient-to-br from-[#00f2fe] to-[#4facfe] flex items-center justify-center shadow-[0_0_20px_rgba(0,242,254,0.4)] text-[#0b132b]">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </div>

        <h1 class="text-2xl font-bold tracking-tight text-white">Pharmacovigilance</h1>
        <p class="text-xs text-slate-400 mt-1">Plataforma Avanzada de Alertas Médicas</p>
      </div>
      <form @submit.prevent="handleLogin" class="space-y-5">
        <div>
          <label for="username" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
            Identificador de Usuario
          </label>
          <div class="relative">
            <input type="text" id="username" v-model="form.username" required placeholder="Ej. admin_pharma"
              :disabled="loading" autocomplete="username"
              class="w-full bg-[#0b132b]/80 border border-slate-700/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#00f2fe] focus:ring-1 focus:ring-[#00f2fe] transition-all disabled:opacity-50" />
          </div>
        </div>
        <div>
          <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
            Clave de Acceso
          </label>
          <div class="relative">
            <input type="password" id="password" v-model="form.password" required placeholder="••••••••••••"
              :disabled="loading" autocomplete="current-password"
              class="w-full bg-[#0b132b]/80 border border-slate-700/60 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#00f2fe] focus:ring-1 focus:ring-[#00f2fe] transition-all disabled:opacity-50" />
          </div>
        </div>
        <button type="submit" :disabled="loading"
          class="w-full py-3 px-4 rounded-xl font-bold text-sm text-[#0b132b] bg-gradient-to-r from-[#00f2fe] to-[#00c853] hover:shadow-[0_0_25px_rgba(0,242,254,0.5)] active:scale-[0.99] transition-all disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2 mt-2">
          <svg v-if="loading" class="animate-spin h-5 w-5 text-[#0b132b]" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
          <span>{{ loading ? 'Autenticando...' : 'Iniciar Sesión' }}</span>
        </button>
      </form>
      <div class="mt-6 pt-4 border-t border-slate-700/40 text-center">
        <p class="text-[11px] text-slate-400 flex items-center justify-center gap-1.5">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#00f2fe]" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
          Encriptación Bio-Ciber SSL-256 Activa
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../axios'
import { alertError, alertSuccess } from '../sweetalert'

const router = useRouter()
const loading = ref(false)
const errorMessage = ref(null)

const form = ref({
  username: '',
  password: ''
})

const handleLogin = async () => {
  loading.value = true
  errorMessage.value = null

  try {
    await api.get('/sanctum/csrf-cookie')
    const response = await api.post('/login', form.value)

    if (response.data.token) {
      localStorage.setItem('auth_token', response.data.token)
      alertSuccess('✅ Sesión iniciada con éxito')
      router.push({ name: 'dashboard' })
    } else {
      errorMessage.value = 'Credenciales incorrectas'
      alertError('❌ Credenciales incorrectas')
    }
  } catch (error) {
    const errorMsg = error.response?.data?.message || 'Error al iniciar sesión'

    if (errorMsg.toLowerCase().includes('credentials')) {
      errorMessage.value = 'Credenciales incorrectas'
    } else {
      errorMessage.value = errorMsg
    }

    alertError('❌ ' + errorMessage.value)
  } finally {
    loading.value = false
  }
}
</script>