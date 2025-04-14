<template>
  <div class="employee-create">
    <div class="page-header">
      <h2 class="page-title">Add New Employee</h2>
      <router-link to="/employees" class="btn btn-sm btn-primary no-underline">
        Back to Employees
      </router-link>
    </div>

    <div class="card">
      <form @submit.prevent="saveEmployee" class="employee-form">
        <ul class="alert alert-danger" v-if="error">
          <li v-for="(msg, index) in error" :key="index">{{ msg }}</li>
        </ul>

        <div class="form-row">
          <div class="form-group">
            <label for="name" class="form-label">Name *</label>
            <input type="text" id="name" v-model="employee.name" class="form-control" required />
          </div>

          <div class="form-group">
            <label for="email" class="form-label">Email *</label>
            <input type="email" id="email" v-model="employee.email" class="form-control" required />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="password" class="form-label">Password *</label>
            <input type="password" id="password" v-model="employee.password" class="form-control" required />
          </div>

          <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password *</label>
            <input type="password" id="password_confirmation" v-model="employee.password_confirmation"
              class="form-control" required />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="phone" class="form-label">Phone *</label>
            <input type="text" id="phone" v-model="employee.phone" class="form-control" required />
          </div>

          <div class="form-group">
            <label for="department" class="form-label">Department</label>
            <select v-model="employee.department_id" class="form-control">
              <option value="" disabled>Select department</option>
              <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                {{ dept.name }}
              </option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="role" class="form-label">Role</label>
            <select v-model="employee.role_id" class="form-control">
              <option value="" disabled>Select role</option>
              <option v-for="role in roles.data" :key="role.id" :value="role.id">
                {{ role.name }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label for="joining_date" class="form-label">Joining Date</label>
            <input type="date" v-model="employee.joining_date" class="form-control" />
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" @click="resetForm">Reset</button>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            {{ loading ? 'Saving...' : 'Save Employee' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>


<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useEmployeeStore } from '../../stores/employee'
import { useToast } from 'primevue/usetoast'
import api from '../../services/api.service'
import { useDepartmentStore } from '../../stores/department'

const router = useRouter()
const employeeStore = useEmployeeStore()
const departmentStore = useDepartmentStore()

const toast = useToast()

const loading = computed(() => employeeStore.loading)
const error = computed(() => employeeStore.error)
const departments = computed(() => departmentStore.departments)


// const departments = ref([])
const roles = ref([])
const employee = reactive({
  name: '',
  email: '',
  phone: '',
  department_id: '',
  role_id: '',
  joining_date: '',
  password: '',
  password_confirmation: ''
})

onMounted(async () => {
  await fetchDepartments()
  await fetchRoles()
})

async function fetchDepartments() {
  await departmentStore.fetchDepartments({ all: true })
}

async function fetchRoles() {
  const res = await api.get('/roles?all=true')
  roles.value = res.data
  console.log(roles)
}

async function saveEmployee() {
  // employeeStore.error = null
  if (!employee.name || !employee.email || !employee.phone) {
    toast.add({
      severity: 'warn',
      summary: 'Validation Error',
      detail: 'Please fill in all required fields.',
      life: 5000
    })
    return
  }
  if (employee.password !== employee.password_confirmation) {
    toast.add({
      severity: 'error',
      summary: 'Validation Error',
      detail: 'Passwords do not match',
      life: 5000
    })
    return
  }
  try {
    await employeeStore.addEmployee(employee)
    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Employee added successfully',
      life: 3000
    })
    error = []
    router.push('/employees')
  } catch (err) {
    console.log(err)
    const msg = err.response?.data?.message || 'Failed to create employee'
    toast.add({ severity: 'error', summary: 'Error', detail: msg, life: 3000 })
  }
}

function resetForm() {
  Object.keys(employee).forEach(key => {
    employee[key] = ''
  })
}
</script>


<style scoped>
.employee-create {
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

.employee-form {
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

.form-row {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.form-group {
  flex: 1;
}
</style>