<template>
    <div v-if="shouldShow" :class="errorClasses">
      <slot>{{ message }}</slot>
    </div>
  </template>

  <script>
  export default {
    name: 'FormError',
    props: {
      message: {
        type: [String, Array],
        default: ''
      },
      field: {
        type: String,
        default: ''
      },
      errors: {
        type: Object,
        default: () => ({})
      },
      type: {
        type: String,
        default: 'danger',
        validator: (value) => ['danger', 'warning', 'info'].includes(value)
      }
    },
    computed: {
      shouldShow() {
        if (this.field && this.errors) {
          return this.errors[this.field];
        }
        return this.message;
      },
      errorClasses() {
        return {
          'alert': true,
          [`alert-${this.type}`]: true,
          'mt-1': true,
          'mb-0': true,
          'py-1': true,
          'small': true
        }
      },
      displayMessage() {
        if (this.field && this.errors) {
          return this.errors[this.field];
        }
        return this.message;
      }
    }
  }
  </script>

  <style scoped>
  .alert {
    font-size: 0.875rem;
  }
  </style>
