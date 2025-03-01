<template>
    <div :class="[
      'd-flex',
      `justify-content-${align}`,
      spacing
    ]">
      <slot />
    </div>
  </template>

  <script setup>
  defineProps({
    align: {
      type: String,
      default: 'end',
      validator: (value) => ['start', 'center', 'end'].includes(value)
    },
    spacing: {
      type: String,
      default: 'mt-4'
    }
  });
  </script>
