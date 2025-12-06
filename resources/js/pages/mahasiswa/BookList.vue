<template>
  <section class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold text-gray-800">Katalog Buku</h1>

      <!-- Loading -->
      <div v-if="loading" class="mt-6 text-gray-600">Memuat buku...</div>

      <!-- Content -->
      <div v-else class="mt-6">
        <!-- Search -->
        <input
          v-model="q"
          @input="search"
          placeholder="Cari judul atau ISBN..."
          class="w-full max-w-xl rounded-lg border border-gray-300 bg-white px-4 py-2
                 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        />

        <!-- List -->
        <ul class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <li
            v-for="(book, idx) in filtered"
            :key="book?.id ?? idx"
            v-if="books"
            class="rounded-xl border border-gray-200 bg-white p-0 shadow-sm hover:shadow-md transition"
          >
            <router-link
              :to="{ name: 'book.detail', params: { id: book.id } }"
              class="block p-4 h-full"
            >
              <div class="flex flex-col h-full">
                <!-- cover -->
                <div class="w-full h-44 bg-gray-100 rounded-md overflow-hidden flex items-center justify-center">
                  <img
                    v-if="getCover(book)"
                    :src="getCover(book)"
                    :alt="book.title"
                    class="object-cover w-full h-full"
                    @error="onImgError"
                  />
                  <div v-else class="text-gray-400 text-sm px-3">No cover</div>
                </div>

                <!-- meta -->
                <div class="mt-3">
                  <div class="font-semibold text-gray-900 line-clamp-2">{{ book.title }}</div>
                  <div class="mt-1 text-sm text-gray-500">
                    <span>{{ book.year || '-' }}</span>
                    <span class="mx-1">•</span>
                    <span>{{ book.isbn || '-' }}</span>
                  </div>
                  <p v-if="book.description" class="mt-3 text-sm leading-relaxed text-gray-700 line-clamp-3">
                    {{ book.description }}
                  </p>
                </div>

                <!-- actions -->
                <div class="mt-auto pt-3 flex items-center justify-between gap-3">
                  <button
                    v-if="getPdfUrl(book)"
                    @click.stop.prevent="openPdf(book)"
                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700"
                  >
                    Buka PDF
                  </button>

                </div>
              </div>
            </router-link>
          </li>
        </ul>

        <!-- Empty state -->
        <div
          v-if="!filtered.length"
          class="mt-6 rounded-lg border border-dashed border-gray-300 bg-white p-6 text-center text-gray-600"
        >
          Tidak ada buku ditemukan.
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { fetchBooks } from '@/services/api'

type Book = {
  id: number
  title: string
  isbn?: string
  year?: number
  description?: string
  cover_url?: string | null
  cover_path?: string | null
  pdf_url?: string | null
  pdf_path?: string | null
}

const books = ref<Book[]>([])
const loading = ref(true)
const q = ref('')

const BASE = import.meta.env.VITE_API_BASE || 'http://127.0.0.1:8000'

/**
 * Robust load:
 * - handles responses shaped as array or { data: [...] }
 * - filters out falsy elements (null/undefined)
 * - logs useful info for debugging
 */
async function load() {
  console.log('[BookList] load() start')
  loading.value = true
  try {
    const res = await fetchBooks()
    console.log('[BookList] fetchBooks raw response:', res)

    // helper: cari array pertama dalam objek (depth-first, max depth 3)
    function findArray(obj: any, depth = 0): any[] | null {
      if (!obj || depth > 3) return null
      if (Array.isArray(obj)) return obj
      if (typeof obj !== 'object') return null

      // common keys to try first
      const keysPrefer = ['data', 'items', 'results']
      for (const k of keysPrefer) {
        if (k in obj) {
          const found = findArray(obj[k], depth + 1)
          if (found) return found
        }
      }

      // fallback: iterate all keys
      for (const k in obj) {
        if (!Object.prototype.hasOwnProperty.call(obj, k)) continue
        const found = findArray(obj[k], depth + 1)
        if (found) return found
      }
      return null
    }

    // Normalize: if res is array -> use it; otherwise try to find nested array
    let items: any[] | null = null
    if (Array.isArray(res)) {
      items = res
    } else if (res && typeof res === 'object') {
      // axios wrapper sometimes returns { data: <payload> }
      const maybe = (res as any).data ?? res
      items = findArray(maybe)
    }

    if (!items) {
      console.warn('[BookList] unexpected books payload, expected array but got:', res)
      books.value = []
    } else {
      books.value = items.filter((it: any) => !!it && typeof it === 'object')
    }

    console.log('[BookList] normalized books count:', books.value.length)
  } catch (err) {
    console.error('[BookList] fetchBooks error:', err)
    books.value = []
  } finally {
    loading.value = false
    console.log('[BookList] load() done, loading=', loading.value, 'books.length=', books.value.length)
  }
}

function search() {
  // hanya trigger computed
}

const filtered = computed(() => {
  const list = books.value ?? []
  if (!q.value) return list
  const term = q.value.toLowerCase()
  return list.filter(b =>
    ((b.title || '') as string).toLowerCase().includes(term) ||
    ((b.isbn || '') as string).toLowerCase().includes(term)
  )
})

function getCover(book: Book): string | undefined {
  if (!book) return undefined
  if (book.cover_url) return book.cover_url
  if (book.cover_path) {
    return `${BASE.replace(/\/$/, '')}/storage/${book.cover_path.replace(/^\/+/, '')}`
  }
  return undefined
}

function getPdfUrl(book: Book): string | undefined {
  if (!book) return undefined
  if (book.pdf_url) return book.pdf_url
  if (book.pdf_path) {
    return `${BASE.replace(/\/$/, '')}/storage/${book.pdf_path.replace(/^\/+/, '')}`
  }
  return undefined
}

function openPdf(book: Book) {
  const url = getPdfUrl(book)
  if (!url) return
  window.open(url, '_blank')
}

function onImgError(e: Event) {
  const img = e.target as HTMLImageElement
  img.style.display = 'none'
  img.removeAttribute('src')
}

onMounted(load)
</script>

<style scoped>
.line-clamp-2 { display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; overflow: hidden; }
.line-clamp-3 { display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3; overflow: hidden; }
</style>
