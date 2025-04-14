<template>
  <div class="modal-wrapper">
    <div class="modal-box">
      <div class="modal-header">
        <h3>Confirm Deletion</h3>
        <button class="modal-close" @click="$emit('close')">&times;</button>
      </div>
      <div class="modal-body">
        <p>
          Are you sure you want to delete <strong>{{ item?.name }}</strong
          >?
        </p>
        <p class="warning-text">This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-cancel" @click="$emit('close')">Cancel</button>
        <button class="btn btn-danger" @click="$emit('confirm')">Delete</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  item: Object,
});
</script>

<style scoped>
.modal-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  z-index: 50;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-backdrop {
  position: absolute;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4); /* ← reduce this value for a lighter overlay */
  z-index: 1;
}

.modal-box {
  position: relative;
  background-color: white;
  padding: 2rem;
  border-radius: 8px;
  z-index: 2;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: bold;
  font-size: 1.25rem;
  margin-bottom: 1rem;
}

.modal-close {
  background: transparent;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
}

.modal-body p {
  margin-bottom: 0.5rem;
}

.warning-text {
  color: red;
  font-size: 0.9rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
}

.btn-cancel {
  background-color: #e5e7eb;
  color: #374151;
}

.btn-danger {
  background-color: #dc2626;
  color: white;
}
</style>
