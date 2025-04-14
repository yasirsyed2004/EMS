<template>
  <div class="department-create">
    <div class="page-header">
      <h2 class="page-title">Add New Department</h2>
      <router-link to="/departments" class="btn btn-sm btn-primary no-underline">
        Back to Departments
      </router-link>
    </div>

    <div class="card">
      <form @submit.prevent="saveDepartment" class="department-form">
        <div class="alert alert-danger" v-if="error">
          {{ error }}
        </div>

        <div class="form-group">
          <label for="name" class="form-label">Department Name *</label>
          <input type="text" id="name" v-model="department.name" class="form-control" required />
        </div>

        <div class="form-group">
          <label for="code" class="form-label">Code</label>
          <input type="text" id="code" v-model="department.code" class="form-control" />
        </div>


        <div class="form-group">
          <label for="description" class="form-label">Description</label>
          <textarea id="description" v-model="department.description" class="form-control" rows="3"></textarea>
        </div>


        <div class="form-actions">
          <button type="button" class="btn btn-secondary" @click="resetForm">
            Reset
          </button>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            {{ loading ? 'Saving...' : 'Save Department' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useDepartmentStore } from '../../stores/department'
import { useToast } from 'primevue/usetoast'

const router = useRouter()
const departmentStore = useDepartmentStore()
const toast = useToast()


const employees = ref([])
const loading = computed(() => departmentStore.loading)
const error = computed(() => departmentStore.error)

const department = reactive({
  name: '',
  description: '',
  code: ''
})


async function saveDepartment() {
  if (!department.name || !department.description || !department.code) {
    toast.add({
      severity: 'warn',
      summary: 'Validation Error',
      detail: 'All fields are required',
      life: 5000
    })
    return
  }
  try {
    await departmentStore.addDepartment(department)
    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Department added successfully',
      life: 5000
    })

    router.push('/departments')
  } catch (error) {
    console.error('Failed to create department:', error)
    const msg = err.response?.data?.message || 'Failed to create department'
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: msg,
      life: 3000
    })
    error.value = msg

  }
}

function resetForm() {
  Object.keys(department).forEach(key => {
    department[key] = ''
  })
}
</script>

<style scoped>
.department-create {
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

.department-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1rem;
}

.card {
  border: none;
}

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