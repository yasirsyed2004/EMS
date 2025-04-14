<template>
  <div class="container my-5">
    <div class="row gy-4">
      <!-- Left Side: Profile -->
      <div class="col-lg-6">
        <UserProfile />
      </div>

      <!-- Right Side: Change Password -->
      <div class="col-lg-6">
        <ChangePassword />
      </div>
    </div>
  </div>

</template>

<script setup>
import UserProfile from '../../components/auth/Profile.vue'
import ChangePassword from '../../components/auth/ChangePassword.vue'
</script>