<template>
  <section class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-6 py-8">
      <div v-if="loading" class="text-gray-600">Memuat detail buku...</div>

      <div v-else-if="error" class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
        {{ error }}
      </div>

      <div v-else class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row gap-6">
          <div class="w-full sm:w-48">
            <div class="w-full h-64 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
              <img
                v-if="book.cover_url || book.cover_path"
                :src="book.cover_url || coverFallback()"
                class="object-cover w-full h-full"
                alt="cover"
                @error="imgErr"
              />
              <div v-else class="text-gray-400">No cover</div>
            </div>
          </div>

          <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-800">{{ book.title }}</h1>
            <div class="mt-2 text-sm text-gray-500">
              <span>{{ book.year || '-' }}</span>
              <span class="mx-1">•</span>
              <span>{{ book.isbn || '-' }}</span>
            </div>

            <p v-if="book.description" class="mt-4 text-gray-700 whitespace-pre-line">
              {{ book.description }}
            </p>

            <div class="mt-6 flex flex-wrap gap-3">
              <a
                v-if="book.pdf_url || book.pdf_path"
                :href="book.pdf_url || pdfFallback()"
                target="_blank"
                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
              >
                Buka PDF
              </a>

              <router-link
                to="/"
                class="inline-flex items-center rounded-md border px-4 py-2 text-gray-700 hover:bg-gray-100"
              >
                Kembali
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/services/api'

const route = useRoute()
const id = route.params.id as string | number

const book = ref<any>({})
const loading = ref(true)
const error = ref<string | null>(null)

const BASE = import.meta.env.VITE_API_BASE || 'http://127.0.0.1:8000'

function coverFallback() {
  if (!book.value.cover_path) return ''
  return `${BASE.replace(/\/$/, '')}/storage/${book.value.cover_path.replace(/^\/+/, '')}`
}
function pdfFallback() {
  if (!book.value.pdf_path) return ''
  return `${BASE.replace(/\/$/, '')}/storage/${book.value.pdf_path.replace(/^\/+/, '')}`
}
function imgErr(e: Event) {
  const img = e.target as HTMLImageElement
  img.style.display = 'none'
}

onMounted(async () => {
  loading.value = true
  error.value = null
  try {
    const res = await api.get(`/api/books/${id}`)
    book.value = res.data
  } catch (e: any) {
    error.value = e?.response?.data?.message || 'Gagal memuat data buku'
  } finally {
    loading.value = false
  }
})
</script>