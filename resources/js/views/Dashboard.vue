<!-- resources/js/views/Dashboard.vue -->
<template>
	<div>
		<!-- Navbar -->
		<nav class="navbar navbar-dark bg-primary">
			<div class="container-fluid">
				<span class="navbar-brand">🔄 Pharmacovigilance Alert System</span>
				<button class="btn btn-outline-light" @click="logout">
					Cerrar Sesión
				</button>
			</div>
		</nav>

		<div class="container mt-4">
			<!-- Formulario de Búsqueda -->
			<div class="card shadow mb-4">
				<div class="card-body">
					<h5 class="card-title">Buscar Órdenes</h5>
					<form @submit.prevent="searchOrders">
						<div class="row g-3 align-items-end">
							<div class="col-md-4">
								<label class="form-label">Número de Lote *</label>
								<input type="text" class="form-control" v-model="filters.lot" required placeholder="Ej: 951357">
							</div>
							<div class="col-md-3">
								<label class="form-label">Fecha Inicio</label>
								<input type="date" class="form-control" v-model="filters.start_date">
							</div>
							<div class="col-md-3">
								<label class="form-label">Fecha Fin</label>
								<input type="date" class="form-control" v-model="filters.end_date">
							</div>
							<div class="col-md-2">
								<button type="submit" class="btn btn-primary w-100" :disabled="loading">
									<span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
									Buscar
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<!-- Tabla de Resultados -->
			<div class="card shadow">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="card-title mb-0">Órdenes Encontradas</h5>
						<span class="badge bg-primary">{{ orders.length }} resultados</span>
					</div>

					<div v-if="loading" class="text-center py-5">
						<div class="spinner-border text-primary"></div>
					</div>

					<div v-else-if="orders.length === 0" class="text-center py-5">
						<p class="text-muted">No se encontraron órdenes para este lote</p>
					</div>

					<div v-else class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>ID Orden</th>
									<th>Cliente</th>
									<th>Contacto</th>
									<th>Fecha Compra</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="order in orders" :key="order.id">
									<td>#{{ order.id }}</td>
									<td>{{ order.customer.name }}</td>
									<td>
										<div>{{ order.customer.email }}</div>
										<small class="text-muted">{{ order.customer.phone }}</small>
									</td>
									<td>{{ formatDate(order.purchase_date) }}</td>
									<td>
										<div class="btn-group btn-group-sm">
											<router-link :to="{ name: 'order.detail', params: { id: order.id } }"
												class="btn btn-outline-primary">
												Ver Orden
											</router-link>
											<button class="btn btn-outline-warning" @click="showAlertModal(order)">
												Alertar
											</button>
											<router-link :to="{ name: 'customer.detail', params: { id: order.customer.id } }"
												class="btn btn-outline-info">
												Ver Cliente
											</router-link>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal de Alerta -->
		<AlertModal v-if="showModal" :order="selectedOrder" @confirm="sendAlert" @close="closeModal" />
	</div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../axios'
import AlertModal from '../components/AlertModal.vue'

const router = useRouter()
const loading = ref(false)
const orders = ref([])
const showModal = ref(false)
const selectedOrder = ref(null)

// Filtros por defecto
const filters = ref({
	lot: '951357',
	start_date: getDefaultStartDate(),
	end_date: getDefaultEndDate()
})

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

// Buscar órdenes
const searchOrders = async () => {
	loading.value = true
	try {
		const response = await api.get('/orders', {
			params: {
				lot: filters.value.lot,
				start_date: filters.value.start_date,
				end_date: filters.value.end_date
			}
		})
		orders.value = response.data.data || []
	} catch (error) {
		console.error('Error al buscar órdenes:', error)
		alert('Error al buscar órdenes')
	} finally {
		loading.value = false
	}
}

// Cargar datos al montar
onMounted(() => {
	searchOrders()
})

// Modal
const showAlertModal = (order) => {
	selectedOrder.value = order
	showModal.value = true
}

const closeModal = () => {
	showModal.value = false
	selectedOrder.value = null
}

const sendAlert = async () => {
	try {
		await api.post('/alerts/send', {
			order_id: selectedOrder.value.id
		})
		alert('✅ Alerta enviada exitosamente')
		closeModal()
	} catch (error) {
		alert('❌ Error al enviar la alerta')
	}
}

// Logout
const logout = () => {
	localStorage.removeItem('auth_token')
	router.push({ name: 'login' })
}
</script>