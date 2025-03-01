<!-- components/BtnCreate.vue -->
<template>
    <Link
      :href="href"
      v-if="hasAnyPermission(permissions)"
      :class="[
        'btn btn-sm d-inline-flex align-items-center gap-1',
        `btn-${variant}`,
        { 'opacity-50 pe-none': loading },
        sizeClasses,
        className
      ]"
      :title="tooltip"
    >
      <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <IconPlus v-else :size="iconSize" :stroke-width="strokeWidth" class="flex-shrink-0" />

      <span v-if="!iconOnly" class="d-none d-sm-inline-block">
        <slot>{{ label }}</slot>
      </span>

      <slot name="badge"></slot>
    </Link>
  </template>

  <script setup>
  import { Link } from '@inertiajs/vue3';
  import { IconPlus } from '@tabler/icons-vue';
  import { computed } from 'vue';

  const props = defineProps({
    href: {
      type: String,
      required: true
    },
    permissions: {
      type: Array,
      required: true
    },
    variant: {
      type: String,
      default: 'primary'
    },
    size: {
      type: String,
      default: 'sm',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    iconSize: {
      type: Number,
      default: 16
    },
    strokeWidth: {
      type: Number,
      default: 2
    },
    label: {
      type: String,
      default: 'Tambah'
    },
    tooltip: {
      type: String,
      default: 'Tambah Data Baru'
    },
    loading: {
      type: Boolean,
      default: false
    },
    iconOnly: {
      type: Boolean,
      default: false
    },
    className: {
      type: String,
      default: ''
    }
  });

  const sizeClasses = computed(() => {
    const sizes = {
      sm: 'btn-sm py-1 px-2',
      md: 'py-2 px-3',
      lg: 'btn-lg py-2 px-4'
    };
    return sizes[props.size] || sizes.sm;
  });
  </script>

  <style scoped>
  .btn {
    transition: all 0.2s ease-in-out;
    position: relative;
    overflow: hidden;
  }

  .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .btn:active {
    transform: translateY(0);
  }

  .spinner-border {
    width: 1rem;
    height: 1rem;
  }

  @media (max-width: 576px) {
    .btn.btn-sm {
      padding: 0.25rem 0.5rem;
    }
  }
  </style>
