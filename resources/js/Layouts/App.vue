<template>
	<div class="c-app">
		<div class="c-sidebar c-sidebar-dark c-sidebar-fixed"
			:class="{ 'c-sidebar-lg-show': !$page.url.startsWith('/apps/dashboard') }" id="sidebar">
			<div class="c-sidebar-brand d-lg-down-none header">
				<img src="/images/logo-only.png" width="40"> <span class="ml-2 font-weight-bold">TSP Work Orders</span>
			</div>

			<!-- sidebar -->
			<Sidebar />
			<!-- end sidebar -->

		</div>
		<div class="c-wrapper c-fixed-components">

			<!-- header -->
			<Header />
			<!-- end header -->

			<div class="c-body">

				<!-- content -->
				<slot />
				<!-- end content -->

				<footer class="c-footer">
					<div>&copy; 2025 - TSP Fittings Coding Test - Adam Suchi Hafizullah</div>
				</footer>
			</div>
		</div>
	</div>
</template>

<script>
import Header from '../Components/Header.vue'
import Sidebar from '../Components/Sidebar.vue'

export default {
	components: {
		Header,
		Sidebar
	}
}
</script>
