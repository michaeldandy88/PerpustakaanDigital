<template>
  <section class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 py-8">
      <h1 class="text-3xl font-bold text-gray-800">Dashboard Pustakawan</h1>
      <p class="mt-1 text-gray-600">Ringkasan aktivitas perpustakaan.</p>

      <div v-if="error" class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">
        {{ error }}
      </div>

      <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
          <div class="text-sm text-gray-500">Jumlah buku</div>
          <div class="mt-2 text-3xl font-bold text-gray-900">
            {{ loading ? '…' : stats.totalBooks }}
          </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
          <div class="text-sm text-gray-500">Peminjaman aktif</div>
          <div class="mt-2 text-3xl font-bold text-gray-900">
            {{ loading ? '…' : stats.activeLoans }}
          </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
          <div class="text-sm text-gray-500">Terlambat dikembalikan</div>
          <div class="mt-2 text-3xl font-bold text-gray-900">
            {{ loading ? '…' : stats.overdueLoans }}
          </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
          <div class="text-sm text-gray-500">Total eksemplar</div>
          <div class="mt-2 text-3xl font-bold text-gray-900">
            {{ loading ? '…' : stats.totalCopies }}
          </div>
          <div class="mt-1 text-xs text-gray-500">
            Tersedia: {{ loading ? '…' : stats.availableCopies }} • Dipinjam: {{ loading ? '…' : stats.borrowedCopies }}
          </div>
        </div>
      </div>

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

      <div class="mt-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-800">Buku terbaru</h2>
        <ul class="mt-3 space-y-2">
          <li v-if="loading" class="text-gray-500">Memuat…</li>
          <li v-else-if="!stats.recentBooks?.length" class="text-gray-500">Belum ada data.</li>
          <li v-else v-for="b in stats.recentBooks" :key="b.id" class="flex justify-between">
            <span class="font-medium">{{ b.title }}</span>
            <span class="text-gray-500">{{ b.year ?? '-' }}</span>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { getLibrarianStats } from '@/services/api'

const loading = ref(true)
const error = ref<string | null>(null)
const stats = ref<any>({
  totalBooks: 0,
  activeLoans: 0,
  overdueLoans: 0,
  totalCopies: 0,
  availableCopies: 0,
  borrowedCopies: 0,
  recentBooks: []
})

onMounted(async () => {
  loading.value = true
  error.value = null
  try {
    stats.value = await getLibrarianStats()
  } catch (e: any) {
    error.value = e?.response?.data?.message || 'Gagal memuat statistik'
  } finally {
    loading.value = false
  }
})
</script>
