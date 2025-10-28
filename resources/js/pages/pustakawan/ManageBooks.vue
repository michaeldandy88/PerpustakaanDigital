<template>
  <section class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 py-8">
      <h1 class="text-3xl font-bold text-gray-800">Kelola Buku</h1>
      <p class="text-gray-600 mt-1">Manajemen daftar buku di perpustakaan.</p>

      <!-- Aksi cepat -->
      <div class="mt-6 flex justify-end">
        <button
          @click="openCreateModal"
          class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
        >
          + Tambah Buku
        </button>
      </div>

      <!-- Tabel Buku -->
      <div class="mt-4 rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
          <thead>
            <tr class="bg-gray-100 text-gray-600 text-left">
              <th class="px-4 py-3">Judul</th>
              <th class="px-4 py-3">ISBN</th>
              <th class="px-4 py-3">Tahun</th>
              <th class="px-4 py-3 w-32">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="b in books"
              :key="b.id"
              class="border-t hover:bg-gray-50"
            >
              <td class="px-4 py-3 font-medium">{{ b.title }}</td>
              <td class="px-4 py-3">{{ b.isbn }}</td>
              <td class="px-4 py-3">{{ b.year }}</td>
              <td class="px-4 py-3 flex gap-2">
                <button
                  class="text-blue-600 hover:underline"
                  @click="openEditModal(b)"
                >
                  Edit
                </button>
                <button
                  class="text-red-600 hover:underline"
                  @click="deleteBook(b.id)"
                >
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="!books.length">
              <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                Belum ada buku.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Tambah/Edit -->
      <div
        v-if="showForm"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">
            {{ editing ? 'Edit Buku' : 'Tambah Buku' }}
          </h2>

          <form @submit.prevent="saveBook">
            <label class="block mb-3">
              <span class="text-gray-600">Judul</span>
              <input
                v-model="form.title"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
              />
            </label>

            <label class="block mb-3">
              <span class="text-gray-600">ISBN</span>
              <input
                v-model="form.isbn"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
              />
            </label>

            <label class="block mb-3">
              <span class="text-gray-600">Tahun</span>
              <input
                v-model="form.year"
                type="number"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
              />
            </label>

            <div class="flex justify-end gap-3 mt-4">
              <button
                type="button"
                @click="closeForm"
                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
              >
                Batal
              </button>

              <button
                type="submit"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700"
              >
                Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const books = ref<Array<any>>([
  { id: 1, title: 'Pemrograman Laravel', isbn: '978602', year: 2023 },
  { id: 2, title: 'Algoritma & Struktur Data', isbn: '978603', year: 2022 },
])

// Form State
const showForm = ref(false)
const editing = ref(false)
const form = ref<any>({ id: null, title: '', isbn: '', year: '' })

function openCreateModal() {
  editing.value = false
  form.value = { id: null, title: '', isbn: '', year: '' }
  showForm.value = true
}

function openEditModal(book: any) {
  editing.value = true
  form.value = { ...book }
  showForm.value = true
}

function closeForm() {
  showForm.value = false
}

// Mock CRUD
function saveBook() {
  if (editing.value) {
    const index = books.value.findIndex(b => b.id === form.value.id)
    if (index !== -1) books.value[index] = { ...form.value }
  } else {
    books.value.push({ ...form.value, id: Date.now() })
  }
  showForm.value = false
}

function deleteBook(id: number) {
  books.value = books.value.filter(b => b.id !== id)
}
</script>
