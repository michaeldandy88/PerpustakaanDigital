<template>
  <section class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 py-8">
      <h1 class="text-3xl font-bold text-gray-800">Kelola Buku</h1>
      <p class="text-gray-600 mt-1">Manajemen daftar buku di perpustakaan.</p>

      <!-- Alerts -->
      <div
        v-if="error"
        class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700"
      >
        {{ error }}
      </div>
      <div
        v-if="notice"
        class="mt-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-700"
      >
        {{ notice }}
      </div>

      <!-- Aksi cepat -->
      <div class="mt-6 flex justify-end">
        <button
          @click="openCreateModal"
          class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
          :disabled="loading"
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
              <th class="px-4 py-3 w-40">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                Memuat data...
              </td>
            </tr>

            <tr
              v-for="b in books"
              :key="b.id"
              class="border-t hover:bg-gray-50"
            >
              <td class="px-4 py-3 font-medium">{{ b.title }}</td>
              <td class="px-4 py-3">{{ b.isbn || '-' }}</td>
              <td class="px-4 py-3">{{ b.year || '-' }}</td>
              <td class="px-4 py-3 flex gap-3">
                <button
                  class="text-blue-600 hover:underline"
                  @click="openEditModal(b)"
                >
                  Edit
                </button>
                <button
                  class="text-red-600 hover:underline"
                  @click="confirmDelete(b)"
                >
                  Hapus
                </button>
              </td>
            </tr>

            <tr v-if="!loading && !books.length">
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
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">
            {{ editing ? 'Edit Buku' : 'Tambah Buku' }}
          </h2>

          <form @submit.prevent="saveBook" class="space-y-3">
            <label class="block">
              <span class="text-gray-600">Judul</span>
              <input
                v-model="form.title"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
              />
            </label>

            <label class="block">
              <span class="text-gray-600">ISBN</span>
              <input
                v-model="form.isbn"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
              />
            </label>

            <label class="block">
              <span class="text-gray-600">Tahun</span>
              <input
                v-model.number="form.year"
                type="number"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
              />
            </label>

            <label class="block">
              <span class="text-gray-600">Deskripsi</span>
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </label>

            <!-- Upload Cover -->
            <label class="block">
              <span class="text-gray-600">Cover (gambar)</span>
              <input
                type="file"
                accept="image/*"
                class="mt-1 block w-full text-sm"
                @change="onCoverChange"
              />
              <div v-if="coverPreview" class="mt-2">
                <img
                  :src="coverPreview"
                  alt="Preview cover"
                  class="w-24 h-auto rounded border"
                />
              </div>
              <div v-else-if="editing && form.cover_url" class="mt-2 text-sm">
                <span class="text-gray-600">Cover sudah ada.</span>
              </div>
            </label>

            <!-- Upload PDF -->
            <label class="block">
              <span class="text-gray-600">File PDF</span>
              <input
                type="file"
                accept="application/pdf"
                class="mt-1 block w-full text-sm"
                @change="onPdfChange"
              />
              <div v-if="pdfName" class="mt-2 text-sm text-gray-700">
                Dipilih: {{ pdfName }}
              </div>
              <div v-else-if="editing && form.pdf_url" class="mt-2 text-sm">
                <span class="text-gray-600">PDF sudah ada.</span>
              </div>
            </label>

            <div class="flex justify-end gap-3 pt-2">
              <button
                type="button"
                @click="closeForm"
                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
              >
                Batal
              </button>

              <button
                type="submit"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50"
                :disabled="saving"
              >
                {{ saving ? 'Menyimpan...' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Dialog hapus sederhana -->
      <div
        v-if="showDelete"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
      >
        <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">
          <h2 class="text-lg font-semibold text-gray-800">Hapus Buku</h2>
          <p class="mt-2 text-gray-600">
            Yakin ingin menghapus
            <span class="font-semibold">{{ pendingDelete?.title }}</span>?
          </p>
          <div class="flex justify-end gap-3 mt-6">
            <button
              class="px-4 py-2 rounded-lg border border-gray-300"
              @click="showDelete = false"
            >
              Batal
            </button>
            <button
              class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700"
              @click="doDelete"
            >
              Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { listBooks, createBookApi, updateBookApi, deleteBookApi } from '@/services/api'

type Book = {
  id: number
  title: string
  isbn?: string
  year?: number
  description?: string
  cover_url?: string | null
  pdf_url?: string | null
}

const books = ref<Book[]>([])
const loading = ref(true)
const saving = ref(false)
const error = ref<string | null>(null)
const notice = ref<string | null>(null)

const showForm = ref(false)
const editing = ref(false)
const form = ref<any>({
  id: undefined,
  title: '',
  isbn: '',
  year: undefined,
  description: '',
  cover_url: undefined,
  pdf_url: undefined,
})

const coverFile = ref<File | null>(null)
const pdfFile = ref<File | null>(null)
const coverPreview = ref<string | null>(null)
const pdfName = ref<string | null>(null)

const showDelete = ref(false)
const pendingDelete = ref<Book | null>(null)

async function load() {
  loading.value = true
  error.value = null
  try {
    const data = await listBooks()
    books.value = Array.isArray(data) ? data : (data.data ?? [])
  } catch (e: any) {
    error.value = e?.response?.data?.message || 'Gagal memuat buku'
  } finally {
    loading.value = false
  }
}

function openCreateModal() {
  editing.value = false
  form.value = {
    id: undefined,
    title: '',
    isbn: '',
    year: undefined,
    description: '',
    cover_url: undefined,
    pdf_url: undefined,
  }
  coverFile.value = null
  pdfFile.value = null
  coverPreview.value = null
  pdfName.value = null
  showForm.value = true
}

function openEditModal(b: Book) {
  editing.value = true
  form.value = {
    id: b.id,
    title: b.title,
    isbn: b.isbn ?? '',
    year: b.year,
    description: b.description ?? '',
    cover_url: b.cover_url ?? null,
    pdf_url: b.pdf_url ?? null,
  }
  coverFile.value = null
  pdfFile.value = null
  coverPreview.value = null
  pdfName.value = null
  showForm.value = true
}

function closeForm() {
  showForm.value = false
}

function onCoverChange(e: Event) {
  const input = e.target as HTMLInputElement
  const file = input.files?.[0] ?? null
  coverFile.value = file
  if (file) {
    coverPreview.value = URL.createObjectURL(file)
  } else {
    coverPreview.value = null
  }
}

function onPdfChange(e: Event) {
  const input = e.target as HTMLInputElement
  const file = input.files?.[0] ?? null
  pdfFile.value = file
  pdfName.value = file ? file.name : null
}

async function saveBook() {
  saving.value = true
  error.value = null
  notice.value = null

  try {
    const fd = new FormData()
    fd.append('title', form.value.title || '')
    fd.append('isbn', form.value.isbn || '')
    if (form.value.year != null) {
      fd.append('year', String(form.value.year))
    }
    fd.append('description', form.value.description || '')

    if (coverFile.value) {
      fd.append('cover', coverFile.value)
    }

    if (pdfFile.value) {
      fd.append('pdf', pdfFile.value)
    }

    if (editing.value && form.value.id != null) {
      // _method=PUT agar Laravel terima multipart update
      fd.append('_method', 'PUT')
      const updated = await updateBookApi(form.value.id, fd)
      const idx = books.value.findIndex(b => b.id === updated.id)
      if (idx !== -1) books.value[idx] = updated
      notice.value = 'Buku diperbarui'
    } else {
      const created = await createBookApi(fd)
      books.value.unshift(created)
      notice.value = 'Buku ditambahkan'
    }

    showForm.value = false
  } catch (e: any) {
    error.value = e?.response?.data?.message || e.message || 'Gagal menyimpan buku'
  } finally {
    saving.value = false
  }
}

function confirmDelete(b: Book) {
  pendingDelete.value = b
  showDelete.value = true
}

async function doDelete() {
  if (!pendingDelete.value) return
  error.value = null
  notice.value = null
  try {
    await deleteBookApi(pendingDelete.value.id)
    books.value = books.value.filter(b => b.id !== pendingDelete.value!.id)
    notice.value = 'Buku dihapus'
  } catch (e: any) {
    error.value = e?.response?.data?.message || 'Gagal menghapus buku'
  } finally {
    showDelete.value = false
    pendingDelete.value = null
  }
}

onMounted(load)
</script>