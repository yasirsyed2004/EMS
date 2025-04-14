<template>
  <div class="employee-list">
    <div class="list-header">
      <div class="search-filter">
        <div class="search-box">
          <input type="text" v-model="searchQuery" placeholder="Search employees..." class="form-control"
            @input="handleSearch" />
        </div>
      </div>

      <router-link to="/employees/create" class="btn btn-primary mt-4" v-if="hasPermission('create_employee')">
        Add Employee
      </router-link>
    </div>

    <div>
      <div v-if="loading" class="loading-indicator">
        Loading employees...
      </div>

      <table v-else class="table table-responsive table-secondary">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>Position</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee in employees" :key="employee.id">
            <td>{{ employee.name }}</td>
            <td>{{ employee.email }}</td>
            <td>{{ employee.department?.name || 'N/A' }}</td>
            <td>{{ employee.position }}</td>
            <td>
              <span class="status-badge"
                :class="{ 'active': employee.status === 'active', 'inactive': employee.status === 'inactive' }">
                {{ employee.status }}
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <router-link :to="`/employees/${employee.id}`" class="btn-action btn-view" title="View"
                  v-if="hasPermission('show_employee')">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-eye">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                </router-link>

                <router-link :to="`/employees/${employee.id}/edit`" class="btn-action btn-edit" title="Edit"
                  v-if="hasPermission('update_employee')">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-edit-2">
                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                  </svg>
                </router-link>

                <button @click="confirmDelete(employee)" class="btn-action btn-delete" title="Delete"
                  v-if="hasPermission('delete_employee')">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trash-2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="employees.length === 0">
            <td colspan="6" class="text-center">No employees found</td>
          </tr>
        </tbody>
      </table>

      <Pagination :currentPage="currentPage" :totalPages="totalPages" @change-page="changePage" />
    </div>

    <!-- Delete Confirmation Modal -->
    <DeleteModal v-if="showDeleteModal" :item="employeeToDelete" @close="showDeleteModal = false"
    @confirm="deleteEmployee" />


  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useEmployeeStore } from '../../stores/employee'
import { useAuthStore } from '../../stores/auth'
import Pagination from '../../components/common/Pagination.vue'
import DeleteModal from '../../components/common/deleteModal.vue'

const router = useRouter()
const employeeStore = useEmployeeStore()
const authStore = useAuthStore()

const employees = computed(() => employeeStore.employees)
const loading = computed(() => employeeStore.loading)
const error = computed(() => employeeStore.error)
const pagination = computed(() => employeeStore.pagination)

const currentPage = ref(1)
const perPage = ref(10)
const searchQuery = ref('')
const searchTimeout = ref(null)
// const employeeToDelete = ref(null)


const showDeleteModal = ref(false)
const employeeToDelete = ref(null)




onMounted(async () => {
  await fetchEmployees()
})

const totalPages = computed(() => {
  return Math.ceil((employeeStore.totalRecords || 0) / (perPage.value || 1))
})

watch([currentPage, perPage], async () => {
  await fetchEmployees()
})


async function fetchEmployees() {
  await employeeStore.fetchEmployees({
    page: currentPage.value,
    perPage: perPage.value,
  })
}

async function deleteEmployee() {
  await employeeStore.deleteEmployee(employeeToDelete.value.id)
  showDeleteModal.value = false
  await fetchEmployees()
}
function handleSearch() {
  // Debounce search to avoid too many API calls
  if (searchTimeout.value) {
    clearTimeout(searchTimeout.value)
  }

  searchTimeout.value = setTimeout(async () => {
    currentPage.value = 1 // Reset to first page when searching
    await fetchEmployees()
  }, 300)
}

function changePage(page) {
  if (page < 1 || page > totalPages.value) return
  currentPage.value = page
}

function confirmDelete(employee) {
  employeeToDelete.value = employee
  showDeleteModal.value = true
}

// async function deleteEmployee() {
//   if (!employeeToDelete.value) return

//   try {
//     await employeeStore.deleteEmployee(employeeToDelete.value.id)
//     showDeleteModal.value = false
//     employeeToDelete.value = null

//     // Refresh the list
//     await fetchEmployees()
//   } catch (error) {
//     console.error('Failed to delete employee:', error)
//   }
// }

function hasPermission(permission) {
  return authStore.hasPermission(permission)
}
</script>

<style scoped>
.employee-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding-top: 5%;
}

.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.search-filter {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.search-box {
  width: 300px;
}

.loading-indicator {
  padding: 2rem;
  text-align: center;
  color: var(--text-light);
}

.status-badge {
  display: inline-block;
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: capitalize;
}

.status-badge.active {
  background-color: #d1fae5;
  color: #065f46;
}

.status-badge.inactive {
  background-color: #fee2e2;
  color: #b91c1c;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.btn-action {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 0.375rem;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-view {
  background-color: #e0f2fe;
  color: #0369a1;
}

.btn-view:hover {
  background-color: #bae6fd;
}

.btn-edit {
  background-color: #fef9c3;
  color: #854d0e;
}

.btn-edit:hover {
  background-color: #fef08a;
}

.btn-delete {
  background-color: #fee2e2;
  color: #b91c1c;
}

.btn-delete:hover {
  background-color: #fecaca;
}
</style>