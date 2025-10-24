<template>
  <section>
    <h1>Daftar Tugas</h1>

    <div v-if="loading">Memuat tugas...</div>

    <ul v-else class="assign-list">
      <li v-for="a in assignments" :key="a.id" class="assign-item">
        <router-link :to="{ name: 'submit', params: { id: a.id } }" class="title">{{ a.title }}</router-link>
        <div class="meta">
          <small>Deadline: {{ formatDate(a.due_at) || 'Belum diatur' }}</small>
        </div>
        <p v-if="a.description" class="desc">{{ a.description }}</p>
      </li>
    </ul>

    <div v-if="!assignments.length && !loading">Belum ada tugas.</div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { fetchAssignments } from '@/services/api'

const assignments = ref<Array<any>>([])
const loading = ref(true)

async function load() {
  loading.value = true
  try {
    const res = await fetchAssignments()
    assignments.value = res.data ?? res
  } finally {
    loading.value = false
  }
}

function formatDate(dt: string | null | undefined) {
  if (!dt) return null
  try {
    const d = new Date(dt)
    return d.toLocaleString()
  } catch {
    return dt
  }
}

onMounted(load)
</script>

<style scoped>
.assign-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 0.8rem; }
.assign-item { padding: 0.8rem; border: 1px solid #eee; border-radius: 6px; background: white; }
.title { font-weight: 600; text-decoration: none; color: #111; }
.meta { color: #666; margin-top: 0.25rem; }
.desc { margin-top: 0.6rem; color: #444; }
</style>