<!-- components/FormLabel.vue -->
<template>
    <label
      :for="htmlFor"
      :class="[
        'form-label',
        fontClasses,
        sizeClasses,
        colorClasses,
        { 'mb-1': !noMargin },
        className
      ]"
    >
      <!-- Label content with optional icon -->
      <span class="d-inline-flex align-items-center gap-1">
        <slot name="icon"></slot>
        <span>{{ name }}</span>
      </span>

      <!-- Required indicator -->
      <span
        v-if="required"
        class="text-danger ms-1"
        :class="{ 'required-asterisk': useAsterisk }"
        :title="requiredText"
      >
        {{ useAsterisk ? '*' : requiredText }}
      </span>

      <!-- Optional indicator -->
      <span
        v-if="optional"
        class="text-muted ms-1 small"
        :title="optionalText"
      >
        ({{ optionalText }})
      </span>

      <!-- Help text -->
      <slot name="help">
        <small
          v-if="helpText"
          class="d-block text-muted mt-1"
          :class="helpTextClasses"
        >
          {{ helpText }}
        </small>
      </slot>

      <!-- Custom content slot -->
      <slot></slot>
    </label>
   </template>

   <script setup>
   import { computed } from 'vue';

   const props = defineProps({
    name: {
      type: String,
      required: true
    },
    htmlFor: {
      type: String,
      default: ''
    },
    required: {
      type: Boolean,
      default: false
    },
    optional: {
      type: Boolean,
      default: false
    },
    useAsterisk: {
      type: Boolean,
      default: true
    },
    requiredText: {
      type: String,
      default: 'Required'
    },
    optionalText: {
      type: String,
      default: 'Optional'
    },
    helpText: {
      type: String,
      default: ''
    },
    size: {
      type: String,
      default: 'md',
      validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    weight: {
      type: String,
      default: 'bold',
      validator: (value) => ['normal', 'medium', 'semibold', 'bold'].includes(value)
    },
    variant: {
      type: String,
      default: 'default',
      validator: (value) => ['default', 'primary', 'success', 'warning', 'danger', 'info'].includes(value)
    },
    noMargin: {
      type: Boolean,
      default: false
    },
    className: {
      type: String,
      default: ''
    }
   });

   // Computed classes
   const fontClasses = computed(() => ({
    'fw-normal': props.weight === 'normal',
    'fw-medium': props.weight === 'medium',
    'fw-semibold': props.weight === 'semibold',
    'fw-bold': props.weight === 'bold'
   }));

   const sizeClasses = computed(() => ({
    'form-label-sm': props.size === 'sm',
    'form-label-lg': props.size === 'lg'
   }));

   const colorClasses = computed(() => ({
    'text-primary': props.variant === 'primary',
    'text-success': props.variant === 'success',
    'text-warning': props.variant === 'warning',
    'text-danger': props.variant === 'danger',
    'text-info': props.variant === 'info'
   }));

   const helpTextClasses = computed(() => ({
    'small': true,
    'text-muted': true,
    'mt-1': true,
    'text-sm': props.size === 'sm',
    'text-base': props.size === 'md',
    'text-lg': props.size === 'lg'
   }));
   </script>

   <style scoped>
   .form-label {
    display: inline-block;
    margin-bottom: var(--bs-label-margin-bottom, 0.5rem);
   }

   .form-label-sm {
    font-size: 0.875rem;
   }

   .form-label-lg {
    font-size: 1.25rem;
   }

   .required-asterisk {
    font-weight: bold;
    font-size: 1.2em;
    line-height: 0;
   }

   /* Optional animations */
   .form-label {
    transition: color 0.2s ease-in-out;
   }

   @media (hover: hover) {
    .form-label:hover {
      opacity: 0.9;
    }
   }

   /* Responsive adjustments */
   @media (max-width: 576px) {
    .form-label {
      font-size: 0.9rem;
    }

    .form-label-lg {
      font-size: 1.1rem;
    }
   }
   </style>
