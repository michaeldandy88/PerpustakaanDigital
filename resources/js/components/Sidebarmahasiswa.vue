<script setup>
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const logout = async () => {
  try {
    await axios.post('/logout')
    router.push('/login')
  } catch (e) {
    console.error(e)
  }
}
</script>

<template>
  <aside class="w-64 min-h-screen bg-blue-700 text-white flex flex-col">
    <!-- HEADER -->
    <div class="px-6 py-5 text-xl font-bold border-b border-blue-600">
      Perpustakaan
    </div>

    <!-- MENU -->
    <nav class="flex-1 px-4 py-6 space-y-2">
      <router-link
        to="/dashboard"
        class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition"
        active-class="bg-blue-600"
      >
        Dashboard
      </router-link>

      <router-link
        to="/books"
        class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition"
        active-class="bg-blue-600"
      >
        Daftar Buku
      </router-link>

      <router-link
        to="/my-borrowings"
        class="block px-4 py-2 rounded-lg hover:bg-blue-600 transition"
        active-class="bg-blue-600"
      >
        Riwayat Peminjaman
      </router-link>
    </nav>
  </aside>
</template>