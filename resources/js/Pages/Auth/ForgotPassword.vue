<template>
    <LayoutAuth>
        <Head>
            <title>Lupa Password - {{ title() }}</title>
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
                                <h5>Reset Password</h5>
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
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary shadow-sm rounded-sm px-4 w-100 mb-3" type="submit">Kirim Link Reset
                                            Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </LayoutAuth>
</template>

<script setup>
//import layout
import LayoutAuth from '../../Layouts/Auth.vue';

//import reactive
import { reactive } from 'vue';

//inertia adapter
import { router, Head, Link } from '@inertiajs/vue3';

//define form state
const form = reactive({
    email: '',
});

//props
const props = defineProps({
    errors: Object,
    session: Object
});

//submit method
const submit = () => {
    router.post('/forgot-password', {
        email: form.email
    });
};
</script>
