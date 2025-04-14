<template>
  <div class="card shadow-sm">
    <div class="card-header text-black">
      <h5 class="mb-0">User Information</h5>
    </div>
    <div class="card-body p-0">
      <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between">
          <!-- <strong>Name:</strong> -->
          <span>{{ user?.name }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <!-- <strong>Email:</strong> -->
          <span>{{ user?.email }}</span>
        </li>
        <!-- <li class="list-group-item d-flex justify-content-between">
            <strong>Role:</strong>
            <span>{{ user.roles[0]?.name || 'N/A' }}</span>
          </li> -->
        <!-- <li class="list-group-item d-flex justify-content-between">
            <strong>Last Login:</strong>
            <span>{{ formatDate(user.last_login) || 'N/A' }}</span>
          </li> -->
      </ul>
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();
const user = computed(() => authStore.user);
</script>

<style scoped>
.card {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  padding: 2rem;
  margin-bottom: 2rem;
}
.card-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  text-align: center;
}
.form-group {
  margin-bottom: 1.25rem;
}
.form-label {
  display: block;
  font-weight: 500;
  margin-bottom: 0.5rem;
  color: #374151;
}
.form-control {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1rem;
  background-color: white;
}
</style>
