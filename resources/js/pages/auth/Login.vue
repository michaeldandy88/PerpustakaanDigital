<script setup>
import { ref } from 'vue'
import api from '../../services/api'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const router = useRouter()
const error = ref('')

const login = async () => {
  error.value = ''
  try {
    await api.get('/sanctum/csrf-cookie')
    await api.post('/login', {
      email: email.value,
      password: password.value
    })

    const user = (await api.get('/user')).data

    if (user.role === 'admin') {
      router.push('/admin')
    } else {
      router.push('/dashboard')
    }
  } catch {
    error.value = 'Email atau password salah'
  }
}
</script>

<template>
  <div>
    <h1>Login</h1>
    <form @submit.prevent="login">
      <input v-model="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password" />
      <button>Login</button>
    </form>
    <p v-if="error">{{ error }}</p>
  </div>
</template>