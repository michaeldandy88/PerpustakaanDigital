<template>
  <section class="min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-6 py-8">
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Detail Tugas</h1>
        <router-link
          :to="{ name:'dosen.grade', params:{ id } }"
          class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
        >
          Nilai Submission
        </router-link>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        Memuat detail tugas...
      </div>

      <!-- Error -->
      <div
        v-else-if="error"
        class="mt-6 rounded-xl border border-red-200 bg-red-50 p-6 text-red-700"
      >
        {{ error }}
      </div>

      <!-- Content -->
      <div v-else class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="space-y-3">
          <div>
            <div class="text-sm text-gray-500">ID</div>
            <div class="font-semibold text-gray-900">{{ assignment.id }}</div>
          </div>

          <div>
            <div class="text-sm text-gray-500">Judul</div>
            <div class="font-semibold text-gray-900">{{ assignment.title }}</div>
          </div>

          <div v-if="assignment.description">
            <div class="text-sm text-gray-500">Deskripsi</div>
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
              {{ assignment.description }}
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <div class="text-sm text-gray-500">Deadline</div>
              <div class="text-gray-800">
                {{ formatDate(assignment.due_at) || 'Belum diatur' }}
              </div>
            </div>
            <div v-if="assignment.created_at">
              <div class="text-sm text-gray-500">Dibuat</div>
              <div class="text-gray-800">{{ formatDate(assignment.created_at) }}</div>
            </div>
          </div>
        </div>

        <div class="mt-6 flex gap-3">
          <router-link
            :to="{ name:'dosen.createAssignment' }"
            class="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-100"
          >
            Buat Tugas Baru
          </router-link>
          <router-link
            :to="{ name:'dosen.grade', params:{ id } }"
            class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
          >
            Lanjut ke Penilaian
          </router-link>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { getAssignment } from '@/services/api'

const route = useRoute()
const id = useRoute().params.id as string | number

const loading = ref(true)
const error = ref<string | null>(null)
const assignment = ref<any>({})

function formatDate(dt?: string | null) {
  if (!dt) return null
  const d = new Date(dt)
  return isNaN(d.getTime()) ? String(dt) : d.toLocaleString()
}

onMounted(async () => {
  loading.value = true
  error.value = null
  try {
    const data = await getAssignment(id)
    assignment.value = data
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Gagal memuat detail tugas'
  } finally {
    loading.value = false
  }
})
</script>