<script setup>
import { ref } from 'vue'
import api from '../../services/api'
import { useRouter } from 'vue-router'
import AuthLayout from '../../layouts/AuthLayout.vue'

const name = ref('')
const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const register = async () => {
  error.value = ''
  try {
    await api.get('/sanctum/csrf-cookie')
    await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password.value,
    })
    router.push('/login')
  } catch {
    error.value = 'Gagal register'
  }
}
</script>

<template>
  <AuthLayout>
    <div class="w-full max-w-sm bg-white p-6 rounded-xl shadow">
      <h1 class="text-xl font-semibold text-center mb-6">Register</h1>

      <form @submit.prevent="register" class="space-y-4">
        <input
          v-model="name"
          placeholder="Nama"
          class="w-full px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <input
          v-model="email"
          type="email"
          placeholder="Email"
          class="w-full px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <input
          v-model="password"
          type="password"
          placeholder="Password"
          class="w-full px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <p v-if="error" class="text-sm text-red-600">
          {{ error }}
        </p>

        <button
          type="submit"
          class="w-full px-4 py-2 rounded-md bg-blue-600 text-white
                 font-medium hover:bg-blue-700 transition"
        >
          Register
        </button>
      </form>
    </div>
  </AuthLayout>
</template>