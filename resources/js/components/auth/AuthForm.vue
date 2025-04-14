<template>
  <form class="space-y-6" @submit.prevent="$emit('submit')">
    <div>
      <input
        :value="email"
        @input="updateEmail"
        type="email"
        required
        placeholder="Email address"
        class="form-control w-full rounded-t-md"
      />
      <input
        :value="password"
        @input="updatePassword"
        type="password"
        required
        placeholder="Password"
        class="form-control w-full rounded-b-md mt-1"
      />
    </div>

    <div v-if="error" class="text-sm text-red-600 text-center">
      {{ error }}
    </div>

    <button type="submit" :disabled="loading" class="btn btn-primary w-full relative">
      <span v-if="loading" class="absolute left-4 top-2.5">
        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
          <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
          />
          <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4zm2 5.29A7.96 7.96 0 014 12H0c0 3.04 1.13 5.82 3 7.94l3-2.65z"
          />
        </svg>
      </span>
      {{ loading ? "Signing in..." : "Sign in" }}
    </button>
  </form>
</template>

<script setup>
defineProps(["email", "password", "loading", "error"]);
const emit = defineEmits(["update:email", "update:password"]);

const updateEmail = (e) => {
  emit("update:email", e.target.value);
};
const updatePassword = (e) => {
  emit("update:password", e.target.value);
};
</script>

<style scoped>
.form-control {
  padding: 0.75rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  width: 100%;
}

.btn-primary {
  background-color: var(--color-primary, #4f46e5);
  color: white;
  padding: 0.75rem;
  font-weight: 600;
  border-radius: 0.375rem;
}

.btn-primary:hover {
  background-color: var(--color-primary-hover, #4338ca);
}
</style>
