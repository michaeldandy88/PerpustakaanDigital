<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'

const route = useRoute()
const router = useRouter()
const book = ref(null)

onMounted(async () => {
  try {
    const res = await api.get(`/books/${route.params.id}/read`)
    book.value = res.data
  } catch (e) {
    if (e.response?.status === 403) {
      alert('Anda belum meminjam buku ini')
      router.push('/books')
    }
  }
})
</script>

<template>
  <div v-if="book" class="space-y-4">
    <h1 class="text-xl font-semibold">
      {{ book.title }}
    </h1>

    <iframe
      :src="`/storage/${book.pdf_path}`"
      class="w-full h-[600px] border rounded"
    />
  </div>
</template>
