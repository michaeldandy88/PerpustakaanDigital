<template>
  <section class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 py-8">
      <h1 class="text-3xl font-bold text-gray-800">Kelola Salinan Buku</h1>
      <p class="text-gray-600 mt-1">
        Manajemen salinan (eksemplar) buku dalam perpustakaan.
      </p>

      <!-- tombol tambah -->
      <div class="mt-6 flex justify-end">
        <button
          @click="openCreateModal"
          class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
        >
          + Tambah Salinan
        </button>
      </div>

      <!-- tabel -->
      <div
        class="mt-4 rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto"
      >
        <table class="min-w-full text-sm text-gray-700">
          <thead>
            <tr class="bg-gray-100 text-gray-600 text-left">
              <th class="px-4 py-3">Buku</th>
              <th class="px-4 py-3">Kode Inventaris</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3 w-32">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="c in copies"
              :key="c.id"
              class="border-t hover:bg-gray-50"
            >
              <td class="px-4 py-3 font-medium">{{ c.book_title }}</td>
              <td class="px-4 py-3">{{ c.inventory_code }}</td>
              <td class="px-4 py-3">
                <span
                  class="inline-block px-2 py-1 rounded-full text-xs font-semibold"
                  :class="c.status === 'tersedia'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700'"
                >
                  {{ c.status }}
                </span>
              </td>
              <td class="px-4 py-3 flex gap-2">
                <button
                  class="text-blue-600 hover:underline"
                  @click="openEditModal(c)"
                >
                  Edit
                </button>
                <button
                  class="text-red-600 hover:underline"
                  @click="deleteCopy(c.id)"
                >
                  Hapus
                </button>
              </td>
            </tr>

            <tr v-if="!copies.length">
              <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                Belum ada salinan.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- modal -->
      <div
        v-if="showForm"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">
            {{ editing ? 'Edit Salinan' : 'Tambah Salinan' }}
          </h2>

          <form @submit.prevent="saveCopy">
            <label class="block mb-3">
              <span class="text-gray-600">Nama Buku</span>
              <input
                v-model="form.book_title"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
              />
            </label>

            <label class="block mb-3">
              <span class="text-gray-600">Kode Inventaris</span>
              <input
                v-model="form.inventory_code"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
              />
            </label>

            <label class="block mb-3">
              <span class="text-gray-600">Status</span>
              <select
                v-model="form.status"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
              >
                <option value="tersedia">Tersedia</option>
                <option value="dipinjam">Dipinjam</option>
              </select>
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

// data mock
const copies = ref<Array<any>>([
  { id: 1, book_title: 'Pemrograman Laravel', inventory_code: 'INV-01', status: 'tersedia' },
  { id: 2, book_title: 'Algoritma', inventory_code: 'INV-02', status: 'dipinjam' },
])

const showForm = ref(false)
const editing = ref(false)
const form = ref<any>({
  id: null,
  book_title: '',
  inventory_code: '',
  status: 'tersedia',
})

function openCreateModal() {
  editing.value = false
  form.value = { id: null, book_title: '', inventory_code: '', status: 'tersedia' }
  showForm.value = true
}

function openEditModal(c: any) {
  editing.value = true
  form.value = { ...c }
  showForm.value = true
}

function closeForm() {
  showForm.value = false
}

function saveCopy() {
  if (editing.value) {
    const idx = copies.value.findIndex(c => c.id === form.value.id)
    if (idx !== -1) copies.value[idx] = { ...form.value }
  } else {
    copies.value.push({ ...form.value, id: Date.now() })
  }
  showForm.value = false
}

function deleteCopy(id: number) {
  copies.value = copies.value.filter(c => c.id !== id)
}
</script>