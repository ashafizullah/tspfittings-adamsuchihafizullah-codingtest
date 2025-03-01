<template>
    <button
      type="button"
      v-if="hasAnyPermission(permissions)"
      @click="handleDelete"
      :class="[
        'btn d-inline-flex align-items-center gap-1',
        `btn-${variant}`,
        { 'opacity-50 pe-none': loading },
        sizeClasses,
        className
      ]"
      :title="tooltip"
      :disabled="disabled || loading"
    >
      <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <IconTrash v-else :size="iconSize" :stroke-width="strokeWidth" class="flex-shrink-0" />

      <span v-if="!iconOnly" class="d-none d-sm-inline-block">
        <slot>{{ label }}</slot>
      </span>

      <slot name="badge"></slot>
    </button>
   </template>

   <script setup>
   import { ref } from 'vue';
   import { IconTrash } from '@tabler/icons-vue';
   import { deleteConfirm } from '../../Composables/useAlert';
   import { computed } from 'vue';

   const props = defineProps({
    id: {
      type: Number,
      required: true
    },
    model: {
      type: String,
      required: true
    },
    route: {
      type: String,
      required: true
    },
    permissions: {
      type: Array,
      required: true
    },
    variant: {
      type: String,
      default: 'danger'
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
      default: 'Hapus'
    },
    tooltip: {
      type: String,
      default: 'Hapus Data'
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
      default: ''
    },
    confirmMessage: {
      type: String,
      default: 'Anda yakin ingin menghapus data ini?'
    }
   });

   const emit = defineEmits(['beforeDelete', 'afterDelete', 'error']);
   const loading = ref(false);

   const sizeClasses = computed(() => {
    const sizes = {
      sm: 'btn-sm py-1 px-2',
      md: 'py-2 px-3',
      lg: 'btn-lg py-2 px-4'
    };
    return sizes[props.size] || sizes.sm;
   });

   const handleDelete = async () => {
    if (loading.value || props.disabled) return;

    try {
      emit('beforeDelete', props.id);
      loading.value = true;

      await deleteConfirm(props.model, props.route, props.id);

      emit('afterDelete', props.id);
    } catch (error) {
      emit('error', error);
    } finally {
      loading.value = false;
    }
   };
   </script>

   <style scoped>
   .btn {
    transition: all 0.2s ease-in-out;
    position: relative;
    overflow: hidden;
   }

   .btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
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
