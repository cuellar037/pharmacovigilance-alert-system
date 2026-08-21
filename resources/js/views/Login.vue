<template>
	<div class="min-vh-100 d-flex align-items-center bg-light">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="card shadow">
						<div class="card-body p-5">
							<h2 class="text-center mb-2">🔬 Pharmacovigilance</h2>
							<p class="text-center text-muted mb-4">Sistema de Alertas</p>

							<form @submit.prevent="handleLogin">
								<div class="mb-3">
									<label for="username" class="form-label">Usuario</label>
									<input type="text" class="form-control" id="username" v-model="form.username" required
										placeholder="Ingresa tu usuario" :disabled="loading">
								</div>

								<div class="mb-3">
									<label for="password" class="form-label">Contraseña</label>
									<input type="password" class="form-control" id="password" v-model="form.password" required
										placeholder="Ingresa tu contraseña" :disabled="loading">
								</div>

								<div v-if="errorMessage" class="alert alert-danger">
									{{ errorMessage }}
								</div>

								<button type="submit" class="btn btn-primary w-100" :disabled="loading">
									<span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
									{{ loading ? 'Iniciando sesión...' : 'Iniciar Sesión' }}
								</button>
							</form>

							<div class="mt-3 text-center text-muted small">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../axios'

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
			router.push({ name: 'dashboard' })
		} else {
			errorMessage.value = 'Credenciales incorrectas'
		}
	} catch (error) {
		errorMessage.value = error.response?.data?.message || 'Error al iniciar sesión'
	} finally {
		loading.value = false
	}
}
</script>