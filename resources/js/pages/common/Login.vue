<template>
  <div class="login-page">
    <h2>Login (mock)</h2>
    <form @submit.prevent="doLogin">
      <input v-model="email" placeholder="email (mahasiswa@..., dosen@..., pustakawan@...)" />
      <input v-model="password" type="password" placeholder="password" />
      <button>Login</button>
    </form>
    <p v-if="error" style="color:red">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { loginMock } from '@/services/auth'

const email = ref('')
const password = ref('')
const error = ref<string|null>(null)
const router = useRouter()

async function doLogin() {
  error.value = null
  try {
    await loginMock(email.value, password.value)
    router.push({ name: 'books' })
  } catch (e: any) {
    error.value = e.message || 'Login failed'
  }
}
</script>