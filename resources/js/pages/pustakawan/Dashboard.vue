<template>
  <section class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 py-8">
      <h1 class="text-3xl font-bold text-gray-800">Dashboard Pustakawan</h1>
      <p class="mt-1 text-gray-600">Ringkasan aktivitas perpustakaan.</p>

      <!-- Stat Cards -->
      <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
          <div class="text-sm text-gray-500">Jumlah buku</div>
          <div class="mt-2 text-3xl font-bold text-gray-900">123</div>
          <div class="mt-1 text-xs text-gray-500">mock data</div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
          <div class="text-sm text-gray-500">Peminjaman aktif</div>
          <div class="mt-2 text-3xl font-bold text-gray-900">5</div>
          <div class="mt-1 text-xs text-gray-500">mock data</div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
          <div class="text-sm text-gray-500">Terlambat dikembalikan</div>
          <div class="mt-2 text-3xl font-bold text-gray-900">0</div>
          <div class="mt-1 text-xs text-gray-500">mock data</div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="mt-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800">Aksi cepat</h2>
        <div class="mt-4 flex flex-wrap gap-3">
          <router-link
            :to="{ name: 'pustakawan.manageBooks' }"
            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
          >
            Kelola Buku
          </router-link>

          <router-link
            :to="{ name: 'pustakawan.manageCopies' }"
            class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
          >
            Kelola Eksemplar
          </router-link>
        </div>
      </div>
    </div>
  </section>
</template>