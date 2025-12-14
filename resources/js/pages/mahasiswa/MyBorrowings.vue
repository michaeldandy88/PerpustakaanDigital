<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const borrowings = ref([])

const load = async () => {
  borrowings.value = (await api.get('/my-borrowings')).data
}

onMounted(load)

const returnBook = async (id) => {
  if (!confirm('Kembalikan buku ini?')) return

  try {
    await api.post(`/borrowings/${id}/return`)
    alert('Buku berhasil dikembalikan')
    load()
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal mengembalikan buku')
  }
}
</script>

<template>
  <div>
    <h1 class="text-xl font-semibold mb-6">
      Riwayat Peminjaman
    </h1>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left">Judul Buku</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="b in borrowings"
            :key="b.id"
            class="border-t"
          >
            <td class="px-4 py-3">
              {{ b.book.title }}
            </td>

            <td class="px-4 py-3">
              <span
                :class="
                  b.status === 'dipinjam'
                    ? 'text-yellow-600'
                    : 'text-green-600'
                "
                class="font-medium"
              >
                {{ b.status }}
              </span>
            </td>

            <td class="px-4 py-3 text-center">
              <button
                v-if="b.status === 'dipinjam'"
                @click="returnBook(b.id)"
                class="px-3 py-1 rounded-md bg-blue-600
                       text-white text-sm hover:bg-blue-700"
              >
                Kembalikan
              </button>

              <span
                v-else
                class="text-gray-400 text-sm"
              >
                —
              </span>
            </td>
          </tr>

          <tr v-if="borrowings.length === 0">
            <td colspan="3" class="px-4 py-6 text-center text-gray-500">
              Belum ada riwayat peminjaman
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
