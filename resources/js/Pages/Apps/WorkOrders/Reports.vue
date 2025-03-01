<template>
	<Head>
		<title>Production Reports - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple mb-4">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-chart-bar"></i>&nbsp;Product Status Summary</span>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover align-middle">
										<thead class="table-light position-sticky top-0">
											<tr>
												<th scope="col">Product</th>
												<th scope="col" class="text-center">Pending Quantity</th>
												<th scope="col" class="text-center">In Progress Quantity</th>
												<th scope="col" class="text-center">Completed Quantity</th>
												<th scope="col" class="text-center">Canceled Quantity</th>
												<th scope="col" class="text-center">Total Requested</th>
												<th scope="col" class="text-center">Total Processed</th>
												<th scope="col" class="text-center">Completion Rate</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(summary, index) in statusSummary" :key="index">
												<td>{{ summary.product_name }}</td>
												<td class="text-center">{{ summary.pending_quantity }}</td>
												<td class="text-center">{{ summary.in_progress_quantity }}</td>
												<td class="text-center">{{ summary.completed_quantity }}</td>
												<td class="text-center">{{ summary.canceled_quantity }}</td>
												<td class="text-center font-weight-bold">{{ getTotalRequestedQuantity(summary) }}</td>
												<td class="text-center font-weight-bold">{{ getProcessedQuantity(summary) }}</td>
												<td class="text-center">
													<div class="progress">
														<div class="progress-bar" role="progressbar"
															:class="getProgressBarClass(getCompletionRate(summary))"
															:style="`width: ${getCompletionRate(summary)}%;`"
															:aria-valuenow="getCompletionRate(summary)"
															aria-valuemin="0"
															aria-valuemax="100">
															{{ getCompletionRate(summary) }}%
														</div>
													</div>
												</td>
											</tr>
											<tr v-if="statusSummary.length === 0">
												<td colspan="8" class="text-center py-3">No products found.</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="card border-0 rounded-3 shadow border-top-purple mb-4">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-users-cog"></i>&nbsp;Operator Performance</span>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover align-middle">
										<thead class="table-light position-sticky top-0">
											<tr>
												<th scope="col">Operator</th>
												<th scope="col">Product</th>
												<th scope="col" class="text-center">Requested Quantity</th>
												<th scope="col" class="text-center">Completed Quantity</th>
												<th scope="col" class="text-center">Completion %</th>
												<th scope="col" class="text-center">Latest Completion Date</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(performance, index) in operatorPerformance" :key="index">
												<td>{{ performance.operator_name }}</td>
												<td>{{ performance.product_name }}</td>
												<td class="text-center">{{ performance.requested_quantity }}</td>
												<td class="text-center">{{ performance.completed_quantity }}</td>
												<td class="text-center">
													<div class="progress">
														<div class="progress-bar" role="progressbar"
															:class="getProgressBarClass(getOperatorCompletionRate(performance))"
															:style="`width: ${getOperatorCompletionRate(performance)}%;`"
															:aria-valuenow="getOperatorCompletionRate(performance)"
															aria-valuemin="0"
															aria-valuemax="100">
															{{ getOperatorCompletionRate(performance) }}%
														</div>
													</div>
												</td>
												<td class="text-center">{{ formatDate(performance.latest_completion_date) }}</td>
											</tr>
											<tr v-if="operatorPerformance.length === 0">
												<td colspan="6" class="text-center py-3">No operator performance data found.</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-clipboard-check"></i>&nbsp;Recently Completed Work Orders</span>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover align-middle">
										<thead class="table-light position-sticky top-0">
											<tr>
												<th scope="col">WO Number</th>
												<th scope="col">Product</th>
												<th scope="col">Operator</th>
												<th scope="col" class="text-center">Requested Quantity</th>
												<th scope="col" class="text-center">Completed Quantity</th>
												<th scope="col" class="text-center">Created Date</th>
												<th scope="col" class="text-center">Completion Date</th>
												<th scope="col" class="text-center">Duration (Days)</th>
												<th scope="col" class="text-center">Hours to Complete</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(workOrder, index) in recentlyCompletedOrders" :key="index">
												<td>{{ workOrder.work_order_number }}</td>
												<td>{{ workOrder.product_name }}</td>
												<td>{{ workOrder.operator_name }}</td>
												<td class="text-center">{{ workOrder.requested_quantity }}</td>
												<td class="text-center">{{ workOrder.completed_quantity }}</td>
												<td class="text-center">{{ formatDate(workOrder.created_at) }}</td>
												<td class="text-center">{{ formatDate(workOrder.completed_at) }}</td>
												<td class="text-center">{{ calculateDuration(workOrder.created_at, workOrder.completed_at) }}</td>
												<td class="text-center">{{ workOrder.completion_hours }}</td>
											</tr>
											<tr v-if="recentlyCompletedOrders.length === 0">
												<td colspan="9" class="text-center py-3">No recently completed work orders found.</td>
											</tr>
										</tbody>
									</table>
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
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import moment from 'moment';

const props = defineProps({
	statusSummary: Array,
	operatorPerformance: Array,
	recentlyCompletedOrders: Array
});

// Helper functions for the product status summary
const getTotalRequestedQuantity = (summary) => {
	return (
		parseInt(summary.pending_quantity || 0) +
		parseInt(summary.in_progress_quantity || 0) +
		parseInt(summary.completed_quantity || 0) +
		parseInt(summary.canceled_quantity || 0)
	);
};

const getProcessedQuantity = (summary) => {
	return parseInt(summary.completed_quantity || 0);
};

const getCompletionRate = (summary) => {
	const total = getTotalRequestedQuantity(summary);
	const completed = getProcessedQuantity(summary);
	return total > 0 ? Math.round((completed / total) * 100) : 0;
};

// Helper functions for operator performance
const getOperatorCompletionRate = (performance) => {
	const requested = parseInt(performance.requested_quantity || 0);
	const completed = parseInt(performance.completed_quantity || 0);
	return requested > 0 ? Math.round((completed / requested) * 100) : 0;
};

// Format dates
const formatDate = (dateString) => {
	return dateString ? moment(dateString).format('MMMM Do, YYYY') : 'N/A';
};

// Calculate duration between two dates in days
const calculateDuration = (startDate, endDate) => {
	if (!startDate || !endDate) return 'N/A';
	const start = moment(startDate);
	const end = moment(endDate);
	return end.diff(start, 'days');
};

// Get appropriate color class for progress bars
const getProgressBarClass = (percentage) => {
	if (percentage < 25) return 'bg-danger';
	if (percentage < 50) return 'bg-warning';
	if (percentage < 75) return 'bg-info';
	return 'bg-success';
};
</script>

<style scoped>
.progress {
	height: 20px;
	font-size: 0.75rem;
	font-weight: bold;
	color: white;
}
</style>
