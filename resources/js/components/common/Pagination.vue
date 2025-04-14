<script setup>
defineProps({
  currentPage: Number,
  totalPages: Number,
});
const emit = defineEmits(["change-page"]);
</script>

<template>
  <div class="pagination">
    <button
      class="btn-page btn"
      @click="$emit('change-page', currentPage - 1)"
      :disabled="currentPage === 1"
    >
      Previous
    </button>

    <span class="page-info">Page {{ currentPage }} of {{ totalPages }}</span>

    <button
      class="btn-page btn"
      @click="$emit('change-page', currentPage + 1)"
      :disabled="currentPage === totalPages"
    >
      Next
    </button>
  </div>
</template>

<style scoped>
.pagination {
  display: flex;
  justify-content: flex-end; /* Push to right */
  align-items: center;
  margin-top: 1rem;
  gap: 1rem;
  padding-right: 1rem;
}

.btn-page {
  padding: 0.375rem 0.75rem;
  border: 1px solid var(--border-color);
  background-color: rgb(238, 233, 233);
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-page:hover:not(:disabled) {
  background-color: var(--secondary-color);
}

.btn-page:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-info {
  font-size: 0.875rem;
  color: var(--text-light);
}
</style>
