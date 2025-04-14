<template>
  <div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Dashboard</h1>

    <!-- Stats overview -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <!-- Total Employees -->
      <div class="card">
        <div class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-grow">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Total Employees
                </dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl font-semibold text-gray-900">
                    {{ stats.totalEmployees }}
                  </div>
                </dd>
              </dl>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="text-sm">
            <router-link to="/employees" class="font-medium text-primary hover:text-primary no-underline">
              View all<span class="sr-only"> employees</span>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Departments -->
      <div class="card">
        <div class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-grow">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Departments
                </dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl font-semibold text-gray-900">
                    {{ stats.totalDepartments }}
                  </div>
                </dd>
              </dl>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="text-sm">
            <router-link to="/departments" class="font-medium text-primary hover:text-primary no-underline">
              View all<span class="sr-only"> departments</span>
            </router-link>
          </div>
        </div>
      </div>

      <!-- User Role -->
      <div class="card">
        <div class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
              </svg>
            </div>
            <div class="ml-5 w-0 flex-grow">
              <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                  Your Role
                </dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl font-semibold text-gray-900 capitalize">
                    {{ user?.roles[0]?.name || 'N/A' }}
                  </div>
                </dd>
              </dl>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="text-sm">
            <router-link to="/profile" class="font-medium text-primary hover:text-primary no-underline">
              View profile<span class="sr-only"> details</span>
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent employees -->
    <h2 class="text-lg font-medium text-gray-900 mt-8 mb-4">Recent Employees</h2>
    <div class="card">
      <ul v-if="recentEmployees.length" role="list" class="divide-y divide-gray-200">
        <li v-for="employee in recentEmployees" :key="employee.id">
          <router-link :to="`/employees/${employee.id}/edit`" class="block hover:bg-gray-50 no-underline">
            <div class="px-4 py-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-primary font-semibold text-sm">
                      {{ getInitials(employee.name) }}
                    </span>
                  </div>
                  <div class="ml-4">
                    <p class="text-sm font-medium text-primary truncate">
                      {{ employee.name }}
                    </p>
                    <p class="text-sm text-gray-500">
                      {{ employee.email }}
                    </p>
                  </div>
                </div>
                <div class="ml-2 flex-shrink-0 flex">
                  <p class="badge badge-success">
                    {{ employee.department?.name || 'No Department' }}
                  </p>
                </div>
              </div>
            </div>
          </router-link>
        </li>
      </ul>
      <div v-else class="px-4 py-5 text-center text-gray-500">
        No employees found
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useEmployeeStore } from '../stores/employee'
import { useDepartmentStore } from '../stores/department'

const authStore = useAuthStore()
const employeeStore = useEmployeeStore()
const departmentStore = useDepartmentStore()

const user = computed(() => authStore.user)
const recentEmployees = ref([])
const stats = ref({
  totalEmployees: 0,
  totalDepartments: 0
})

const employeesPromise = employeeStore.fetchEmployees({
  page: 1,
  perPage: 5,
})
const departmentsPromise = departmentStore.fetchDepartments()

onMounted(async () => {
  try {
    const [employees, departments] = await Promise.all([
      employeesPromise,
      departmentsPromise,
    ])

    recentEmployees.value = employees

    stats.value = {
      totalEmployees: employeeStore.totalRecords,
      totalDepartments: departmentStore.departments.length,
    }
  } catch (error) {
    console.error('Error loading dashboard data:', error)
  }
})

const getInitials = (firstName, lastName) => {
  return `${firstName?.charAt(0) || ''}${lastName?.charAt(0) || ''}`.toUpperCase()
}
</script>