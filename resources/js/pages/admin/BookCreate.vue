<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import { useRouter } from 'vue-router'

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

onMounted(async () => {
  categories.value = (await api.get('/categories')).data
})

const onPdfChange = e => (pdf.value = e.target.files[0] || null)
const onCoverChange = e => (cover.value = e.target.files[0] || null)

const submit = async () => {
  if (!pdf.value) {
    alert('File PDF wajib diunggah')
    return
  }

  const formData = new FormData()
  formData.append('title', title.value)
  formData.append('author', author.value)
  formData.append('publisher', publisher.value)
  formData.append('description', description.value)
  formData.append('stock', Number(stock.value))
  if (category_id.value) formData.append('category_id', category_id.value)
  formData.append('pdf', pdf.value)
  if (cover.value) formData.append('cover', cover.value)

  await api.post('/books', formData)
  router.push('/admin/books')
}
</script>

<template>
  <div class="max-w-3xl bg-white p-6 rounded-xl shadow">
    <h1 class="text-xl font-semibold mb-6">Tambah Buku</h1>

    <div class="space-y-4">
      <input v-model="title" placeholder="Judul Buku" class="w-full input" />
      <input v-model="author" placeholder="Penulis" class="w-full input" />
      <input v-model="publisher" placeholder="Penerbit" class="w-full input" />

      <select v-model="category_id" class="w-full input">
        <option value="">Pilih Kategori</option>
        <option v-for="c in categories" :key="c.id" :value="c.id">
          {{ c.name }}
        </option>
      </select>

      <textarea
        v-model="description"
        placeholder="Deskripsi"
        rows="4"
        class="w-full input"
      />

      <input type="number" v-model="stock" min="1" class="w-full input" />

      <input type="file" accept="application/pdf" @change="onPdfChange" />
      <input type="file" accept="image/*" @change="onCoverChange" />

      <button
        @click="submit"
        class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition"
      >
        Simpan
      </button>
    </div>
  </div>
</template>