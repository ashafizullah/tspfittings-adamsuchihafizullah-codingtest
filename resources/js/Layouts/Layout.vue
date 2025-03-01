<template>
	<div class="page-wrapper">
		<!-- Main Header-->
		<Header />
		<!--End Main Header -->
		<slot />
		<!-- Main Footer -->
		<Footer />
	</div>
	<!--End pagewrapper-->
</template>
<script>
import Header from '../Components/Frontend/Header.vue'
import Footer from '../Components/Frontend/Footer.vue'
import {
	Head,
} from '@inertiajs/inertia-vue3';

export default {
	//register components
	components: {
		Header,
		Footer,
		Head
	}
}
</script>

<style>

</style>