<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import DashboardLayout from '../../layouts/DashboardLayout.vue'

const router = useRouter()
const books = ref([])

const load = async () => {
  books.value = (await api.get('/books')).data
}

onMounted(load)

const remove = async (id) => {
  if (!confirm('Hapus buku ini?')) return
  await api.delete(`/books/${id}`)
  load()
}
</script>

<template>
  <DashboardLayout>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-semibold">Data Buku</h1>

      <!-- Tambah Buku -->
      <button
        @click="router.push('/admin/books/create')"
        class="px-4 py-2 rounded-md bg-blue-600 text-white
               text-sm font-medium hover:bg-blue-700 transition"
      >
        + Tambah Buku
      </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left">Judul</th>
            <th class="px-4 py-3 text-left">Penulis</th>
            <th class="px-4 py-3 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="b in books"
            :key="b.id"
            class="border-t"
          >
            <td class="px-4 py-3">{{ b.title }}</td>
            <td class="px-4 py-3">{{ b.author }}</td>
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <!-- Edit -->
                <button
                  @click="router.push(`/admin/books/${b.id}/edit`)"
                  class="px-3 py-1 rounded-md bg-yellow-500
                         text-white text-sm hover:bg-yellow-600"
                >
                  Edit
                </button>

                <!-- Hapus -->
                <button
                  @click="remove(b.id)"
                  class="px-3 py-1 rounded-md bg-red-600
                         text-white text-sm hover:bg-red-700"
                >
                  Hapus
                </button>
              </div>
            </td>
          </tr>

          <!-- Empty state -->
          <tr v-if="books.length === 0">
            <td colspan="3" class="px-4 py-6 text-center text-gray-500">
              Belum ada data buku
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </DashboardLayout>
</template>