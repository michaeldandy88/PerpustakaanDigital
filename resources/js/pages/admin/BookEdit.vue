<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import DashboardLayout from '../../layouts/DashboardLayout.vue'

const route = useRoute()
const router = useRouter()

const title = ref('')
const author = ref('')
const publisher = ref('')
const category_id = ref('')
const description = ref('')
const stock = ref(1)

const pdf = ref(null)
const cover = ref(null)

const categories = ref([])
const book = ref(null)

onMounted(async () => {
  categories.value = (await api.get('/categories')).data

  const res = await api.get(`/books/${route.params.id}`)
  book.value = res.data

  // isi nilai awal (PENTING)
  title.value = book.value.title
  author.value = book.value.author
  publisher.value = book.value.publisher
  category_id.value = book.value.category_id ?? ''
  description.value = book.value.description ?? ''
  stock.value = book.value.stock
})

const onPdfChange = (e) => {
  pdf.value = e.target.files[0] || null
}

const onCoverChange = (e) => {
  cover.value = e.target.files[0] || null
}

const submit = async () => {
  const formData = new FormData()

  // field WAJIB (sesuai validasi backend)
  formData.append('title', title.value)
  formData.append('author', author.value)
  formData.append('publisher', publisher.value)
  formData.append('stock', Number(stock.value))

  // optional
  if (description.value) {
    formData.append('description', description.value)
  }

  if (category_id.value) {
    formData.append('category_id', category_id.value)
  }

  if (pdf.value) {
    formData.append('pdf', pdf.value)
  }

  if (cover.value) {
    formData.append('cover', cover.value)
  }

  // spoof PUT
  formData.append('_method', 'PUT')

  try {
    await api.post(`/books/${route.params.id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    router.push('/admin/books')
  } catch (e) {
    console.error(e.response?.data)
    alert('Gagal update buku')
  }
}
</script>

<template>
  <DashboardLayout>
    <div v-if="book" class="max-w-3xl bg-white p-6 rounded-xl shadow">
      <h1 class="text-xl font-semibold mb-6">
        Edit Buku
      </h1>

      <div class="space-y-4">
        <!-- Judul -->
        <input
          v-model="title"
          placeholder="Judul Buku"
          class="w-full px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <!-- Penulis -->
        <input
          v-model="author"
          placeholder="Penulis"
          class="w-full px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <!-- Penerbit -->
        <input
          v-model="publisher"
          placeholder="Penerbit"
          class="w-full px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <!-- Kategori -->
        <select
          v-model="category_id"
          class="w-full px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Pilih Kategori</option>
          <option
            v-for="c in categories"
            :key="c.id"
            :value="c.id"
          >
            {{ c.name }}
          </option>
        </select>

        <!-- Deskripsi -->
        <textarea
          v-model="description"
          rows="4"
          placeholder="Deskripsi"
          class="w-full px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <!-- Stok -->
        <input
          type="number"
          min="0"
          v-model="stock"
          class="w-full px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <!-- PDF Saat Ini -->
        <div class="text-sm">
          <p class="font-medium">PDF saat ini</p>
          <a
            :href="`/storage/${book.pdf_path}`"
            target="_blank"
            class="text-blue-600 underline"
          >
            Lihat PDF
          </a>
        </div>

        <!-- Ganti PDF -->
        <input
          type="file"
          accept="application/pdf"
          @change="onPdfChange"
        />

        <!-- Cover Saat Ini -->
        <div v-if="book.cover_path">
          <p class="font-medium text-sm mt-2">
            Cover saat ini
          </p>
          <img
            :src="`/storage/${book.cover_path}`"
            class="w-24 mt-1 rounded"
          />
        </div>

        <!-- Ganti Cover -->
        <input
          type="file"
          accept="image/*"
          @change="onCoverChange"
        />

        <!-- Action -->
        <div class="flex gap-3 pt-4">
          <button
            @click="submit"
            class="px-4 py-2 rounded-md bg-blue-600 text-white
                   text-sm font-medium hover:bg-blue-700 transition"
          >
            Update
          </button>

          <button
            @click="router.push('/admin/books')"
            class="px-4 py-2 rounded-md bg-gray-200
                   text-sm hover:bg-gray-300"
          >
            Batal
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>
