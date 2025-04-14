<template>
  <div class="employee-view">
    <div class="page-header">
      <h2 class="page-title">Employee Details</h2>
      <div class="header-actions">
        <router-link :to="`/employees/${id}/edit`" class="btn btn-primary" v-if="hasPermission('update_employee')">
          Edit Employee
        </router-link>
        <router-link to="/employees" class="btn btn-secondary">
          Back to Employees
        </router-link>
      </div>
    </div>

    <div v-if="loading" class="loading-indicator">
      Loading employee data...
    </div>

    <div v-else-if="!employee" class="error-message">
      Employee not found or you don't have permission to view this employee.
    </div>

    <div v-else class="employee-details">
      <div class="card employee-info">
        <div class="employee-header">
          <div class="employee-avatar">
            {{ getInitials(employeeData.name) }}
          </div>
          <div class="employee-title">
            <h3 class="employee-name">{{ employeeData.name }}</h3>
            <!-- <span 
                class="status-badge" 
                :class="{ 'active': employee.status === 'active', 'inactive': employee.status === 'inactive' }"
              >
                {{ employee.status }}
              </span> -->
          </div>
        </div>
        {{ console.log(employeeData) }}

        <div class="info-section">
          <h4 class="section-title">Contact Information</h4>
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">Email</span>
              <span class="info-value">{{ employeeData.email }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Phone</span>
              <span class="info-value">{{ employeeData.phone || 'N/A' }}</span>
            </div>

          </div>
        </div>

        <div class="info-section">
          <h4 class="section-title">Employment Details</h4>
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">Department</span>
              <span class="info-value">{{ employeeData.department || 'N/A' }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Position</span>
              <span class="info-value">{{ employeeData.role }}</span>
            </div>
            <div class="info-item">
              <span class="info-label">Joined Date</span>
              <span class="info-value">{{ formatDate(employeeData.joining_date) }}</span>
            </div>
            <!-- <div class="info-item" v-if="hasPermission('view_salary')">
                <span class="info-label">Salary</span>
                <span class="info-value">{{ formatCurrency(employee.value.datasalary) }}</span>
              </div> -->
          </div>
        </div>

        <!-- <div class="info-section" v-if="employee.value.datanotes">
            <h4 class="section-title">Notes</h4>
            <p class="notes">{{ employee.value.datanotes }}</p>
          </div> -->
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useEmployeeStore } from '../../stores/employee'
import { useAuthStore } from '../../stores/auth'
import { useToast } from 'primevue/usetoast'

const route = useRoute()
const employeeStore = useEmployeeStore()
const authStore = useAuthStore()

const toast = useToast()

const id = computed(() => route.params.id)
const employee = computed(() => employeeStore.employee)
const loading = computed(() => employeeStore.loading)
console.log(employee)
const employeeFetched = ref(false);
const employeeData = ref({
  name: '',
  email: '',
  phone: '',
  department: '',
  role: '',
  joining_date: ''
})

onMounted(async () => {
  try {
    await employeeStore.fetchEmployee(id.value)
    const employee = employeeStore.employee
    console.log(employee.id, 'ep')
    if (employee) {
      employeeData.value.name = employee.user.name || ''
      employeeData.value.email = employee.user.email || ''
      employeeData.value.phone = employee.user.phone || ''
      employeeData.value.department = employee.department?.name || ''
      employeeData.value.role = employee.user.roles?.[0]?.name || ''
      employeeData.value.joining_date = employee.joining_date || ''

      employeeFetched.value = true
    }
    console.log(employeeData)
  } catch (err) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to load employee',
      life: 3000
    })
  }
})

function getInitials(name) {
  if (!name) return '?'

  const nameParts = name.split(' ')
  if (nameParts.length > 1) {
    return `${nameParts[0][0]}${nameParts[nameParts.length - 1][0]}`.toUpperCase()
  }
  return nameParts[0][0].toUpperCase()
}

function formatDate(dateString) {
  if (!dateString) return 'N/A'

  const date = new Date(dateString)
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date)
}

function formatCurrency(amount) {
  if (!amount) return 'N/A'

  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(amount)
}

function hasPermission(permission) {
  return authStore.hasPermission(permission)
}
</script>

<style scoped>
.employee-view {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  padding: 2rem;
  background-color: #f9fafb;
  min-height: 100vh;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1f2937;
}

.header-actions {
  display: flex;
  gap: 0.75rem;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: background-color 0.2s ease;
}

.btn-primary {
  background-color: #4f46e5;
  color: #fff;
  border: none;
}

.btn-primary:hover {
  background-color: #4338ca;
}

.btn-secondary {
  background-color: #e5e7eb;
  color: #111827;
  border: none;
}

.btn-secondary:hover {
  background-color: #d1d5db;
}

.loading-indicator,
.error-message {
  padding: 2rem;
  text-align: center;
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
}

.error-message {
  color: #dc2626;
}

.employee-details {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.employee-info {
  background-color: white;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.employee-header {
  display: flex;
  align-items: center;
  margin-bottom: 1.5rem;
}

.employee-avatar {
  width: 80px;
  height: 80px;
  background-color: #4f46e5;
  color: white;
  font-size: 1.75rem;
  font-weight: 700;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1.5rem;
}

.employee-name {
  font-size: 1.5rem;
  font-weight: 700;
  color: #111827;
}

.info-section {
  margin-bottom: 1.5rem;
}

.section-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 0.5rem;
  margin-bottom: 1rem;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}

@media (min-width: 640px) {
  .info-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

.info-item {
  display: flex;
  flex-direction: column;
}

.info-label {
  font-size: 0.875rem;
  color: #6b7280;
  margin-bottom: 0.25rem;
}

.info-value {
  font-weight: 600;
  color: #111827;
}

.notes {
  white-space: pre-line;
  color: #374151;
  font-size: 0.95rem;
}
</style>