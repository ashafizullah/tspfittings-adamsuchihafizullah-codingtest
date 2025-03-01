<template>
	<Head>
		<title>Users - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-users"></i> Users</span>
							</div>
							<div class="card-body">
								<form @submit.prevent="handleSearch">
									<div class="input-group mb-3">
										<BtnAdd href="/apps/users/create" :permissions="['users.create']" />
										<input type="text" class="form-control" v-model="search" placeholder="cari berdasarkan nama...">
										<BtnSearch />
									</div>
								</form>
								<div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover align-middle">
                                        <thead class="table-light position-sticky top-0">
											<tr>
												<th scope="col">Nama</th>
												<th scope="col">Username</th>
												<th scope="col">No. HP/WA</th>
												<th scope="col">Email</th>
												<th scope="col">Roles</th>
												<th scope="col" class="action-column">Aksi</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(user, index) in users.data" :key="index">
												<td>{{ user.name }}</td>
												<td>{{ user.username }}</td>
												<td>{{ user.no_hp }}</td>
												<td>{{ user.email }}</td>
												<td>
													<span v-for="(role, index) in user.roles" :key="index"
														class="badge badge-primary shadow border-0 ms-2 mb-2">
														{{ role.name }}
													</span>
												</td>
												<td class="text-center">
													<BtnEdit :href="`/apps/users/${user.id}/edit`" :permissions="['users.edit']" />
													<BtnDelete model="User" route="users" :id="user.id" :permissions="['users.delete']" />
												</td>
											</tr>
										</tbody>
									</table>
								</div>
                                <PaginationWrapper align="end">
                                    <Pagination
                                        :links="users.links"
                                        :per-page="users.per_page"
                                        :total-items="users.total"
                                        align="end"
                                        size="sm"
                                        :show-compact-on-mobile="true"
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
import Pagination from '../../../Components/Pagination.vue'
import PaginationWrapper from '../../../Components/PaginationWrapper.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'
import BtnAdd from '../../../Components/Buttons/BtnAdd'
import BtnSearch from '../../../Components/Buttons/BtnSearch.vue'
import BtnEdit from '../../../Components/Buttons/BtnEdit'
import BtnDelete from '../../../Components/Buttons/BtnDelete'

defineProps({
	users: Object
})

const search = ref('' || (new URL(document.location)).searchParams.get('q'));
const handleSearch = () => {
	router.get('/apps/users', {
		q: search.value,
	});
}
</script>

<style></style>
