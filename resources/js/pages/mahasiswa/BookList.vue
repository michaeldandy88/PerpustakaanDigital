<template>
  <section>
    <h1>Katalog Buku</h1>

    <div v-if="loading">Memuat buku...</div>

    <div v-else>
      <input v-model="q" @input="search" placeholder="Cari judul atau ISBN..." class="search" />

      <ul class="book-list">
        <li v-for="book in filtered" :key="book.id" class="book-item">
          <div class="title">{{ book.title }}</div>
          <div class="meta">
            <small>{{ book.year || '-' }} • {{ book.isbn || '-' }}</small>
          </div>
          <p class="desc" v-if="book.description">{{ book.description }}</p>
        </li>
      </ul>

      <div v-if="!filtered.length">Tidak ada buku ditemukan.</div>
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
    // API bisa mengembalikan { data: [...] } atau [...]
    books.value = res.data ?? res
  } finally {
    loading.value = false
  }
}

function search() {
  // cuma trigger reactive computed
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

<style scoped>
.search { padding: 0.5rem; width: 100%; max-width: 420px; margin: 0.5rem 0 1rem; }
.book-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 0.8rem; }
.book-item { padding: 0.8rem; border: 1px solid #e6e6e6; border-radius: 6px; background: #fff; }
.title { font-weight: 600; }
.meta { color: #666; margin-top: 0.25rem; }
.desc { margin-top: 0.5rem; color: #444; }
</style>