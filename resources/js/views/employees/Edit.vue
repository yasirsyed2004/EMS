<template>
  <div class="employee-create">
    <div class="page-header">
      <h2 class="page-title">Edit Employee</h2>
      <router-link to="/employees" class="btn btn-sm btn-primary no-underline">
        Back to Employees
      </router-link>
    </div>

    <div class="card" v-if="employeeData">
      <form @submit.prevent="updateEmployee" class="employee-form">
        <ul class="alert alert-danger" v-if="error">
          <li v-for="(msg, index) in error" :key="index">{{ msg }}</li>
        </ul>

        <div class="form-row">
          <div class="form-group">
            <label for="name" class="form-label">Name *</label>
            <input type="text" id="name" v-model="employeeData.name" class="form-control" required />
          </div>

          <div class="form-group">
            <label for="email" class="form-label">Email *</label>
            <input type="email" id="email" v-model="employeeData.email" class="form-control" required />
          </div>
        </div>
        <!-- <div class="form-row">
  <div class="form-group">
    <label for="password" class="form-label">Password</label>
    <input type="password" id="password" v-model="employeeData.password" class="form-control" />
  </div>

  <div class="form-group">
    <label for="password_confirmation" class="form-label">Confirm Password</label>
    <input type="password" id="password_confirmation" v-model="employeeData.password_confirmation" class="form-control" />
  </div>
</div> -->


        <div class="form-row">
          <div class="form-group">
            <label for="phone" class="form-label">Phone *</label>
            <input type="text" id="phone" v-model="employeeData.phone" class="form-control" required />
          </div>

          <div class="form-group">
            <label for="department" class="form-label">Department</label>
            <select v-model="employeeData.department_id" class="form-control">
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
            <select v-model="employeeData.role_id" class="form-control">
              <option value="" disabled>Select role</option>
              <option v-for="role in roles.data" :key="role.id" :value="role.id">
                {{ role.name }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label for="joining_date" class="form-label">Joining Date</label>
            <input type="date" v-model="employeeData.joining_date" class="form-control" />
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn btn-secondary" @click="resetForm">Reset</button>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            {{ loading ? 'Updating...' : 'Update Employee' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToast } from 'primevue/usetoast'
import api from '../../services/api.service'
import { useEmployeeStore } from '../../stores/employee'
import { useDepartmentStore } from '../../stores/department'

const route = useRoute()
const router = useRouter()
const toast = useToast()
const employeeStore = useEmployeeStore()
const departmentStore = useDepartmentStore()
const id = computed(() => route.params.id)

const loading = computed(() => employeeStore.loading)
const error = computed(() => employeeStore.error)
const departments = computed(() => departmentStore.departments)
const roles = ref([])
const employeeFetched = ref(false);

const employeeData = reactive({
  name: '',
  email: '',
  phone: '',
  department_id: '',
  role_id: '',
  joining_date: '',
  // password: '',
  // password_confirmation: ''
})


onMounted(async () => {
  await fetchDepartments()
  await fetchRoles()
  await fetchEmployee()
})

async function fetchDepartments() {
  await departmentStore.fetchDepartments({ all: true })
}

async function fetchRoles() {
  const res = await api.get('/roles?all=true')
  roles.value = res.data
}

async function fetchEmployee() {
  try {

    await employeeStore.fetchEmployee(id.value)
    const employee = employeeStore.employee
    console.log(employee.id, 'ep')
    if (employee) {
      employeeData.name = employee.user.name || ''
      employeeData.email = employee.user.email || ''
      employeeData.phone = employee.user.phone || ''
      employeeData.department_id = employee.department?.id || ''
      employeeData.role_id = employee.user.roles?.[0]?.id || ''
      employeeData.joining_date = employee.joining_date || ''

      employeeFetched.value = true
    }
    console.log(employeeData, 'ddddddd')
  } catch (err) {
    console.log(err)
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to load employee',
      life: 3000
    })
  }
}

async function updateEmployee() {
  employeeStore.error = null
  try {
    await employeeStore.updateEmployee(route.params.id, employeeData)
    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: 'Employee updated successfully',
      life: 3000
    })
    router.push('/employees')
  } catch (err) {
    const msg = err.response?.data?.message || 'Failed to update employee'
    // console.log(msg)
    toast.add({ severity: 'error', summary: 'Error', detail: msg, life: 3000 })
  }
}

function resetForm() {
  fetchEmployee()
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
