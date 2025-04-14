<template>
  <div class="department-list">
    <div class="list-header">
      <div class="search-box">
        <input type="text" v-model="searchQuery" @input="handleSearch" placeholder="Search departments..."
          class="form-control" />
      </div>
      <router-link to="/departments/create" class="btn btn-primary no-underline"
        v-if="hasPermission('create_department')">
        Add Department
      </router-link>
    </div>

    <div>
      <div v-if="loading">Loading...</div>
      <DepartmentTable :departments="departments" :currentPage="currentPage" :totalPages="totalPages"
        :hasPermission="hasPermission" @delete="confirmDelete" @change-page="changePage" />
      <!-- <div class="pagination-wrapper"> -->
      <Pagination :currentPage="currentPage" :totalPages="totalPages" @change-page="changePage" />
      <!-- </div> -->

    </div>

    <DeleteModal v-if="showDeleteModal" :item="departmentToDelete" @close="showDeleteModal = false"
      @confirm="deleteDepartment" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useDepartmentStore } from '../../stores/department'
import { useAuthStore } from '../../stores/auth'
import DepartmentTable from '../../components/department/DepartmentTable.vue'
import DeleteModal from '../../components/common/deleteModal.vue'
import Pagination from '../../components/common/Pagination.vue'

const departmentStore = useDepartmentStore()
const authStore = useAuthStore()

const departments = computed(() => departmentStore.departments)
const loading = computed(() => departmentStore.loading)


const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(1)
const showDeleteModal = ref(false)
const departmentToDelete = ref(null)

const hasPermission = (perm) => authStore.hasPermission(perm)


onMounted(fetchDepartments)

watch([currentPage, perPage], async () => {
  await fetchDepartments()
})
const totalPages = computed(() => {
  console.log(useDepartmentStore.totalRecords, 'dd')
  return Math.ceil((departmentStore.totalRecords || 0) / (perPage.value || 1))
})
function confirmDelete(dept) {
  departmentToDelete.value = dept
  showDeleteModal.value = true
}


function changePage(page) {
  if (page < 1 || page > totalPages.value) return
  currentPage.value = page
}

async function deleteDepartment() {
  await departmentStore.deleteDepartment(departmentToDelete.value.id)
  showDeleteModal.value = false
  await fetchDepartments()
}

async function fetchDepartments() {
  await departmentStore.fetchDepartments({
    page: currentPage.value,
    perPage: 1
  })
}

function handleSearch() {
  clearTimeout(searchTimeout.value)
  searchTimeout.value = setTimeout(() => {
    currentPage.value = 1
    fetchDepartments()
  }, 300)
}

const searchTimeout = ref(null)
</script>

<style scoped>
.department-list {
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


.btn-page {
  padding: 0.375rem 0.75rem;
  border: 1px solid var(--border-color);
  background-color: white;
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