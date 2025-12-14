<script setup>
import { useRouter } from 'vue-router'
import { onMounted } from 'vue'
import api from '../services/api'

onMounted(async () => {
  try {
    await api.get('/user')
    router.push('/dashboard')
  } catch {
  }})

const router = useRouter()
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white rounded-2xl shadow p-8 text-center">
      <h1 class="text-2xl font-bold text-gray-800 mb-3">
        Perpustakaan Digital
      </h1>

      <p class="text-gray-600 text-sm mb-8">
        Sistem peminjaman dan membaca buku digital
        untuk mahasiswa secara online.
      </p>

      <div class="flex flex-col gap-3">
        <button
          @click="router.push('/login')"
          class="w-full px-4 py-2 rounded-md bg-blue-600
                 text-white font-medium hover:bg-blue-700 transition"
        >
          Masuk
        </button>

        <button
          @click="router.push('/register')"
          class="w-full px-4 py-2 rounded-md border
                 border-gray-300 text-gray-700
                 hover:bg-gray-50 transition"
        >
          Daftar
        </button>
      </div>
    </div>
  </div>
</template>
