<template>
  <header class="fixed top-0 left-0 right-0 z-40 h-16">
    <div class="h-full flex items-center justify-end px-4 sm:px-6 lg:px-8">
      <!-- Avatar & Dropdown -->
      <div class="relative">
        <button @click="showDropdown = !showDropdown" id="user-menu-button" type="button" class="focus:outline-none">
          <img v-if="user?.avatar"
            :src="user.avatar ?? 'https://ui-avatars.com/api/?name=Ali+M&background=4f46e5&color=fff'" alt="User avatar"
            class="w-10 h-10 rounded-full ring-1 ring-indigo-200 object-cover" />
          <div v-else
            class="w-10 h-10 rounded-full flex items-center justify-center bg-indigo-100 text-primary font-semibold ring-1 ring-indigo-200">
            {{ userInitials }}
          </div>
        </button>

        <!-- Dropdown -->
        <!-- Dropdown -->
        <div v-if="showDropdown" class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg z-50">
          <div class="px-4 py-3 text-sm text-black font-medium">
            {{ userFullName }}
          </div>
          <router-link to="/profile"
            class="flex items-center gap-4 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline"
            @click="showDropdown = false">
            <span>👤</span> Your Profile
          </router-link>
          <button @click="logout"
            class="flex items-center gap-4 w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline">
            <span>🚪</span> Sign out
          </button>
        </div>


      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const showDropdown = ref(false)
const user = computed(() => authStore.user)
const userFullName = computed(() => user.value.name || '')
// console.log(user.value.name)
const userInitials = computed(() => {
  if (!user.value) return ''
  return `${user.value.name?.[0] || ''}`.toUpperCase()
})

// Click outside to close
onMounted(() => {
  const handleClick = (event) => {
    if (
      showDropdown.value &&
      !event.target.closest('#user-menu-button') &&
      !event.target.closest('.fade-enter-active')
    ) {
      showDropdown.value = false
    }
  }
  window.addEventListener('click', handleClick)
  onUnmounted(() => {
    window.removeEventListener('click', handleClick)
  })
})

const logout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

header {
  background-color: transparent;
  /* makes it fully transparent */
  backdrop-filter: blur(8px);
  /* optional: adds a blur effect */
  -webkit-backdrop-filter: blur(8px);
  /* for Safari support */
  /* border-bottom: 1px solid rgba(255, 255, 255, 0.1);  */
}

button {
  border: none;
}
</style>
