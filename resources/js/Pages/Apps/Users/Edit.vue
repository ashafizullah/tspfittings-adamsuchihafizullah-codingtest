<template>
	<Head>
		<title>Perbarui User - {{ title() }}</title>
	</Head>
	<main class="c-main">
		<div class="container-fluid">
			<div class="fade-in">
				<div class="row">
					<div class="col-md-12">
						<div class="card border-0 rounded-3 shadow border-top-purple">
							<div class="card-header">
								<span class="font-weight-bold"><i class="fa fa-user"></i> Perbarui User</span>
							</div>
							<div class="card-body">
								<form @submit.prevent="submit">
									<div class="row">
										<div class="col-md-6">
											<div class="mb-3">
												<Label name="Nama Lengkap" required />
												<input class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" type="text"
													placeholder="Name">
											</div>
											<div v-if="errors.name" class="alert alert-danger">
												{{ errors.name }}
											</div>
										</div>
										<div class="col-md-6">
											<div class="mb-3">
												<Label name="Username" required />
												<input class="form-control" v-model="form.username" :class="{ 'is-invalid': errors.username }"
													type="text" placeholder="Username">
											</div>
											<div v-if="errors.username" class="alert alert-danger">
												{{ errors.username }}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="mb-3">
												<Label name="Email" required />
												<input class="form-control" v-model="form.email" :class="{ 'is-invalid': errors.email }"
													type="email" placeholder="Alamat email">
											</div>
											<div v-if="errors.email" class="alert alert-danger">
												{{ errors.email }}
											</div>
										</div>
										<div class="col-md-6">
											<div class="mb-3">
												<Label name="No. HP/WA" required />
												<input class="form-control" v-model="form.no_hp" :class="{ 'is-invalid': errors.no_hp }"
													type="number" placeholder="No. HP/WA">
											</div>
											<div v-if="errors.no_hp" class="alert alert-danger">
												{{ errors.no_hp }}
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="mb-3">
												<Label name="Password" required />
												<input class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }"
													type="password" placeholder="Password">
											</div>
											<div v-if="errors.password" class="alert alert-danger">
												{{ errors.password }}
											</div>
										</div>
										<div class="col-md-6">
											<div class="mb-3">
												<Label name="Confirmation Password" required />
												<input class="form-control" v-model="form.password_confirmation" type="password"
													placeholder="Konfirmasi password">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="mb-3">
												<Label name="Roles" required />
												<br>
												<div class="form-check form-check-inline" v-for="(role, index) in roles" :key="index">
													<input class="form-check-input" type="checkbox" v-model="form.roles" :value="role.name"
														:id="`check-${role.id}`">
													<label class="form-check-label" :for="`check-${role.id}`">{{ role.name }}</label>
												</div>
											</div>
											<div v-if="errors.roles" class="alert alert-danger">
												{{ errors.roles }}
											</div>
										</div>
									</div>
									<div class="row">
                                        <div class="d-flex gap-2">
											<BtnSave />
											<BtnCancel href="/apps/users" />
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
import { reactive, ref, watch, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import BtnSave from '../../../Components/Buttons/BtnSave.vue'
import BtnCancel from '../../../Components/Buttons/BtnCancel.vue'
import Label from '../../../Components/Label.vue'
import { successAlert } from '../../../Composables/useAlert'
import Multiselect from '@vueform/multiselect'
import axios from 'axios'

const props = defineProps({
	errors: Object,
	user: Object,
	roles: Array,
})

const form = reactive({
	name: props.user.name,
	username: props.user.username,
	no_hp: props.user.no_hp,
	email: props.user.email,
	password: '',
	password_confirmation: '',
	roles: props.user.roles.map(obj => obj.name),
});

const submit = async () => {
	try {
		await router.put(`/apps/users/${props.user.id}`, {
			name: form.name,
			username: form.username,
			no_hp: form.no_hp,
			email: form.email,
			password: form.password,
			password_confirmation: form.password_confirmation,
			roles: form.roles
		}, {
			onSuccess: () => {
				successAlert('User', 'diperbarui')
			},
		})
	} catch (error) {
		console.log(error)
	}
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
