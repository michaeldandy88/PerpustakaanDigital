<template>
  <section class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 py-8">
      <h1 class="text-3xl font-bold text-gray-800">Kelola Salinan Buku</h1>
      <p class="text-gray-600 mt-1">Manajemen salinan (eksemplar) buku.</p>

      <!-- Alerts -->
      <div v-if="error" class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">
        {{ error }}
      </div>
      <div v-if="notice" class="mt-4 rounded-lg border border-green-200 bg-green-50 p-3 text-green-700">
        {{ notice }}
      </div>

      <!-- Aksi -->
      <div class="mt-6 flex justify-end">
        <button
          @click="openCreateModal"
          class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
          :disabled="loading"
        >
          + Tambah Salinan
        </button>
      </div>

      <!-- Tabel -->
      <div class="mt-4 rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
          <thead>
            <tr class="bg-gray-100 text-gray-600 text-left">
              <th class="px-4 py-3">Buku</th>
              <th class="px-4 py-3">Barcode</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3">Lokasi</th>
              <th class="px-4 py-3 w-40">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" class="px-4 py-6 text-center text-gray-500">Memuat data…</td>
            </tr>

            <tr
              v-for="c in copies"
              :key="c.id"
              class="border-t hover:bg-gray-50"
            >
              <td class="px-4 py-3 font-medium">{{ c.book?.title || '-' }}</td>
              <td class="px-4 py-3">{{ c.barcode }}</td>
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
              <td class="px-4 py-3">{{ c.location || '-' }}</td>
              <td class="px-4 py-3 flex gap-3">
                <button class="text-blue-600 hover:underline" @click="openEditModal(c)">Edit</button>
                <button class="text-red-600 hover:underline" @click="confirmDelete(c)">Hapus</button>
              </td>
            </tr>

            <tr v-if="!loading && !copies.length">
              <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada salinan.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Tambah/Edit -->
      <div v-if="showForm" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">
          <h2 class="text-xl font-semibold text-gray-800 mb-4">
            {{ editing ? 'Edit Salinan' : 'Tambah Salinan' }}
          </h2>

          <form @submit.prevent="saveCopy" class="space-y-3">
            <label class="block">
              <span class="text-gray-600">Buku</span>
              <select
                v-model.number="form.book_id"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
              >
                <option :value="undefined" disabled>Pilih buku…</option>
                <option v-for="b in books" :key="b.id" :value="b.id">
                  {{ b.title }}
                </option>
              </select>
            </label>

            <label class="block">
              <span class="text-gray-600">Barcode</span>
              <input
                v-model="form.barcode"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
              />
            </label>

            <label class="block">
              <span class="text-gray-600">Status</span>
              <select
                v-model="form.status"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
              >
                <option value="tersedia">Tersedia</option>
                <option value="dipinjam">Dipinjam</option>
              </select>
            </label>

            <label class="block">
              <span class="text-gray-600">Tipe Salinan (opsional)</span>
              <input
                v-model="form.copy_type"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="mis: hardcopy, softcopy"
              />
            </label>

            <label class="block">
              <span class="text-gray-600">Lokasi (opsional)</span>
              <input
                v-model="form.location"
                class="w-full mt-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="mis: Rak A3"
              />
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
                {{ saving ? 'Menyimpan…' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Dialog hapus -->
      <div v-if="showDelete" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-xl p-6 shadow-lg">
          <h2 class="text-lg font-semibold text-gray-800">Hapus Salinan</h2>
          <p class="mt-2 text-gray-600">
            Yakin ingin menghapus salinan dengan barcode
            <span class="font-semibold">{{ pendingDelete?.barcode }}</span>?
          </p>
          <div class="flex justify-end gap-3 mt-6">
            <button class="px-4 py-2 rounded-lg border border-gray-300" @click="showDelete = false">Batal</button>
            <button class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700" @click="doDelete">Hapus</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { listBooks, listCopies, createCopyApi, updateCopyApi, deleteCopyApi } from '@/services/api'

type Copy = {
  id: number
  book_id: number
  barcode: string
  status: 'tersedia' | 'dipinjam'
  copy_type?: string | null
  location?: string | null
  book?: { id: number; title: string }
}
type Book = { id: number; title: string }

const loading = ref(true)
const saving = ref(false)
const error = ref<string | null>(null)
const notice = ref<string | null>(null)

const books = ref<Book[]>([])
const copies = ref<Copy[]>([])

const showForm = ref(false)
const editing = ref(false)
const form = ref<Partial<Copy>>({
  id: undefined,
  book_id: undefined,
  barcode: '',
  status: 'tersedia',
  copy_type: '',
  location: ''
})

const showDelete = ref(false)
const pendingDelete = ref<Copy | null>(null)

async function load() {
  loading.value = true
  error.value = null
  try {
    // buku untuk dropdown
    const bookData = await listBooks()
    books.value = Array.isArray(bookData) ? bookData : (bookData.data ?? [])

    // salinan (paginate 10 di backend)
    const copyData = await listCopies()
    copies.value = Array.isArray(copyData) ? copyData : (copyData.data ?? [])
  } catch (e: any) {
    error.value = e?.response?.data?.message || 'Gagal memuat data'
  } finally {
    loading.value = false
  }
}

function openCreateModal() {
  editing.value = false
  form.value = { id: undefined, book_id: undefined, barcode: '', status: 'tersedia', copy_type: '', location: '' }
  showForm.value = true
}

function openEditModal(c: Copy) {
  editing.value = true
  form.value = {
    id: c.id,
    book_id: c.book_id,
    barcode: c.barcode,
    status: c.status,
    copy_type: c.copy_type || '',
    location: c.location || ''
  }
  showForm.value = true
}

function closeForm() {
  showForm.value = false
}

async function saveCopy() {
  saving.value = true
  error.value = null
  notice.value = null
  try {
    if (!form.value.book_id || !form.value.barcode) {
      throw new Error('Buku dan barcode wajib diisi')
    }

    if (editing.value && form.value.id != null) {
      const updated = await updateCopyApi(form.value.id, {
        book_id: form.value.book_id,
        barcode: form.value.barcode,
        status: form.value.status as 'tersedia' | 'dipinjam',
        copy_type: form.value.copy_type || undefined,
        location: form.value.location || undefined
      })
      // update lokal
      const idx = copies.value.findIndex(c => c.id === updated.id)
      if (idx !== -1) copies.value[idx] = updated
      notice.value = 'Salinan diperbarui'
    } else {
      const created = await createCopyApi({
        book_id: form.value.book_id,
        barcode: form.value.barcode,
        status: form.value.status as 'tersedia' | 'dipinjam',
        copy_type: form.value.copy_type || undefined,
        location: form.value.location || undefined
      })
      // prepend
      copies.value.unshift(created)
      notice.value = 'Salinan ditambahkan'
    }

    showForm.value = false
  } catch (e: any) {
    error.value = e?.response?.data?.message || e.message || 'Gagal menyimpan salinan'
  } finally {
    saving.value = false
  }
}

function confirmDelete(c: Copy) {
  pendingDelete.value = c
  showDelete.value = true
}

async function doDelete() {
  if (!pendingDelete.value) return
  error.value = null
  notice.value = null
  try {
    await deleteCopyApi(pendingDelete.value.id)
    copies.value = copies.value.filter(c => c.id !== pendingDelete.value!.id)
    notice.value = 'Salinan dihapus'
  } catch (e: any) {
    error.value = e?.response?.data?.message || 'Gagal menghapus salinan'
  } finally {
    showDelete.value = false
    pendingDelete.value = null
  }
}

onMounted(load)
</script>
