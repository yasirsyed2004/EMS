<template>
  <div class="card">
    <h3 class="text-lg font-semibold mb-4">Change Password</h3>
    <form @submit.prevent="handleChangePassword">
      <div class="form-group">
        <label class="form-label" for="newPassword">New Password</label>
        <input
          type="password"
          id="newPassword"
          v-model="form.newPassword"
          class="form-control"
          required
        />
      </div>
      <div class="form-group">
        <label class="form-label" for="confirmPassword">Confirm New Password</label>
        <input
          type="password"
          id="confirmPassword"
          v-model="form.confirmPassword"
          class="form-control"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary w-full mt-4">Update Password</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useToast } from "primevue/usetoast";
import { useAuthStore } from "../../stores/auth";

const toast = useToast();
const authStore = useAuthStore();

const form = ref({
  newPassword: "",
  confirmPassword: "",
});

const handleChangePassword = async () => {
  if (!form.value.newPassword || !form.value.confirmPassword) {
    toast.add({
      severity: "warn",
      summary: "Validation Error",
      detail: "All fields are required",
      life: 5000,
    });
    return;
  }

  if (form.value.newPassword !== form.value.confirmPassword) {
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Passwords do not match",
      life: 5000,
    });
    return;
  }

  try {
    await authStore.resetPassword({
      password: form.value.newPassword,
      password_confirmation: form.value.confirmPassword,
    });

    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Password updated successfully",
      life: 5000,
    });

    // Reset form
    form.value.newPassword = "";
    form.value.confirmPassword = "";
  } catch (err) {
    toast.add({
      severity: "error",
      summary: "Failed",
      detail: err?.response?.data?.message || "Password update failed",
      life: 5000,
    });
  }
};
</script>

<style scoped>
.card {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  padding: 2rem;
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
.form-control:focus {
  outline: none;
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
}
.btn {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
}
.btn-primary {
  background-color: #4f46e5;
  color: white;
  border: none;
}
.btn-primary:hover {
  background-color: #4338ca;
}
</style>
