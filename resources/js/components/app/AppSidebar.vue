<template>
  <aside class="app-sidebar" :class="{ 'collapsed': isCollapsed }">
    <div class="sidebar-header">
      <router-link to="/dashboard" class="logo-container no-underline">
        <span class="logo">EMS</span>
        <span class="logo-text">Employee MS</span>
      </router-link>
      <button class="collapse-btn" @click="toggleSidebar">
        <span class="collapse-icon"></span>
      </button>
    </div>
    <div class="sidebar-content">
      <nav class="sidebar-nav">
        <ul class="nav-list">
          <li class="nav-item">
            <router-link to="/dashboard" class="nav-link no-underline" :class="{ 'active': isActive('/') }"
              v-if="hasRole('admin')">
              <span class="nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="feather feather-home">
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                  <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
              </span>
              <span class="nav-text">Dashboard</span>
            </router-link>
          </li>
          <li class="nav-item" v-if="hasPermission('show_employee')">
            <router-link to="/employees" class="nav-link no-underline" :class="{ 'active': isActive('/employees') }">
              <span class="nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="feather feather-users">
                  <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
              </span>
              <span class="nav-text">Employees</span>
            </router-link>
          </li>
          <li class="nav-item" v-if="hasPermission('show_department') && hasRole('admin')">
            <router-link to="/departments" class="nav-link" :class="{ 'active': isActive('/departments') }">
              <span class="nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="feather feather-briefcase">
                  <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
              </span>
              <span class="nav-text">Departments</span>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/profile" class="nav-link" :class="{ 'active': isActive('/profile') }">
              <span class="nav-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="feather feather-user">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </span>
              <span class="nav-text">Profile</span>
            </router-link>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const route = useRoute()
const authStore = useAuthStore()
const isCollapsed = ref(false)

function toggleSidebar() {
  isCollapsed.value = !isCollapsed.value
  document.body.classList.toggle('sidebar-collapsed')
}

const activeRoute = computed(() => route.path)

function isActive(path) {
  return activeRoute.value === path || activeRoute.value.startsWith(`${path}/`)
}

function hasPermission(permission) {
  return authStore.hasPermission(permission)
}
function hasRole(roleName) {
  return authStore.hasRole(roleName)
}

</script>

<style scoped>
.app-sidebar {
  width: 250px;
  background-color: #1e293b;
  color: #e2e8f0;
  height: 100vh;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 100;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

/* .app-sidebar.collapsed {
    width: 70px;
  } */
.app-sidebar {
  width: 250px;
  min-width: 250px;
  transition: width 0.3s ease;
}

/* When collapsed */
.app-sidebar.collapsed {
  width: 100px;
  min-width: 70px;
}

.sidebar-header {
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo-container {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: white;
}

.logo {
  font-weight: 700;
  font-size: 1.25rem;
  color: white;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo-text {
  font-weight: 600;
  margin-left: 0.75rem;
  font-size: 1.125rem;
  transition: opacity 0.3s ease;
}

.collapsed .logo-text {
  opacity: 0;
  width: 0;
  overflow: hidden;
}

.collapse-btn {
  background: none;
  border: none;
  color: #e2e8f0;
  cursor: pointer;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

.collapse-icon {
  position: relative;
  width: 16px;
  height: 16px;
}

.collapse-icon::before,
.collapse-icon::after {
  content: '';
  position: absolute;
  background-color: currentColor;
  width: 10px;
  height: 2px;
  transition: transform 0.3s ease;
}

.collapse-icon::before {
  transform: rotate(45deg);
  top: 7px;
  left: 0;
}

.collapse-icon::after {
  transform: rotate(-45deg);
  top: 7px;
  right: 0;
}

.collapsed .collapse-icon::before {
  transform: rotate(-45deg);
}

.collapsed .collapse-icon::after {
  transform: rotate(45deg);
}

.sidebar-content {
  flex: 1;
  overflow-y: auto;
  padding: 1rem 0;
}

.nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.nav-item {
  margin-bottom: 0.25rem;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: #e2e8f0;
  text-decoration: none;
  transition: all 0.2s;
  border-radius: 0.375rem;
  margin: 0 0.5rem;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
  background-color: var(--primary-color);
  color: white;
}

.nav-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  height: 24px;
  margin-right: 0.75rem;
  flex-shrink: 0;
}

.nav-text {
  transition: opacity 0.3s ease;
  white-space: nowrap;
}

.collapsed .nav-text {
  opacity: 0;
  width: 0;
  overflow: hidden;
}

@media (max-width: 768px) {
  .app-sidebar {
    transform: translateX(-100%);
  }

  .app-sidebar.active {
    transform: translateX(0);
  }
}
</style>