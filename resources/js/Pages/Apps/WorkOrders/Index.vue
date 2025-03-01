<template>
	<Head>
		<title>Work Orders - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-folder"></i>&nbsp;Work Orders</span>
							</div>
							<div class="card-body">
								<form @submit.prevent="handleSearch">
									<div class="input-group mb-3">
										<BtnAdd href="/apps/work-orders/create" :permissions="['work_orders.create']" />
									</div>
									<div class="card border-0 rounded-3 shadow">
									<div class="card-header" @click="showFilter = !showFilter">
										<IconFilter :size="20" stroke-width="1" />&nbsp;
										<span class="font-weight-bold">Filter</span>
									</div>
									<Transition name="slide-fade">
                                        <div class="card-body" v-if="showFilter">
                                            <div class="row mb-2">
                                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                                    <small>Product Name:</small>
                                                    <input type="text" v-model="product_name" class="form-control" placeholder="Search product name..." @input="handleFilter" />
                                                </div>
                                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                                    <small>Status:</small>
                                                    <select v-model="status" class="form-control" @change="handleFilter">
                                                        <option value="">All Statuses</option>
                                                        <option v-for="statusOption in statuses" :key="statusOption" :value="statusOption">
                                                            {{ statusOption }}
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                                    <small>Operator:</small>
                                                    <Multiselect v-model="operator_id" :options="operators" valueProp="id" label="name" trackBy="name" :searchable="true" placeholder="Select operator:" @select="handleFilter" />
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                                    <small>From Date:</small>
                                                    <input type="date" v-model="from_date" class="form-control" @change="handleFilter" />
                                                </div>
                                                <div class="col-12 col-md-4 mb-2 mb-md-0">
                                                    <small>To Date:</small>
                                                    <input type="date" v-model="to_date" class="form-control" @change="handleFilter" />
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
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover align-middle">
										<thead class="table-light position-sticky top-0">
											<tr>
												<th scope="col">Work Order #</th>
												<th scope="col">Product Name</th>
												<th scope="col">Quantity</th>
												<th scope="col">Deadline</th>
												<th scope="col">Status</th>
												<th scope="col">Operator</th>
												<th scope="col">Created By</th>
												<th scope="col">Updated By</th>
												<th scope="col">Created At</th>
												<th scope="col">Updated At</th>
												<th scope="col" v-if="hasAnyPermission(['work_orders.edit', 'work_orders.delete'])" class="action-column">Actions</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(workOrder, index) in workOrders.data" :key="index">
												<td>{{ workOrder.work_order_number }}</td>
												<td>{{ workOrder.product_name }}</td>
												<td>{{ workOrder.quantity }}</td>
												<td>{{ formatDate(workOrder.production_deadline) }}</td>
												<td>
													<span :class="getStatusBadgeClass(workOrder.status)">
														{{ workOrder.status }}
													</span>
												</td>
												<td>{{ workOrder.operator.name }}</td>
												<td>{{ workOrder.created_by_user.name }}</td>
												<td>{{ workOrder.updated_by_user.name }}</td>
												<td>{{ changeDateTimeFormat(workOrder.created_at) }}</td>
												<td>{{ changeDateTimeFormat(workOrder.updated_at) }}</td>
												<td class="text-center" v-if="hasAnyPermission(['work_orders.edit', 'work_orders.delete'])">
                                                    <BtnView v-if="hasAnyPermission(['work_orders.index'])" :href="`/apps/work-orders/${workOrder.id}/progress`" :permissions="['work_orders.index']" :iconOnly="false" label="Lihat Progres"/>
													<BtnEdit v-if="hasAnyPermission(['work_orders.edit'])" :href="`/apps/work-orders/${workOrder.id}/edit`" :permissions="['work_orders.edit']" />
													<BtnDelete v-if="hasAnyPermission(['work_orders.delete'])" model="Work Order" route="work-orders" :id="workOrder.id" :permissions="['work_orders.delete']" />
												</td>
											</tr>
										</tbody>
									</table>
								</div>
                                <PaginationWrapper align="end">
                                    <Pagination
                                        :links="workOrders.links"
                                        align="end"
                                        size="sm"
                                        :show-compact-on-mobile="true"
                                        theme="primary"
                                    />
                                </PaginationWrapper>
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
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import BtnAdd from '../../../Components/Buttons/BtnAdd'
import BtnEdit from '../../../Components/Buttons/BtnEdit'
import BtnDelete from '../../../Components/Buttons/BtnDelete'
import BtnReset from '../../../Components/Buttons/BtnReset.vue'
import BtnView from '../../../Components/Buttons/BtnView.vue'
import Multiselect from '@vueform/multiselect'

defineProps({
	workOrders: Object,
	operators: Array,
	statuses: Array,
	isProductionManager: Boolean,
});

const product_name = ref('' || (new URL(document.location)).searchParams.get('product_name'));
const status = ref('' || (new URL(document.location)).searchParams.get('status'));
const operator_id = ref('' || (new URL(document.location)).searchParams.get('operator_id'));
const from_date = ref('' || (new URL(document.location)).searchParams.get('from_date'));
const to_date = ref('' || (new URL(document.location)).searchParams.get('to_date'));
const showFilter = ref(true);

const handleFilter = async () => {
	try {
		const params = {};

		if (product_name.value) params.product_name = product_name.value;
		if (status.value) params.status = status.value;
		if (operator_id.value) params.operator_id = operator_id.value;
		if (from_date.value) params.from_date = from_date.value;
		if (to_date.value) params.to_date = to_date.value;

		await router.get('/apps/work-orders', params);
	} catch (error) {
		console.log(error)
	}
};

const resetFilter = async () => {
	try {
		product_name.value = '';
		status.value = '';
		operator_id.value = '';
		from_date.value = '';
		to_date.value = '';

		await router.get('/apps/work-orders');
	} catch (error) {
		console.log(error)
	}
};

const formatDate = (dateString) => {
	const date = new Date(dateString);
	return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const getStatusBadgeClass = (status) => {
	const classes = {
		'Pending': 'badge badge-warning shadow border-0',
		'In Progress': 'badge badge-info shadow border-0',
		'Completed': 'badge badge-success shadow border-0',
		'Canceled': 'badge badge-danger shadow border-0'
	};

	return classes[status] || 'badge badge-secondary shadow border-0';
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
