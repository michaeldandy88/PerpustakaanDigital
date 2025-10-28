<template>
  <section class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold text-gray-800">Daftar Tugas</h1>

      <!-- Loading -->
      <div v-if="loading" class="mt-6 text-gray-600">Memuat tugas...</div>

      <!-- List -->
      <ul v-else class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <li
          v-for="a in assignments"
          :key="a.id"
          class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition"
        >
          <router-link
            :to="{ name: 'submit', params: { id: a.id } }"
            class="block text-base font-semibold text-gray-900 hover:text-blue-600"
          >
            {{ a.title }}
          </router-link>

          <div class="mt-1 text-sm text-gray-500">
            <span class="font-medium">Deadline:</span>
            <span>{{ formatDate(a.due_at) || 'Belum diatur' }}</span>
          </div>

          <p v-if="a.description" class="mt-3 text-sm leading-relaxed text-gray-700">
            {{ a.description }}
          </p>
        </li>
      </ul>

      <!-- Empty state -->
      <div
        v-if="!assignments.length && !loading"
        class="mt-6 rounded-lg border border-dashed border-gray-300 bg-white p-6 text-center text-gray-600"
      >
        Belum ada tugas.
      </div>
    </div>
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
  const d = new Date(dt)
  return isNaN(d.getTime()) ? String(dt) : d.toLocaleString()
}

onMounted(load)
</script>
