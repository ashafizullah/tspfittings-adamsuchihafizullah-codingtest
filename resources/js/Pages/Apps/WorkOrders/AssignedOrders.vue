<template>
	<Head>
		<title>My Assigned Work Orders - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-list-check"></i>&nbsp;My Assigned Work Orders</span>
							</div>
							<div class="card-body">
								<form @submit.prevent="handleSearch">
									<div class="card border-0 rounded-3 shadow mb-4">
										<div class="card-header" @click="showFilter = !showFilter">
											<IconFilter :size="20" stroke-width="1" />&nbsp;
											<span class="font-weight-bold">Filter</span>
										</div>
										<Transition name="slide-fade">
											<div class="card-body" v-if="showFilter">
												<div class="row mb-2">
													<div class="col-12 col-md-4 mb-2 mb-md-0">
														<small>Status:</small>
														<select v-model="status" class="form-control" @change="handleFilter">
															<option value="">All Statuses</option>
															<option v-for="statusOption in statuses" :key="statusOption" :value="statusOption">
																{{ statusOption }}
															</option>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="col">
														<BtnReset @click="resetFilter"/>
													</div>
												</div>
											</div>
										</Transition>
									</div>
								</form>

								<!-- Work Orders Table -->
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover align-middle">
										<thead class="table-light position-sticky top-0">
											<tr>
												<th scope="col">WO Number</th>
												<th scope="col">Product</th>
												<th scope="col">Quantity</th>
												<th scope="col">Deadline</th>
												<th scope="col">Status</th>
												<th scope="col">Created By</th>
												<th scope="col">Updated At</th>
												<th scope="col">Actions</th>
											</tr>
										</thead>
										<tbody>
                                            <tr v-for="(workOrder, index) in workOrders.data" :key="index">
                                                <td>{{ workOrder.work_order_number }}</td>
                                                <td>{{ workOrder.product_name }}</td>
                                                <td>
                                                <div>{{ workOrder.quantity }}</div>
                                                <small class="text-muted">
                                                    Processed: {{ getProcessedQuantity(workOrder.id) || 0 }}
                                                    <br>
                                                    Remaining: {{ Math.max(0, workOrder.quantity - (getProcessedQuantity(workOrder.id) || 0)) }}
                                                </small>
                                                </td>
                                                <td>{{ formatDate(workOrder.production_deadline) }}</td>
                                                <td>
                                                <span :class="getStatusBadgeClass(workOrder.status)">
                                                    {{ workOrder.status }}
                                                </span>
                                                </td>
                                                <td>{{ workOrder.created_by_user ? workOrder.created_by_user.name : 'System' }}</td>
												<td>{{ changeDateTimeFormat(workOrder.updated_at) }}</td>
                                                <td class="text-center">
                                                <div class="btn-group">
                                                    <BtnView :href="`/apps/work-orders/${workOrder.id}/progress`" :permissions="['work_orders.view_assigned']" title="View Progress" />

                                                    <button
                                                    v-if="canUpdateStatus(workOrder.status)"
                                                    class="btn btn-sm btn-info"
                                                    @click="openUpdateStatusModal(workOrder)"
                                                    title="Update Status">
                                                    <i class="fa fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                                </td>
                                            </tr>
                                            <tr v-if="workOrders.data.length === 0">
                                                <td colspan="7" class="text-center py-3">No work orders assigned to you.</td>
                                            </tr>
                                        </tbody>
									</table>
								</div>

								<!-- Pagination -->
								<PaginationWrapper align="end">
									<Pagination
										:links="workOrders.links"
										align="end"
										size="sm"
										:show-compact-on-mobile="true"
										theme="primary"
									/>
								</PaginationWrapper>

								<!-- Update Status Modal -->
								<div v-if="showUpdateModal" class="modal d-block" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title">Update Work Order Status</h5>
												<button type="button" class="close" @click="closeUpdateStatusModal">
													<span aria-hidden="true">&times;</span>
												</button>
											</div>
											<div class="modal-body">
												<form @submit.prevent="submitStatusUpdate">
													<div class="form-group mb-3">
														<label for="quantityChange">Quantity Processed:</label>
														<input id="quantityChange" v-model="updateForm.quantity_change" type="number" min="1" :max="selectedWorkOrder.quantity" class="form-control" required>
														<small class="text-muted">
															Enter the quantity processed in this update.
															<span v-if="getTotalProcessedQuantity() + parseInt(updateForm.quantity_change) >= selectedWorkOrder.quantity">
																This will complete the work order.
															</span>
														</small>
														<div v-if="errors.quantity_change" class="text-danger mt-1">
															{{ errors.quantity_change }}
														</div>
													</div>
													<div class="form-group mb-3">
														<label for="notes">Production Notes (Optional):</label>
														<textarea id="notes" v-model="updateForm.notes" class="form-control" rows="3" placeholder="e.g., Cutting complete, Assembly started..."></textarea>
														<div v-if="errors.notes" class="text-danger mt-1">
															{{ errors.notes }}
														</div>
													</div>
												</form>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" @click="closeUpdateStatusModal">Cancel</button>
												<button type="button" class="btn btn-primary" @click="submitStatusUpdate">Update Status</button>
											</div>
										</div>
									</div>
								</div>
								<div v-if="showUpdateModal" class="modal-backdrop fade show"></div>
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
import {
	IconFilter
} from '@tabler/icons-vue'
import Pagination from '../../../Components/Pagination.vue'
import PaginationWrapper from '../../../Components/PaginationWrapper.vue'
import { Head } from '@inertiajs/vue3'
import { ref, reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import BtnView from '../../../Components/Buttons/BtnView.vue'
import BtnReset from '../../../Components/Buttons/BtnReset.vue'
import { successAlert } from '../../../Composables/useAlert'
import moment from 'moment'
import axios from 'axios'

const props = defineProps({
	workOrders: Object,
	statuses: Array,
	filters: Object,
	errors: Object
});

// Filter state
const status = ref(props.filters.status || '');
const showFilter = ref(true);

// Modal state
const showUpdateModal = ref(false);
const selectedWorkOrder = ref({});
const updateForm = reactive({
	quantity_change: 1,
	notes: '',
});

// Track processed quantities
const processedQuantities = ref({});

// Function to calculate total processed quantity for a work order
const getTotalProcessedQuantity = () => {
	return processedQuantities.value[selectedWorkOrder.value.id] || 0;
};

// Filter handlers
const handleFilter = async () => {
	try {
		const params = {};
		if (status.value) {
			params.status = status.value;
		}
		await router.get('/apps/work-orders/assigned', params);
	} catch (error) {
		console.error(error);
	}
};

const resetFilter = async () => {
	try {
		status.value = '';
		await router.get('/apps/work-orders/assigned');
	} catch (error) {
		console.error(error);
	}
};

// Status update modal handlers
const openUpdateStatusModal = (workOrder) => {
	selectedWorkOrder.value = workOrder;
	updateForm.quantity_change = 1;
	updateForm.notes = '';

	// Initialize processed quantity if not already set
	if (!processedQuantities.value[workOrder.id]) {
		// For work orders that are already In Progress, we need to fetch
		// the current processed quantity from the backend
		if (workOrder.status === 'In Progress') {
			fetchProcessedQuantity(workOrder.id);
		} else {
			processedQuantities.value[workOrder.id] = 0;
		}
	}

	showUpdateModal.value = true;
};

const loadAllProcessedQuantities = () => {
  if (props.workOrders && props.workOrders.data) {
    props.workOrders.data.forEach(workOrder => {
      if (!processedQuantities.value[workOrder.id]) {
        fetchProcessedQuantity(workOrder.id);
      }
    });
  }
};

const getProcessedQuantity = (workOrderId) => {
  return processedQuantities.value[workOrderId] || 0;
};

onMounted(() => {
  loadAllProcessedQuantities();
});

// Function to fetch already processed quantity for In Progress work orders
const fetchProcessedQuantity = async (workOrderId) => {
	try {
		const response = await axios.get(`/apps/work-orders/${workOrderId}/production-steps`);
		let totalProcessed = 0;

		if (response.data && response.data.timeline) {
			// Sum up all quantity_change values from the timeline
			response.data.timeline.forEach(step => {
				if (step.quantity_change) {
					totalProcessed += parseInt(step.quantity_change);
				}
			});
		}

		processedQuantities.value[workOrderId] = totalProcessed;
	} catch (error) {
		console.error("Error fetching processed quantity:", error);
		processedQuantities.value[workOrderId] = 0;
	}
};

const closeUpdateStatusModal = () => {
	showUpdateModal.value = false;
};

const submitStatusUpdate = async () => {
	try {
		// Calculate the total quantity that would be processed after this update
		const currentProcessed = processedQuantities.value[selectedWorkOrder.value.id] || 0;
		const newTotalProcessed = currentProcessed + parseInt(updateForm.quantity_change);

		// Determine the new status based on the total quantity processed
		const newStatus = newTotalProcessed >= selectedWorkOrder.value.quantity ? 'Completed' : 'In Progress';

		// Submit the update with the automatically determined status
		await router.post(`/apps/work-orders/${selectedWorkOrder.value.id}/progress`, {
			...updateForm,
			new_status: newStatus
		}, {
			onSuccess: () => {
				// Update the local processed quantity
				processedQuantities.value[selectedWorkOrder.value.id] = newTotalProcessed;
				closeUpdateStatusModal();
				successAlert('Work Order Progress', 'updated successfully');
			},
		});
	} catch (error) {
		console.error(error);
	}
};

// Helper functions
const formatDate = (dateString) => {
	return moment(dateString).format('MMMM Do, YYYY');
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

const canUpdateStatus = (status) => {
	return ['Pending', 'In Progress'].includes(status);
};

const getAvailableStatusOptions = (currentStatus) => {
	if (currentStatus === 'Pending') {
		return ['In Progress'];
	} else if (currentStatus === 'In Progress') {
		return ['Completed'];
	}
	return [];
};
</script>
