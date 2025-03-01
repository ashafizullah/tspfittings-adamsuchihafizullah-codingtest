<!-- components/BtnEdit.vue -->
<template>
    <Link
      :href="href"
      v-if="hasAnyPermission(permissions)"
      :class="[
        'btn d-inline-flex align-items-center gap-1',
        `btn-${variant}`,
        { 'opacity-50 pe-none': loading },
        sizeClasses,
        className
      ]"
      :title="tooltip"
      :disabled="disabled"
      :preserve-scroll="preserveScroll"
      :preserve-state="preserveState"
    >
      <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <IconEdit v-else :size="iconSize" :stroke-width="strokeWidth" class="flex-shrink-0" />

      <span v-if="!iconOnly" class="d-none d-sm-inline-block">
        <slot>{{ label }}</slot>
      </span>

      <slot name="badge"></slot>
    </Link>
   </template>

   <script setup>
   import { Link } from '@inertiajs/vue3';
   import { IconEdit } from '@tabler/icons-vue';
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
      default: 'warning'
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
      default: 'Edit'
    },
    tooltip: {
      type: String,
      default: 'Edit Data'
    },
    loading: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    iconOnly: {
      type: Boolean,
      default: false
    },
    className: {
      type: String,
      default: 'me-2'
    },
    preserveScroll: {
      type: Boolean,
      default: false
    },
    preserveState: {
      type: Boolean,
      default: false
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

   .btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
   }

   .btn:active:not(:disabled) {
    transform: translateY(0);
   }

   .btn:disabled {
    cursor: not-allowed;
    opacity: 0.65;
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
