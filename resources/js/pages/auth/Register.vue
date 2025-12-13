<script setup>
import { ref } from 'vue'
import api from '../../services/api'
import { useRouter } from 'vue-router'

const name = ref('')
const email = ref('')
const password = ref('')
const router = useRouter()
const error = ref('')

const register = async () => {
  error.value = ''
  try {
    await api.get('/sanctum/csrf-cookie')
    await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: password.value
    })
    router.push('/login')
  } catch {
    error.value = 'Gagal register'
  }
}
</script>

<template>
  <div>
    <h1>Register</h1>
    <form @submit.prevent="register">
      <input v-model="name" placeholder="Nama" />
      <input v-model="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Password" />
      <button>Register</button>
    </form>
    <p v-if="error">{{ error }}</p>
  </div>
</template>