<template>
    <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
        <button
            class="c-header-toggler c-class-toggler d-lg-none mfe-auto"
            type="button"
            data-target="#sidebar"
            data-class="c-sidebar-show"
            @click="toggleMobileMenu"
        >
            <IconMenu size="1.6em" />
        </button>

        <button
            class="c-header-toggler c-class-toggler mfs-3 d-md-down-none"
            type="button"
            data-target="#sidebar"
            data-class="c-sidebar-lg-show"
            responsive="true"
            @click="toggleDesktopMenu"
        >
            <IconMenu size="1.6em" />
        </button>

        <ul class="c-header-nav ml-auto mr-4">
            <li class="c-header-nav-item dropdown">
                <a class="c-header-nav-link"
                   data-toggle="dropdown"
                   href="#"
                   role="button"
                   aria-haspopup="true"
                   aria-expanded="false"
                >
                    <div class="c-avatar">
                        <img
                            class="c-avatar-img"
                            :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&amp;background=4e73df&amp;color=ffffff&amp;size=100`"
                        >
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right pt-0 mb-0 pb-0">
                    <Link
                        href="/logout"
                        method="POST"
                        as="button"
                        class="dropdown-item"
                        role="button"
                    >
                        <IconLogout size="16" class="me-2" />
                        Logout
                    </Link>
                </div>
            </li>
        </ul>
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { IconMenu, IconLogout } from '@tabler/icons-vue'
import { onMounted, onUnmounted } from 'vue'

const toggleMobileMenu = () => {
    document.getElementById('sidebar')?.classList.toggle('c-sidebar-show')
}

const toggleDesktopMenu = () => {
    document.getElementById('sidebar')?.classList.toggle('c-sidebar-lg-show')
}

const handleClickOutside = (event) => {
    const sidebar = document.getElementById('sidebar')
    const toggleButton = document.querySelector('.c-header-toggler')

    if (sidebar &&
        sidebar.classList.contains('c-sidebar-show') &&
        !sidebar.contains(event.target) &&
        !toggleButton.contains(event.target)) {
        sidebar.classList.remove('c-sidebar-show')
    }
}

// Lifecycle hooks
onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>
