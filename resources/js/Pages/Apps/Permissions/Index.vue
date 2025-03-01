<template>

	<Head>
		<title>Permissions - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-key"></i> Permissions</span>
							</div>
							<div class="card-body">
								<form @submit.prevent="handleSearch">
									<div class="input-group mb-3">
										<input type="text" class="form-control" v-model="search"
											placeholder="cari berdasarkan nama permissions...">
										<button class="btn btn-primary input-group-text" type="submit"> <i class="fa fa-search me-2"></i>
											Cari</button>
									</div>
								</form>
                                <table class="table table-striped table-bordered table-hover align-middle">
                                    <thead class="table-light position-sticky top-0">
										<tr>
											<th scope="col">Name Permission</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(permission, index) in permissions.data" :key="index">
											<td>{{ permission.name }}</td>
										</tr>
									</tbody>
								</table>
                                <PaginationWrapper align="end">
                                    <Pagination
                                        :links="permissions.links"
                                        :per-page="permissions.per_page"
                                        :total-items="permissions.total"
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

export default {
	//layout
	layout: LayoutApp,

	//register component
	components: {
		Head,
		Link,
		Pagination
	},

	//props
	props: {
		permissions: Object,
	},

	setup() {

		//define state search
		const search = ref('' || (new URL(document.location)).searchParams.get('q'));

		//define method search
		const handleSearch = () => {
			router.get('/apps/permissions', {

				//send params "q" with value from state "search"
				q: search.value,
			});
		}

		//return
		return {
			search,
			handleSearch,
		}

	}
}
</script>

<style>

</style>
