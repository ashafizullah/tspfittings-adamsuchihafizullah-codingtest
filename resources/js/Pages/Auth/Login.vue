<template>
    <LayoutAuth>
        <Head>
            <title>Login Administrator - {{ title() }}</title>
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
                                <h5>LOGIN ADMINISTRATOR</h5>
                                <p class="text-muted">Silakan masukkan email dan password</p>
                                <p class="text-muted">administrator: password</p>
                                <p class="text-muted">production.manager: password</p>
                                <p class="text-muted">operator: password</p>
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
                                    <input class="form-control" v-model="form.username" :class="{ 'is-invalid': errors.username }" type="text"
                                        placeholder="Username">
                                </div>
                                <div v-if="errors.username" class="alert alert-danger">
                                    {{ errors.username }}
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-lock"></i>
                                        </span>
                                    </div>
                                    <input class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }"
                                        type="password" placeholder="Password">
                                </div>
                                <div v-if="errors.password" class="alert alert-danger">
                                    {{ errors.password }}
                                </div>
                                <div class="row">
                                    <!-- <div class="col-12 mb-3 text-end">
                                        <Link class="forgot" href="/forgot-password">Lupa password?</Link>
                                    </div> -->
                                    <div class="col-12">
                                        <button class="btn btn-primary shadow-sm rounded-sm px-4 w-100" type="submit">
                                            <IconLogin :size="20" stroke-width="1"/>&nbsp;Masuk
                                        </button>
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
import {
    IconLogin,
} from '@tabler/icons-vue'
import LayoutAuth from '../../Layouts/Auth.vue';
import { reactive } from 'vue';
import { router, Head, Link } from '@inertiajs/vue3';

defineProps({
    errors: Object,
    session: Object
});

const form = reactive({
    username: '',
    password: '',
});

const submit = () => {
    router.post('/login', {
        username: form.username,
        password: form.password,
    });
}
</script>
