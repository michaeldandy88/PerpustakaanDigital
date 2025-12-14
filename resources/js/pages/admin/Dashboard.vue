<script setup>
import DashboardLayout from '../../layouts/DashboardLayout.vue'
</script>

<template>
  <DashboardLayout>
    <h1 class="text-xl font-semibold mb-6">Dashboard Admin</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <router-link
        to="/admin/books"
        class="bg-white p-5 rounded-xl shadow hover:shadow-md transition"
      >
        <h2 class="font-semibold text-gray-800 mb-1">
          Kelola Buku
        </h2>
        <p class="text-sm text-gray-500">
          Tambah, edit, dan hapus data buku
        </p>
      </router-link>

      <router-link
        to="/admin/categories"
        class="bg-white p-5 rounded-xl shadow hover:shadow-md transition"
      >
        <h2 class="font-semibold text-gray-800 mb-1">
          Kelola Kategori
        </h2>
        <p class="text-sm text-gray-500">
          Manajemen kategori buku
        </p>
      </router-link>

      <router-link
        to="/admin/borrowings"
        class="bg-white p-5 rounded-xl shadow hover:shadow-md transition"
      >
        <h2 class="font-semibold text-gray-800 mb-1">
          Pengembalian Buku
        </h2>
        <p class="text-sm text-gray-500">
          Proses pengembalian & status pinjaman
        </p>
      </router-link>
    </div>
  </DashboardLayout>
</template>
