<template>
  <div>
    <header>
      <router-link to="/">Beranda</router-link> |
      <router-link to="/my/assignments">Tugas</router-link> |
      <router-link v-if="role==='pustakawan'" to="/pustakawan/dashboard">Dashboard</router-link> |
      <router-link v-if="role==='dosen'" to="/dosen/assignments/create">Buat Tugas</router-link> |
      <router-link to="/login" v-if="!isAuth">Login</router-link>
      <button v-if="isAuth" @click="logout">Logout</button>
    </header>

    <main><router-view /></main>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { getUserRole, isAuthenticated, logout } from '@/services/auth'

const router = useRouter()
const isAuth = computed(() => isAuthenticated())
const role = computed(() => getUserRole())

async function logoutAction() {
  await logout()
  router.push({ name: 'login' })
}
</script>
