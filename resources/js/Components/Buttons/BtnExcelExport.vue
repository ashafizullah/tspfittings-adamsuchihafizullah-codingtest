<!-- components/BtnExcelExport.vue -->
<template>
    <a
        :href="computedHref"
        target="_blank"
        :class="[
            'btn d-inline-flex align-items-center gap-1',
            `btn-${variant}`,
            'border-0 shadow',
            className,
            { 'disabled': isDisabled }
        ]"
        :title="tooltip"
    >
        <i :class="[iconClass, iconSize]"></i>
        <span v-if="!iconOnly" class="d-none d-sm-inline-block">
            <slot>{{ label }}</slot>
        </span>
        <slot name="badge"></slot>
    </a>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    baseUrl: {
        type: String,
        required: true
    },
    startDate: {
        type: String,
        default: ''
    },
    endDate: {
        type: String,
        default: ''
    },
    kecamatanId: {
        type: Number,
        default: 0
    },
    kelurahanDesaId: {
        type: Number,
        default: 0
    },
    tpsId: {
        type: Number,
        default: 0
    },
    keterangan: {
        type: String,
        default: ''
    },
    catatan: {
        type: String,
        default: ''
    },
    ketuaId: {
        type: Number,
        default: ''
    },
    variant: {
        type: String,
        default: 'success'
    },
    size: {
        type: String,
        default: 'sm',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    iconClass: {
        type: String,
        default: 'fa fa-file-excel'
    },
    label: {
        type: String,
        default: 'Excel'
    },
    tooltip: {
        type: String,
        default: 'Export to Excel'
    },
    iconOnly: {
        type: Boolean,
        default: false
    },
    className: {
        type: String,
        default: 'shadow-sm rounded-sm'
    },
    requireDates: {
        type: Boolean,
        default: false
    },
    requireFilters: {
        type: Boolean,
        default: true
    }
});

const computedHref = computed(() => {
    const params = new URLSearchParams();

    // Add date parameters if they exist
    if (props.startDate) params.append('start_date', props.startDate);
    if (props.endDate) params.append('end_date', props.endDate);

    // Add location parameters if they exist
    if (props.kecamatanId) params.append('kecamatan_id', props.kecamatanId);
    if (props.kelurahanDesaId) params.append('kelurahan_desa_id', props.kelurahanDesaId);
    if (props.tpsId) params.append('tps_id', props.tpsId);
    if (props.keterangan) params.append('keterangan', props.keterangan);
    if (props.catatan) params.append('catatan', props.catatan);
    if (props.ketuaId) params.append('ketua_id', props.ketuaId);

    return `${props.baseUrl}?${params.toString()}`;
});

const isDisabled = computed(() => {
    if (props.requireDates && (!props.startDate || !props.endDate)) {
        return true;
    }

    if (props.requireFilters && (!props.kecamatanId && !props.kelurahanDesaId && !props.tpsId && !props.keterangan && !props.catatan && !props.ketuaId)) {
        return true;
    }

    return false;
});

const iconSize = computed(() => {
    const sizes = {
        sm: 'fa-sm',
        md: 'fa-md',
        lg: 'fa-lg'
    };
    return sizes[props.size] || sizes.md;
});
</script>

<style scoped>
.btn {
    transition: all 0.2s ease-in-out;
    position: relative;
    overflow: hidden;
}

.btn:hover:not(.disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15) !important;
}

.btn:active:not(.disabled) {
    transform: translateY(0);
}

.btn.disabled {
    cursor: not-allowed;
    opacity: 0.65;
    pointer-events: none;
}

@media (max-width: 576px) {
    .btn.btn-sm {
        padding: 0.25rem 0.5rem;
    }
}
</style>
