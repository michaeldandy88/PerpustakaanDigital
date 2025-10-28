<template>
  <div class="min-h-screen bg-gray-100 text-gray-800 flex flex-col">
    <!-- NAVBAR -->
    <header class="bg-white shadow-sm">
      <div class="max-w-6xl mx-auto flex items-center gap-4 px-6 py-4 text-sm font-medium">

        <router-link to="/" class="hover:text-blue-600">Beranda</router-link>
        <span class="text-gray-400">|</span>

        <router-link to="/my/assignments" class="hover:text-blue-600">Tugas</router-link>
        <span class="text-gray-400">|</span>

        <router-link
          v-if="role === 'pustakawan'"
          to="/pustakawan/dashboard"
          class="hover:text-blue-600"
        >
          Dashboard
        </router-link>
        <span v-if="role === 'pustakawan'" class="text-gray-400">|</span>

        <router-link
          v-if="role === 'dosen'"
          to="/dosen/assignments/create"
          class="hover:text-blue-600"
        >
          Buat Tugas
        </router-link>
        <span v-if="role === 'dosen'" class="text-gray-400">|</span>

        <router-link
          v-if="!isAuth"
          to="/login"
          class="hover:text-blue-600"
        >
          Login
        </router-link>

        <button
          v-if="isAuth"
          @click="logoutAction"
          class="ml-auto rounded-md bg-red-600 px-3 py-1.5 text-white text-sm hover:bg-red-700"
        >
          Logout
        </button>

      </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="flex-1">
      <div class="max-w-6xl mx-auto px-6 py-8">
        <router-view />
      </div>
    </main>
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