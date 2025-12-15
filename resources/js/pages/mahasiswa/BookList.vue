<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import DashboardLayoutMahasiswa from '../../layouts/DashboardLayoutMahasiswa.vue'

const books = ref([])

const load = async () => {
  books.value = (await api.get('/student/books')).data
}

onMounted(load)

const borrow = async (bookId) => {
  try {
    await api.post('/borrowings', { book_id: bookId })
    alert('Buku berhasil dipinjam')
    load()
  } catch (e) {
    alert(e.response?.data?.message || 'Gagal meminjam buku')
  }
}
</script>

<template>
  <DashboardLayoutMahasiswa>
    <div>
      <h1 class="text-xl font-semibold mb-6">
        Daftar Buku
      </h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div
          v-for="b in books"
          :key="b.id"
          class="bg-white rounded-xl p-5 shadow flex flex-col"
        >
          <!-- COVER -->
          <div class="mb-4">
            <img
              v-if="b.cover_path"
              :src="`/storage/${b.cover_path}`"
              alt="Cover Buku"
              class="w-full h-48 object-cover rounded-lg border"
            />

            <div
              v-else
              class="w-full h-48 rounded-lg border
                     flex items-center justify-center
                     text-gray-400 text-sm"
            >
              Tidak ada cover
            </div>
          </div>

          <!-- KATEGORI (FIELD INFORMASI) -->
          <div v-if="b.category" class="mb-2">
            <span
              class="inline-block px-3 py-1 text-xs font-medium
                     bg-blue-100 text-blue-700 rounded-full"
            >
              {{ b.category }}
            </span>
          </div>

          <!-- JUDUL -->
          <h3 class="font-semibold text-gray-800 leading-tight">
            {{ b.title }}
          </h3>

          <!-- PENULIS -->
          <p class="text-sm text-gray-600 mt-1">
            Penulis: {{ b.author }}
          </p>

          <!-- DESKRIPSI -->
          <p
            v-if="b.description"
            class="text-sm text-gray-700 mt-2"
            style="
              display:-webkit-box;
              -webkit-line-clamp:3;
              -webkit-box-orient:vertical;
              overflow:hidden;
            "
          >
            {{ b.description }}
          </p>

          <!-- STOK -->
          <p class="text-sm mt-3">
            Stok:
            <span
              :class="b.stock > 0 ? 'text-green-600' : 'text-red-600'"
              class="font-medium"
            >
              {{ b.stock }}
            </span>
          </p>

          <!-- ACTION -->
          <div class="mt-auto pt-4 flex gap-2">
            <button
              @click="borrow(b.id)"
              :disabled="b.is_borrowed || b.stock < 1"
              class="flex-1 px-4 py-2 rounded-md text-sm font-medium
                     text-white transition
                     disabled:bg-gray-300 disabled:cursor-not-allowed
                     bg-blue-600 hover:bg-blue-700"
            >
              Pinjam
            </button>

            <router-link
              :to="`/books/${b.id}/read`"
              :class="[
                'flex-1 px-4 py-2 rounded-md text-sm font-medium text-center transition',
                b.is_borrowed
                  ? 'bg-green-600 text-white hover:bg-green-700'
                  : 'bg-gray-300 text-gray-500 pointer-events-none'
              ]"
            >
              Baca
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayoutMahasiswa>
</template>