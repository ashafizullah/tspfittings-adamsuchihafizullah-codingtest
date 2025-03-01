<template>
    <Head>
        <title>Welcome - Work Order Management System</title>
    </Head>

    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row justify-content-center align-items-center min-vh-75">
                    <div class="col-md-8">
                        <div class="card border-0 rounded-3 shadow-lg">
                            <div class="card-body p-5 text-center">
                                <img src="/images/logo-trisip.webp" alt="Tri Sinar Purnama" class="img-fluid mb-4" style="max-height: 200px;">

                                <h1 class="display-4 fw-bold mb-3">Selamat Datang</h1>
                                <h2 class="h3 text-secondary mb-4">Sistem Manajemen Work Order<br>untuk Manufaktur</h2>

                                <div class="border-top my-4"></div>

                                <p class="lead text-muted">
                                    Platform digital terpadu untuk pembuatan, pelacakan, dan pembaruan work order
                                    dengan role-based access control. Tingkatkan efisiensi produksi dengan
                                    manajemen work order yang lebih baik.
                                </p>

                                <div class="d-flex justify-content-center gap-3 mt-4">
                                    <Link href="/apps/work-orders" class="btn btn-primary px-4 py-2">
                                        <i class="fa fa-folder me-2"></i> Kelola Work Order
                                    </Link>
                                    <Link href="/apps/work-orders/reports" class="btn btn-outline-secondary px-4 py-2">
                                        <i class="fa fa-chart-bar me-2"></i> Lihat Laporan
                                    </Link>
                                </div>

                                <div class="mt-5">
                                    <small class="text-muted">
                                        © 2025 - TSP Fittings Coding Test - Adam Suchi Hafizullah
                                    </small>
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
    layout: LayoutApp,
};
</script>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
</script>
