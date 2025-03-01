<template>
	<Head>
		<title>Edit Work Order - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-folder"></i> Edit Work Order</span>
							</div>
							<div class="card-body">
								<form @submit.prevent="submit">
									<div class="mb-3">
										<Label name="Work Order Number" />
										<input class="form-control" v-model="form.work_order_number" type="text" disabled>
									</div>

									<div class="mb-3">
										<Label name="Product Name" required />
										<input class="form-control" v-model="form.product_name" :class="{ 'is-invalid': errors.product_name }" type="text" placeholder="Enter product name">
									</div>
									<div v-if="errors.product_name" class="alert alert-danger">
										{{ errors.product_name }}
									</div>

									<div class="mb-3">
										<Label name="Quantity" required />
										<input class="form-control" v-model="form.quantity" :class="{ 'is-invalid': errors.quantity }" type="number" min="1" placeholder="Enter quantity">
									</div>
									<div v-if="errors.quantity" class="alert alert-danger">
										{{ errors.quantity }}
									</div>

									<div class="mb-3">
										<Label name="Production Deadline" required />
										<input class="form-control" v-model="form.production_deadline" :class="{ 'is-invalid': errors.production_deadline }" type="datetime-local">
									</div>
									<div v-if="errors.production_deadline" class="alert alert-danger">
										{{ errors.production_deadline }}
									</div>

									<div class="mb-3">
										<Label name="Status" required />
										<select class="form-control" v-model="form.status" :class="{ 'is-invalid': errors.status }">
											<option v-for="statusOption in statusOptions" :key="statusOption" :value="statusOption">
												{{ statusOption }}
											</option>
										</select>
									</div>
									<div v-if="errors.status" class="alert alert-danger">
										{{ errors.status }}
									</div>

									<div class="mb-3">
										<Label name="Assign Operator" required />
										<Multiselect v-model="form.operator_id" :options="operators" valueProp="id" label="name"
											trackBy="name" :searchable="true" placeholder="Select an operator:" />
									</div>
									<div v-if="errors.operator_id" class="alert alert-danger">
										{{ errors.operator_id }}
									</div>

									<div class="row">
                                        <div class="d-flex gap-2">
											<BtnSave />
											<BtnCancel href="/apps/work-orders" />
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</template>

<script>
import LayoutApp from '../../../Layouts/App.vue'

export default {
	layout: LayoutApp
};
</script>

<script setup>
import { Head } from '@inertiajs/vue3'
import { reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import BtnSave from '../../../Components/Buttons/BtnSave.vue'
import BtnCancel from '../../../Components/Buttons/BtnCancel.vue'
import Label from '../../../Components/Label.vue'
import { successAlert } from '../../../Composables/useAlert'
import Multiselect from '@vueform/multiselect'

const props = defineProps({
	errors: Object,
	workOrder: Object,
	operators: Array,
});

const statusOptions = ['Pending', 'In Progress', 'Completed', 'Canceled'];

const form = reactive({
	work_order_number: props.workOrder.work_order_number,
	product_name: props.workOrder.product_name,
	quantity: props.workOrder.quantity,
	production_deadline: '',
	status: props.workOrder.status,
	operator_id: props.workOrder.operator_id,
});

onMounted(() => {
	// Format the date for the datetime-local input
	if (props.workOrder.production_deadline) {
		const date = new Date(props.workOrder.production_deadline);
		form.production_deadline = date.toISOString().slice(0, 16);
	}
});

const submit = async () => {
	try {
		await router.put(`/apps/work-orders/${props.workOrder.id}`, {
			product_name: form.product_name,
			quantity: form.quantity,
			production_deadline: form.production_deadline,
			status: form.status,
			operator_id: form.operator_id,
		}, {
			onSuccess: () => {
				successAlert('Work order', 'updated successfully')
			}
		});
	} catch (error) {
		console.log(error);
	}
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
