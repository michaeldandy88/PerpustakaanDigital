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
            v-for="book in filtered"
            :key="book.id"
            class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition"
          >
            <div class="font-semibold text-gray-900">{{ book.title }}</div>
            <div class="mt-1 text-sm text-gray-500">
              <span>{{ book.year || '-' }}</span>
              <span class="mx-1">•</span>
              <span>{{ book.isbn || '-' }}</span>
            </div>
            <p v-if="book.description" class="mt-3 text-sm leading-relaxed text-gray-700">
              {{ book.description }}
            </p>
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

const books = ref<Array<any>>([])
const loading = ref(true)
const q = ref('')

async function load() {
  loading.value = true
  try {
    const res = await fetchBooks()
    books.value = res.data ?? res
  } finally {
    loading.value = false
  }
}

function search() {
  // hanya memicu computed
}

const filtered = computed(() => {
  if (!q.value) return books.value
  const term = q.value.toLowerCase()
  return books.value.filter(b =>
    (b.title || '').toLowerCase().includes(term) ||
    (b.isbn || '').toLowerCase().includes(term)
  )
})

onMounted(load)
</script>
