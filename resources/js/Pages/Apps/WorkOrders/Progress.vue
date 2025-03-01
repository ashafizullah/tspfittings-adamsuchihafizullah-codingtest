<template>
	<Head>
		<title>Work Order Progress - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-tasks"></i>&nbsp;Work Order Progress</span>
							</div>
							<div class="card-body">
								<!-- Work Order Details Card -->
								<div class="card border-0 rounded-3 shadow mb-4">
									<div class="card-header bg-light">
										<span class="font-weight-bold">Work Order Details: {{ workOrder.work_order_number }}</span>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<table class="table table-borderless">
													<tbody>
														<tr>
															<td class="font-weight-bold">Product:</td>
															<td>{{ workOrder.product_name }}</td>
														</tr>
														<tr>
															<td class="font-weight-bold">Quantity:</td>
															<td>{{ workOrder.quantity }}</td>
														</tr>
														<tr>
															<td class="font-weight-bold">Deadline:</td>
															<td>{{ formatDate(workOrder.production_deadline) }}</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="col-md-6">
												<table class="table table-borderless">
													<tbody>
														<tr>
															<td class="font-weight-bold">Operator:</td>
															<td>{{ workOrder.operator ? workOrder.operator.name : 'Not Assigned' }}</td>
														</tr>
														<tr>
															<td class="font-weight-bold">Current Status:</td>
															<td>
																<span :class="getStatusBadgeClass(workOrder.status)">
																	{{ workOrder.status }}
																</span>
															</td>
														</tr>
														<tr>
															<td class="font-weight-bold">Created By:</td>
															<td>{{ workOrder.created_by_user ? workOrder.created_by_user.name : 'System' }}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

								<!-- Update Status Panel (visible only for operators and appropriate status) -->
								<div v-if="showStatusUpdateForm" class="card border-0 rounded-3 shadow mb-4">
									<div class="card-header bg-light">
										<span class="font-weight-bold">Update Work Order Status</span>
									</div>
									<div class="card-body">
										<form @submit.prevent="submitProgressUpdate">
											<div class="row">
												<div class="col-md-4 mb-3">
													<Label name="New Status" required />
													<select v-model="form.new_status" class="form-control" required>
														<option v-for="status in availableStatusOptions" :key="status" :value="status">
															{{ status }}
														</option>
													</select>
													<div v-if="errors.new_status" class="text-danger mt-1">
														{{ errors.new_status }}
													</div>
												</div>
												<div class="col-md-4 mb-3">
                                                    <Label name="Quantity Change" required />
                                                    <input v-model="form.quantity_change" type="number" min="0" class="form-control" required>
                                                    <small class="text-muted">
                                                        Original quantity: {{ workOrder.quantity }} |
                                                        Processed so far: {{ processedQuantity }} |
                                                        Remaining: {{ Math.max(0, workOrder.quantity - processedQuantity) }}
                                                    </small>
                                                    <div v-if="errors.quantity_change" class="text-danger mt-1">
                                                        {{ errors.quantity_change }}
                                                    </div>
                                                </div>
												<div class="col-md-4 mb-3">
													<Label name="Notes (Optional)" />
													<input v-model="form.notes" type="text" class="form-control" placeholder="e.g., Cutting complete, Assembly started...">
													<div v-if="errors.notes" class="text-danger mt-1">
														{{ errors.notes }}
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col d-flex gap-2">
													<BtnSave />
													<BtnCancel :href="`/apps/work-orders/assigned`" />
												</div>
											</div>
										</form>
									</div>
								</div>

								<!-- Progress Timeline -->
								<div class="card border-0 rounded-3 shadow">
									<div class="card-header bg-light">
										<span class="font-weight-bold">Progress Timeline</span>
									</div>
									<div class="card-body">
										<div v-if="progressLogs.length === 0" class="text-center py-4">
											<p class="text-muted">No progress updates recorded yet.</p>
										</div>
										<div v-else class="timeline">
											<div v-for="(log, index) in progressLogs" :key="index" class="timeline-item">
												<div class="timeline-marker" :class="getTimelineMarkerClass(log.new_status)"></div>
												<div class="timeline-content">
													<div class="d-flex justify-content-between align-items-center">
														<h5 class="mb-0">
															<span :class="getStatusBadgeClass(log.new_status)">
																{{ log.new_status }}
															</span>
														</h5>
														<span class="text-muted small">{{ formatDateTime(log.created_at) }}</span>
													</div>
													<p v-if="log.quantity_change" class="mb-1">
														Quantity processed: <strong>{{ log.quantity_change }}</strong>
													</p>
													<p v-if="log.notes" class="mb-1">
														Notes: {{ log.notes }}
													</p>
													<p class="text-muted small mb-0">
														Updated by: {{ log.updated_by_user ? log.updated_by_user.name : 'System' }}
													</p>
												</div>
											</div>
										</div>
									</div>
								</div>
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
import { Head, usePage } from '@inertiajs/vue3'
import { reactive, computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import BtnSave from '../../../Components/Buttons/BtnSave.vue'
import BtnCancel from '../../../Components/Buttons/BtnCancel.vue'
import Label from '../../../Components/Label.vue'
import { successAlert } from '../../../Composables/useAlert'
import moment from 'moment'

const props = defineProps({
	workOrder: Object,
	progressLogs: Array,
	errors: Object
});

// Form for updating work order progress
const form = reactive({
	new_status: '',
	quantity_change: 0,
	notes: '',
});

// Computed property to determine available status options based on current status
const availableStatusOptions = computed(() => {
    const currentStatus = props.workOrder.status;
    if (currentStatus === 'Pending') {
        return ['In Progress', 'Completed'];
    } else if (currentStatus === 'In Progress') {
        return ['In Progress', 'Completed']; // Allow selecting In Progress again
    }
    return [];
});

// Computed property to determine if status update form should be shown
const page = usePage();

const showStatusUpdateForm = computed(() => {
	// Only show form for operators assigned to this work order
	// And only if the status is Pending or In Progress
	const isAssignedOperator = props.workOrder.operator_id === page.props.auth.user.id;
	const canUpdateStatus = ['Pending', 'In Progress'].includes(props.workOrder.status);
	return isAssignedOperator && canUpdateStatus;
});

const processedQuantity = computed(() => {
  // Sum all quantity changes from the progress logs
  return props.progressLogs.reduce((total, log) => {
    return total + (log.quantity_change || 0);
  }, 0);
});

// Handle form submission
const submitProgressUpdate = async () => {
	try {
		await router.post(`/apps/work-orders/${props.workOrder.id}/progress`, form, {
			onSuccess: () => {
				successAlert('Work Order Progress', 'updated successfully');
			},
		});
	} catch (error) {
		console.error(error);
	}
};

// Helper functions for formatting and styling
const formatDate = (dateString) => {
	return moment(dateString).format('MMMM Do, YYYY');
};

const formatDateTime = (dateTimeString) => {
	return moment(dateTimeString).format('MMMM Do, YYYY, h:mm a');
};

const getStatusBadgeClass = (status) => {
	switch (status) {
		case 'Pending':
			return 'badge badge-warning shadow border-0';
		case 'In Progress':
			return 'badge badge-info shadow border-0';
		case 'Completed':
			return 'badge badge-success shadow border-0';
		case 'Canceled':
			return 'badge badge-danger shadow border-0';
		default:
			return 'badge badge-secondary shadow border-0';
	}
};

const getTimelineMarkerClass = (status) => {
	switch (status) {
		case 'Pending':
			return 'bg-warning';
		case 'In Progress':
			return 'bg-info';
		case 'Completed':
			return 'bg-success';
		case 'Canceled':
			return 'bg-danger';
		default:
			return 'bg-secondary';
	}
};
</script>

<style scoped>
.timeline {
	position: relative;
	padding-left: 25px;
}

.timeline-item {
	position: relative;
	padding-bottom: 1.5rem;
	border-left: 2px solid #e9ecef;
}

.timeline-item:last-child {
	padding-bottom: 0;
}

.timeline-marker {
	position: absolute;
	left: -9px;
	width: 16px;
	height: 16px;
	border-radius: 50%;
	border: 2px solid #fff;
}

.timeline-content {
	padding-left: 15px;
}
</style>
