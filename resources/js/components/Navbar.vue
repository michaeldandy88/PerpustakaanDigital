<script setup>
import api from '../services/api'
import { useRouter } from 'vue-router'

const router = useRouter()

const logout = async () => {
  try {
    await api.post(
      '/logout',
      null,
      {
        baseURL: '/',
      }
    )

    router.push('/login')
  } catch (e) {
    console.error(e.response?.data)
    alert('Gagal logout')
  }
}
</script>

<template>
  <header
    class="bg-white px-5 py-3 flex items-center justify-between shadow-sm"
  >
    <strong class="text-gray-800 text-base">
      Perpustakaan Digital
    </strong>

    <button
      @click="logout"
      class="px-4 py-2 rounded-md bg-red-600 text-white
             text-sm font-medium hover:bg-red-700 transition"
    >
      Logout
    </button>
  </header>
</template>
