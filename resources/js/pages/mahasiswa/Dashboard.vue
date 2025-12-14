<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import DashboardLayout from '../../layouts/DashboardLayout.vue'
import DashboardLayoutMahasiswa from '../../layouts/DashboardLayoutMahasiswa.vue'
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
  <dashboard-layout-mahasiswa>
    <div>
    <h1 class="text-xl font-semibold mb-6">
      Dashboard Mahasiswa
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
      <router-link
        to="/books"
        class="bg-white p-5 rounded-xl shadow hover:shadow-md transition"
      >
        <h2 class="font-semibold text-gray-800">
          Daftar Buku
        </h2>
        <p class="text-sm text-gray-500">
          Lihat dan pinjam buku
        </p>
      </router-link>

      <router-link
        to="/my-borrowings"
        class="bg-white p-5 rounded-xl shadow hover:shadow-md transition"
      >
        <h2 class="font-semibold text-gray-800">
          Riwayat Peminjaman
        </h2>
        <p class="text-sm text-gray-500">
          Status buku yang dipinjam
        </p>
      </router-link>
    </div>
  </div>
  </dashboard-layout-mahasiswa>
</template>
