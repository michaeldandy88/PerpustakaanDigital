<script setup>
import { ref } from 'vue'
import api from '../../services/api'
import { useRouter } from 'vue-router'
import AuthLayout from '../../layouts/AuthLayout.vue'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const login = async () => {
  error.value = ''
  try {
    // WAJIB: ambil CSRF cookie dari WEB (bukan /api)
    await api.get('/sanctum/csrf-cookie', {
      baseURL: '/',
    })

    // WAJIB: login ke WEB route /login
    await api.post(
      '/login',
      {
        email: email.value,
        password: password.value,
      },
      {
        baseURL: '/',
      }
    )

    router.push('/dashboard')
  } catch (e) {
    error.value = 'Login gagal'
  }
}
</script>

<template>
  <AuthLayout>
    <div class="w-full max-w-sm bg-white p-6 rounded-xl shadow">
      <h2 class="text-xl font-semibold text-center mb-6">
        Login
      </h2>

      <div class="space-y-4">
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
          @click="login"
          class="w-full px-4 py-2 rounded-md bg-blue-600 text-white
                 font-medium hover:bg-blue-700 transition"
        >
          Login
        </button>
      </div>
    </div>
  </AuthLayout>
</template>