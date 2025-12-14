<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import DashboardLayout from '../../layouts/DashboardLayout.vue'

const borrowings = ref([])

// formatter tanggal Indonesia
const formatTanggal = (date) => {
  if (!date) return '-'

  const d = new Date(date)

  return new Intl.DateTimeFormat('id-ID', {
    dateStyle: 'long',
    timeStyle: 'short',
    timeZone: 'Asia/Jakarta',
  }).format(d) + ' WIB'
}

onMounted(async () => {
  borrowings.value = (await api.get('/borrowings')).data
})
</script>

<template>
  <DashboardLayout>
    <h1 class="text-xl font-semibold mb-6">
      Data Peminjaman
    </h1>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left">Mahasiswa</th>
            <th class="px-4 py-3 text-left">Buku</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-left">Waktu Peminjaman</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="b in borrowings"
            :key="b.id"
            class="border-t"
          >
            <!-- Nama Mahasiswa -->
            <td class="px-4 py-3">
              {{ b.user?.name || '-' }}
            </td>

            <!-- Judul Buku -->
            <td class="px-4 py-3">
              {{ b.book?.title || '-' }}
            </td>

            <!-- Status -->
            <td class="px-4 py-3">
              <span
                :class="
                  b.status === 'dipinjam'
                    ? 'text-yellow-600'
                    : 'text-green-600'
                "
                class="font-medium capitalize"
              >
                {{ b.status }}
              </span>
            </td>

            <!-- Tanggal & Waktu Indonesia -->
            <td class="px-4 py-3 text-sm text-gray-500">
              {{ formatTanggal(b.borrow_date ?? b.created_at) }}
            </td>
          </tr>

          <tr v-if="borrowings.length === 0">
            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
              Tidak ada data peminjaman
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </DashboardLayout>
</template>