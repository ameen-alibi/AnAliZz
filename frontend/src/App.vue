<script setup>
import { ref } from 'vue';
import LoginForm from '@/components/auth/LoginForm.vue';

// const authenticated = ref(false);

</script>

<template>
  <div class="min-h-screen w-full ">
    <!-- <nav class="bg-[#071E22] p-4 text-white flex justify-center items-center">
      <router-link to="/register" class="mr-4">Register</router-link>
      <router-link to="/login" class="mr-4">Login</router-link>
      <router-link to="/" class="mr-4">Home</router-link>
      <router-link to="/dashboard">Dashboard</router-link>
    </nav> -->
    <router-view />
  </div>
</template>
