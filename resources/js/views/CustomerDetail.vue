<template>
	<div class="container mt-4">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<h2>Detalle del Cliente</h2>
			<router-link to="/" class="btn btn-secondary">
				← Volver al Dashboard
			</router-link>
		</div>

		<div v-if="loading" class="text-center py-5">
			<div class="spinner-border text-primary"></div>
		</div>

		<div v-else-if="customer" class="row">
			<div class="col-md-4">
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0">Información Personal</h5>
					</div>
					<div class="card-body">
						<h4>{{ customer.name }}</h4>
						<p class="mb-1"><strong>Email:</strong> {{ customer.email }}</p>
						<p><strong>Teléfono:</strong> {{ customer.phone }}</p>
						<p><strong>Total de Órdenes:</strong> {{ customer.orders?.length || 0 }}</p>
					</div>
				</div>
			</div>

			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<h5 class="mb-0">Historial de Compras</h5>
					</div>
					<div class="card-body">
						<div v-if="customer.orders?.length === 0" class="text-center py-3">
							<p class="text-muted">Este cliente no tiene órdenes registradas</p>
						</div>
						<div v-else class="table-responsive">
							<table class="table table-sm">
								<thead>
									<tr>
										<th>Orden #</th>
										<th>Fecha</th>
										<th>Medicamentos</th>
										<th>Acción</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="order in customer.orders" :key="order.id">
										<td>#{{ order.id }}</td>
										<td>{{ formatDate(order.purchase_date) }}</td>
										<td>{{ order.items?.length || 0 }} items</td>
										<td>
											<router-link :to="{ name: 'order.detail', params: { id: order.id } }"
												class="btn btn-outline-primary btn-sm">
												Ver Orden
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
		alert('Error al cargar el cliente')
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
</script>