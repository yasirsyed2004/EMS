<template>
  <div class="department-table-wrapper">
    <table class="table table-responsive table-secondary">
      <thead>
        <tr>
          <th>Name</th>
          <th>Code</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="department in departments" :key="department.id">
          <td>{{ department.name }}</td>
          <td>{{ department.code || "N/A" }}</td>
          <td>{{ department.description || "N/A" }}</td>
          <td>
            <div class="action-buttons">
              <router-link
                :to="`/departments/${department.id}/edit`"
                class="btn-action btn-edit no-underline"
                title="Edit"
                v-if="hasPermission('update_department')"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-edit-2"
                >
                  <path
                    d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"
                  ></path>
                </svg>
              </router-link>
              <button
                @click="$emit('delete', department)"
                class="btn-action btn-delete"
                title="Delete"
                v-if="hasPermission('delete_department')"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-trash-2"
                >
                  <polyline points="3 6 5 6 21 6"></polyline>
                  <path
                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                  ></path>
                  <line x1="10" y1="11" x2="10" y2="17"></line>
                  <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
              </button>
            </div>
          </td>
        </tr>
        <tr v-if="departments.length === 0">
          <td colspan="4" class="text-center">No departments found</td>
        </tr>
      </tbody>
    </table>
    <!-- Pagination -->
    <!-- console.log(totalPages) -->
  </div>
</template>

<script setup>
defineProps({
  departments: Array,
  currentPage: Number,
  totalPages: Number,
  hasPermission: Function,
});
defineEmits(["delete", "change-page"]);
</script>

<style scoped>
.department-table-wrapper {
  width: 100%;
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
</style>
