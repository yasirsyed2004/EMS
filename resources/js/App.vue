<template>
  <!-- Show loader while checking auth -->
  <div v-if="waitingForRoute" class="auth-loading-screen">
    <div class="spinner">...</div>
  </div>

  <!-- Once initialized -->
  <div v-else :class="[
    'app-container',
    isAuthenticated ? 'authenticated' : '',
    isCollapsed ? 'sidebar-collapsed' : ''
  ]">
    <Header v-if="isAuthenticated" @toggle-sidebar="handleSidebarToggle" />
    <Toast />

    <div v-if="isAuthenticated" class="main-layout">
      <Sidebar :is-collapsed="isCollapsed" @toggle-collapse="handleSidebarToggle" />
      <main class="main-content">
        <router-view />
      </main>
    </div>

    <main v-else class="public-content">
      <router-view />
    </main>

    <Footer v-if="isAuthenticated" />
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth'
import Header from './components/app/AppHeader.vue'
import Footer from './components/app/Footer.vue'
import Sidebar from './components/app/AppSidebar.vue'

const authStore = useAuthStore()
const router = useRouter()
// const route = useRoute()


const isCollapsed = ref(false)
const initializing = ref(true) 
const waitingForRoute = ref(false)


const isAuthenticated = computed(() => authStore.isAuthenticated)

const handleSidebarToggle = () => {
  isCollapsed.value = !isCollapsed.value
}

onMounted(async () => {
  try {
    if (authStore.token) {
      await authStore.getCurrentUser()
    }
  } catch (error) {
    console.error('Error checking authentication:', error)
    authStore.logout()
    router.push('/login')
  } finally {
    initializing.value = false // 🔹 Done initializing
  }
})
// Detect when layout changes too early
router.beforeEach((to, from, next) => {
  waitingForRoute.value = true
  next()
})

router.afterEach(() => {
  setTimeout(() => {
    waitingForRoute.value = false
  }, 50) // give layout a bit of time
})

</script>


<style scoped>
.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #f8fafc;
}

.main-layout {
  display: flex;
  flex-grow: 1;
  min-height: calc(100vh - 64px);
  /* adjust to header height */
}



.main-content,
.public-content {
  flex: 1;
  padding: 1rem 1.5rem;
  /* background: linear-gradient(to right, #eef2f3, #2c3842); */
  padding-top: 64px;
  transition: all 0.3s ease;
}

.public-content {
  background: linear-gradient(to right, #67696c, #1e293b);

}

@media (max-width: 1024px) {

  .authenticated .main-content,
  .authenticated.sidebar-collapsed .main-content {
    margin-left: 0 !important;

  }

  .main-content,
  .public-content {
    padding: 1rem;
    padding-top: 64px;
  }
}

.main-content {
  transition: margin-left 0.3s ease;
}

/* Optional: override overflow */
html,
body {
  overflow-x: hidden;
}

* {
  transition: all 0.2s ease;
}

/* Default main content when sidebar is not collapsed */
.authenticated .main-content {
  margin-left: 250px;
}

/* When sidebar is collapsed */
.sidebar-collapsed .main-content {
  margin-left: 70px;
}


/* Mobile view */
/* @media (max-width: 1024px) {
  .authenticated .main-content {
    margin-left: 0 !important;
    padding: 1rem;
  }
} */
</style>
