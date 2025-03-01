<template>
	<Head>
		<title>Perbarui Password - {{ title() }}</title>
	</Head>
	<div class="col-md-6">
		<div class="fade-in">
			<div class="text-center mb-4">
				<a href="" class="text-dark text-decoration-none">
                    <img src="/images/logo-trisip.webp" width="400">
                    <h3 class="mt-2 font-weight-bold">Sitem Informasi Work Orders</h3>
                    <h3 class="mt-2 font-weight-bold">TSP Fittings - Adam Suchi Hafizullah</h3>
                    <h3 class="mt-2 font-weight-bold">Coding Test</h3>
				</a>
			</div>
			<div class="card-group">
				<div class="card border-top-purple border-0 shadow-sm rounded-3">
					<div class="card-body">
						<div class="text-start">
							<h5>Perbarui Password</h5>
						</div>
						<hr>
						<div v-if="session.status" class="alert alert-success mt-2">
							{{ session.status }}
						</div>
						<form @submit.prevent="submit">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-envelope"></i>
									</span>
								</div>
								<input class="form-control" v-model="form.email" :class="{ 'is-invalid': errors.email }" type="email"
									placeholder="Masukkan alamat email">
							</div>
							<div v-if="errors.email" class="alert alert-danger">
								{{ errors.email }}
							</div>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-lock"></i>
									</span>
								</div>
								<input class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }"
									type="password" placeholder="Masukkan password baru">
							</div>
							<div v-if="errors.password" class="alert alert-danger">
								{{ errors.password }}
							</div>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-lock"></i>
									</span>
								</div>
								<input class="form-control" v-model="form.password_confirmation"
									:class="{ 'is-invalid': errors.password_confirmation }" type="password"
									placeholder="Masukkan kembali password baru">
							</div>
							<div class="row">
								<div class="col-12">
									<button class="btn btn-primary shadow-sm rounded-sm px-4 w-100" type="submit">Perbarui
										Password</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
//import layout
import LayoutAuth from '../../Layouts/Auth.vue';

//import reactive
import { reactive } from 'vue';

//inertia adapter
import { router } from '@inertiajs/vue3'

//import Heade and useForm from Inertia
import {
	Head,
	Link,
} from '@inertiajs/vue3';

export default {

	//layout
	layout: LayoutAuth,

	//register component
	components: {
		Head,
		Link
	},

	props: {
		errors: Object,
		route: Object,
		session: Object,
	},

	//define composition API
	setup(props) {

		//define form state
		const form = reactive({
			email: props.route.query.email,
			password: '',
			password_confirmation: '',
			token: props.route.params.token,
		});

		//submit method
		const submit = () => {

			//send data to server
			router.post('/reset-password', {

				//data
				email: form.email,
				password: form.password,
				password_confirmation: form.password_confirmation,
				token: form.token,
			})
		}

		//return form state and submit method
		return {
			form,
			submit,
		};

	}

}
</script>

<style></style>
