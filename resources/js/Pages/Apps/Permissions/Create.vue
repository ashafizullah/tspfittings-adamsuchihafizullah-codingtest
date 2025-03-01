<template>

	<Head>
		<title>Tambah Kategori Artikel - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-folder"></i> Tambah Kategori Artikel</span>
							</div>
							<div class="card-body">

								<form @submit.prevent="submit">
									<div class="mb-3">
										<label class="fw-bold">Nama Kategori</label>
										<input class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" type="text"
											placeholder="Nama kategori" maxlength="50">
										<small>{{ 50 - form.name.length }} karakter tersisa</small>
									</div>
									<div v-if="errors.name" class="alert alert-danger">
										{{ errors.name }}
									</div>
									<div class="mb-3">
										<label class="fw-bold">Deskripsi</label>
										<textarea class="form-control" v-model="form.description"
											:class="{ 'is-invalid': errors.description }" type="text" rows="4" maxlength="100"></textarea>
										<small>{{ 100 - form.description.length }} karakter tersisa</small>
									</div>
									<div v-if="errors.description" class="alert alert-danger">
										{{ errors.description }}
									</div>
									<div class="row">
										<div class="col-12">
											<button class="btn btn-primary shadow-sm rounded-sm" type="submit">Simpan</button>
											<button class="btn btn-warning shadow-sm rounded-sm ms-3" type="reset">Reset</button>
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
//import layout App
import LayoutApp from '../../../Layouts/App.vue';

//import Heade and Link from Inertia
import { Head, Link } from '@inertiajs/vue3';

//import reactive from vue
import { reactive } from 'vue';

//import inerita adapter
import { router } from '@inertiajs/vue3'

//import sweet alert2
import Swal from 'sweetalert2';

export default {
	//layout
	layout: LayoutApp,

	//register components
	components: {
		Head,
		Link
	},

	//props
	props: {
		errors: Object
	},

	//composition API
	setup() {

		//define form with reactive
		const form = reactive({
			name: '',
			description: ''
		});

		//method "submit"
		const submit = () => {

			//send data to server
			router.post('/apps/categories', {
				//data
				name: form.name,
				description: form.description
			}, {
				onSuccess: () => {
					//show success alert
					Swal.fire({
						title: 'Sukses!',
						text: 'Kategori berhasil disimpan.',
						icon: 'success',
						showConfirmButton: false,
						timer: 2000
					});
				},
			});

		}

		return {
			form,
			submit,
		}

	}
}
</script>
