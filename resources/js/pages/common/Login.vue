<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4">
    <div class="w-full max-w-sm bg-white shadow-md rounded-xl p-6">

      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Login
      </h2>

      <form @submit.prevent="doLogin" class="space-y-4">

        <input
          v-model="email"
          type="email"
          placeholder="Email (mahasiswa@..., dosen@..., pustakawan@...)"
          class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        />

        <input
          v-model="password"
          type="password"
          placeholder="Password"
          class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        />

        <button
          class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition"
        >
          Login
        </button>

      </form>

      <p
        v-if="error"
        class="mt-4 text-center text-red-600 text-sm font-medium"
      >
        {{ error }}
      </p>

    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { login } from '@/services/auth'

const email = ref('')
const password = ref('')
const error = ref<string|null>(null)
const router = useRouter()

async function doLogin() {
  error.value = null
  try {
    await login(email.value, password.value)
    router.push({ name: 'books' })
  } catch (e: any) {
    error.value = e.message || 'Login failed'
  }
}
</script>