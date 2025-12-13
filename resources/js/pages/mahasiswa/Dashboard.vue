<script setup>
import api from '../../services/api'
import { useRouter } from 'vue-router'

const router = useRouter()

const logout = async () => {
  await api.post('/logout')
  router.push('/login')
}
</script>

<template>
  <div>
    <h1>Dashboard Mahasiswa</h1>
    <button @click="logout">Logout</button>
  </div>
</template>