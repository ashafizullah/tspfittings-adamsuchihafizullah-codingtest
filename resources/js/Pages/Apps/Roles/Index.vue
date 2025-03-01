<template>

	<Head>
		<title>Roles - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-shield-alt"></i> Roles</span>
							</div>
							<div class="card-body">
								<form @submit.prevent="handleSearch">
									<div class="input-group mb-3">
										<Link href="/apps/roles/create" v-if="hasAnyPermission(['roles.create'])"
											class="btn btn-primary input-group-text"> <i class="fa fa-plus-circle me-2"></i> Tambah</Link>
										<input type="text" class="form-control" v-model="search"
											placeholder="cari berdasarkan nama role...">
										<button class="btn btn-primary input-group-text" type="submit"> <i class="fa fa-search me-2"></i>
											Cari</button>
									</div>
								</form>
                                <table class="table table-striped table-bordered table-hover align-middle">
                                    <thead class="table-light position-sticky top-0">
										<tr>
											<th scope="col">Nama Role</th>
											<th scope="col" style="width:50%">Permissions</th>
											<th scope="col" class="action-column">Aksi</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(role, index) in roles.data" :key="index">
											<td>{{ role.name }}</td>
											<td>
												<span v-for="(permission, index) in role.permissions" :key="index"
													class="badge badge-primary shadow border-0 ms-2 mb-2">
													{{ permission.name }}
												</span>
											</td>
											<td class="text-center">
												<Link :href="`/apps/roles/${role.id}/edit`" v-if="hasAnyPermission(['roles.edit'])"
													class="btn btn-warning btn-sm me-2"><i class="fa fa-pencil-alt me-1"></i> Edit</Link>
												<button @click.prevent="destroy(role.id)" v-if="hasAnyPermission(['roles.delete'])"
													class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
											</td>
										</tr>
									</tbody>
								</table>
                                <PaginationWrapper align="end">
                                    <Pagination
                                        :links="roles.links"
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
//import layout
import LayoutApp from '../../../Layouts/App.vue';

//import component pagination
import Pagination from '../../../Components/Pagination.vue';
import PaginationWrapper from '../../../Components/PaginationWrapper.vue';

//import Heade and Link from Inertia
import { Head, Link } from '@inertiajs/vue3';

//import ref from vue
import { ref } from 'vue';

//import inertia adapter
import { router } from '@inertiajs/vue3'

//import sweet alert2
import Swal from 'sweetalert2';

export default {
	//layout
	layout: LayoutApp,

	//register component
	components: {
		Head,
		Link,
		Pagination
	},

	props: {
		roles: Object,
	},

	setup() {

		//define state search
		const search = ref('' || (new URL(document.location)).searchParams.get('q'));

		//define method search
		const handleSearch = () => {
			Inertia.get('/apps/roles', {

				//send params "q" with value from state "search"
				q: search.value,
			});
		}

		//define method destroy
		const destroy = (id) => {
			Swal.fire({
				title: 'Apakah anda yakin?',
				text: "Data yang terhapus tidak bisa dikembalikan lagi!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, hapus!',
				cancelButtonText: 'Batal'
			})
				.then((result) => {
					if (result.isConfirmed) {

						router.delete(`/apps/roles/${id}`);

						Swal.fire({
							title: 'Terhapus!',
							text: 'Role berhasil dihapus.',
							icon: 'success',
							timer: 2000,
							showConfirmButton: false,
						});
					}
				})
		}

		//return
		return {
			search,
			handleSearch,
			destroy
		}

	}
}
</script>

<style>

</style>
