<template>
  <div class="department-edit">
    <div class="page-header">
      <h2 class="page-title">Edit Department</h2>
      <router-link to="/departments" class="btn btn-sm btn-primary no-underline">
        Back to Departments
      </router-link>
    </div>

    <div class="card" v-if="department">
      <form @submit.prevent="updateDepartment" class="department-form">
        <div class="form-group">
          <label for="name" class="form-label">Department Name *</label>
          <input type="text" id="name" v-model="departmentData.name" class="form-control" required />
        </div>

        <div class="form-group">
          <label for="code" class="form-label">Code</label>
          <input type="text" id="code" v-model="departmentData.code" class="form-control" />
        </div>

        <div class="form-group">
          <label for="description" class="form-label">Description</label>
          <textarea id="description" v-model="departmentData.description" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" @click="resetForm">
            Reset
          </button>
          <button type="submit" class="btn btn-primary" :disabled="saving">
            {{ saving ? 'Saving...' : 'Update Department' }}
          </button>
        </div>
      </form>
    </div>

    <div v-else class="loading-indicator">Loading department data...</div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useDepartmentStore } from '../../stores/department'
import { useToast } from 'primevue/usetoast'

const route = useRoute()
const router = useRouter()
const toast = useToast()

const departmentStore = useDepartmentStore()
const id = computed(() => route.params.id)
const department = computed(() => departmentStore.currentDepartment)
const loading = computed(() => departmentStore.loading)
const saving = ref(false)

const departmentData = reactive({
  name: '',
  code: '',
  description: ''
})

onMounted(async () => {
  try {
    await departmentStore.fetchDepartmentById(id.value)
    if (department.value) {
      console.log(department)
      departmentData.name = department.value.data.name || ''
      departmentData.code = department.value.data.code || ''
      departmentData.description = department.value.data.description || ''
    }
  } catch (err) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to load department',
      life: 3000
    })
  }
})

async function updateDepartment() {
  if (!departmentData.name || !departmentData.description || !departmentData.code) {
    toast.add({
      severity: 'warn',
      summary: 'Validation Error',
      detail: 'All fields are required',
      life: 5000
    })
    return
  }

  saving.value = true
  try {
    await departmentStore.updateDepartment(id.value, departmentData)
    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Department updated successfully',
      life: 3000
    })
    router.push('/departments')
  } catch (error) {
    const msg = error.response?.data?.message || 'Failed to update department'
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: msg,
      life: 3000
    })
  } finally {
    saving.value = false
  }
}

function resetForm() {
  if (department.value) {
    departmentData.name = department.value.name || ''
    departmentData.code = department.value.code || ''
    departmentData.description = department.value.description || ''
  }
}
</script>

<style scoped>
.department-edit {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding-top: 5%;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
}

.loading-indicator {
  padding: 2rem;
  text-align: center;
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  padding: 2rem;
}

.department-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
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

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1rem;
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

.btn-secondary {
  background-color: #e5e7eb;
  color: #111827;
}
</style>
