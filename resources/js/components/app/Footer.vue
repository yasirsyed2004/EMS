<template>
  <footer class="bg-white">
    <div class="max-w-7xl mx-auto py-6 px-4">
      <div class="flex flex-col items-center justify-between sm:flex-row">
        <div class="flex items-center">
          <span class="text-sm text-gray-500">&copy; {{ currentYear }} Employee Management System</span>
        </div>
        <div class="mt-4 sm:mt-0">
          <span class="text-sm text-gray-500">Laravel 8 & Vue 3</span>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue'

const currentYear = computed(() => new Date().getFullYear())
</script>