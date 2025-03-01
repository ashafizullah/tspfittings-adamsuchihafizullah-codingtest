<!-- components/Pagination.vue -->
<template>
    <div>
        <!-- Results Range Info -->
        <div class="text-sm text-gray-600 mb-3" v-if="shouldShowPagination">
            Menampilkan {{ currentRangeStart }}-{{ currentRangeEnd }} dari total {{ totalItems }} data
        </div>

        <nav v-if="shouldShowPagination" :aria-label="ariaLabel">
            <ul :class="paginationClasses">
                <!-- Previous Page -->
                <li :class="['page-item', { 'disabled': !hasPrevPage }]">
                    <Link
                        :href="addQueryToUrl(prevPageUrl)"
                        class="page-link"
                        :class="{ 'disabled': !hasPrevPage }"
                        :aria-disabled="!hasPrevPage"
                        aria-label="Previous"
                        preserve-scroll
                    >
                        <span aria-hidden="true">&laquo;</span>
                        <span class="visually-hidden">Previous</span>
                    </Link>
                </li>

                <!-- Page Numbers -->
                <template v-for="(link, index) in visibleLinks" :key="index">
                    <li v-if="link.type === 'dots'" class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                    <li v-else :class="[
                        'page-item',
                        {
                            'active': link.active,
                            'disabled': !link.url
                        }
                    ]">
                        <Link
                            :href="addQueryToUrl(link.url)"
                            class="page-link"
                            :aria-current="link.active ? 'page' : undefined"
                            v-html="link.label"
                            preserve-scroll
                        />
                    </li>
                </template>

                <!-- Next Page -->
                <li :class="['page-item', { 'disabled': !hasNextPage }]">
                    <Link
                        :href="addQueryToUrl(nextPageUrl)"
                        class="page-link"
                        :class="{ 'disabled': !hasNextPage }"
                        :aria-disabled="!hasNextPage"
                        aria-label="Next"
                        preserve-scroll
                    >
                        <span aria-hidden="true">&raquo;</span>
                        <span class="visually-hidden">Next</span>
                    </Link>
                </li>
            </ul>

            <!-- Optional Small Screen Pagination -->
            <div v-if="showCompactOnMobile" class="d-md-none mt-2 text-center">
                <small class="text-muted">
                    Page {{ currentPage }} of {{ totalPages }}
                </small>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
    align: {
        type: String,
        default: 'end',
        validator: (value) => ['start', 'center', 'end'].includes(value)
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    showCompactOnMobile: {
        type: Boolean,
        default: true
    },
    maxVisiblePages: {
        type: Number,
        default: 5
    },
    ariaLabel: {
        type: String,
        default: 'Page navigation'
    },
    theme: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'success', 'danger', 'warning', 'info'].includes(value)
    },
    // New props for pagination info
    perPage: {
        type: Number,
        required: true
    },
    totalItems: {
        type: Number,
        required: true
    }
});

const addQueryToUrl = (url) => {
    if (!url) return '#';

    const currentQuery = new URL(window.location.href).searchParams;
    const targetUrl = new URL(url, window.location.origin);

    // Preserve all current query parameters except 'page'
    currentQuery.forEach((value, key) => {
        if (key !== 'page') {
            targetUrl.searchParams.set(key, value);
        }
    });

    return targetUrl.toString();
};

// Computed Properties
const shouldShowPagination = computed(() => {
    return props.links && props.links.length > 3;
});

const paginationClasses = computed(() => [
    'pagination',
    `justify-content-${props.align}`,
    `pagination-${props.size}`,
    'mb-0'
]);

const hasPrevPage = computed(() => {
    return props.links[0]?.url !== null;
});

const hasNextPage = computed(() => {
    return props.links[props.links.length - 1]?.url !== null;
});

const prevPageUrl = computed(() => {
    return props.links[0]?.url || '#';
});

const nextPageUrl = computed(() => {
    return props.links[props.links.length - 1]?.url || '#';
});

const currentPage = computed(() => {
    const activePage = props.links.find(link => link.active);
    return activePage ? parseInt(activePage.label) : 1;
});

const totalPages = computed(() => {
    return props.links.length - 2; // Exclude prev/next links
});

// New computed properties for showing results range
const currentRangeStart = computed(() => {
    return (currentPage.value - 1) * props.perPage + 1;
});

const currentRangeEnd = computed(() => {
    const end = currentPage.value * props.perPage;
    return end > props.totalItems ? props.totalItems : end;
});

const visibleLinks = computed(() => {
    if (props.links.length <= props.maxVisiblePages + 2) return props.links.slice(1, -1);

    const currentIdx = props.links.findIndex(link => link.active);
    const halfVisible = Math.floor(props.maxVisiblePages / 2);
    const links = [...props.links];

    // Remove first and last (prev/next) elements
    links.shift();
    links.pop();

    if (currentIdx <= halfVisible + 1) {
        // Near start
        return [
            ...links.slice(0, props.maxVisiblePages),
            { type: 'dots' },
            links[links.length - 1]
        ];
    } else if (currentIdx >= links.length - halfVisible - 1) {
        // Near end
        return [
            links[0],
            { type: 'dots' },
            ...links.slice(-props.maxVisiblePages)
        ];
    } else {
        // Middle
        return [
            links[0],
            { type: 'dots' },
            ...links.slice(currentIdx - halfVisible, currentIdx + halfVisible + 1),
            { type: 'dots' },
            links[links.length - 1]
        ];
    }
});
</script>

<style scoped>
.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: v-bind('themeColors[props.theme]');
    border-color: v-bind('themeColors[props.theme]');
}

.page-link {
    transition: all 0.2s ease-in-out;
}

.page-link:hover:not(.disabled) {
    z-index: 2;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-link.disabled {
    cursor: not-allowed;
    opacity: 0.65;
}

/* Size Variants */
.pagination-sm .page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.pagination-lg .page-link {
    padding: 0.75rem 1.5rem;
    font-size: 1.25rem;
}

@media (max-width: 768px) {
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }

    .page-link {
        padding: 0.375rem 0.75rem;
    }
}
</style>

<script>
// Theme colors mapping
const themeColors = {
    primary: '#30744c',
    secondary: '#6c757d',
    success: '#198754',
    danger: '#dc3545',
    warning: '#ffc107',
    info: '#0dcaf0'
};
</script>
