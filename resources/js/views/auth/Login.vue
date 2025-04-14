<template>
  <div class="login-page">
    <div class="overlay">
      <div class="form-container">
        <div class="text-center mb-6">
          <h2 class="text-3xl font-extrabold text-gray-900">Employee Management System</h2>
          <p class="text-sm text-gray-600 mt-1">Sign in to your account</p>
        </div>

        <LoginForm v-model:email="email" v-model:password="password" :loading="loading" :error="error"
          @update:email="email = $event" @update:password="password = $event" @submit="handleLogin" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import { useToast } from 'primevue/usetoast'
import LoginForm from '../../components/auth/AuthForm.vue'

const router = useRouter()
const authStore = useAuthStore()
const toast = useToast()

const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  if (!email.value || !password.value) {
    toast.add({
      severity: 'warn',
      summary: 'Validation Error',
      detail: 'All fields are required',
      life: 5000
    })
    return
  }

  error.value = ''
  loading.value = true

  try {
    const result = await authStore.login({ email: email.value, password: password.value })

    if (result?.code == 401 || !authStore.token) {
      toast.add({
        severity: 'error',
        summary: 'Login Failed',
        detail: result.message || 'Invalid Email or Password',
        life: 3000
      })
      loading.value = false
      return
    }
    const roles = authStore.user?.roles || []

    if (roles.some(role => role.name === 'manager')) {
      setTimeout(() => {
        // router.push('/employees')
        window.location.href = "/employees"
      }, 100)
    } else if (roles.some(role => role.name === 'employee')) {
      setTimeout(() => {
        router.push('/profile')
      }, 100)
    } else {
      // fallback route if no matching role
      setTimeout(() => {
        router.push('/dashboard')
      }, 100)
    }
  } catch (err) {
    const msg = err.response?.data?.message || 'Login failed. Please check your credentials.'
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: msg,
      life: 3000
    })
    error.value = msg
  } finally {
    loading.value = false
  }
}
</script>
<!-- views/login.vue -->
<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  position: relative;
  background: linear-gradient(to right, #67696c, #1e293b);
}

.overlay {
  backdrop-filter: blur(4px);
  background-color: rgba(255, 255, 255, 0.8);
  padding: 2rem;
  border-radius: 0.75rem;
  max-width: 400px;
  width: 100%;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}
</style>
