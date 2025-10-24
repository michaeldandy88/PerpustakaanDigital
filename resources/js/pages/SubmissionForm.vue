<template>
  <section>
    <h1>Submit Tugas</h1>

    <div v-if="!assignment && !loading">Tugas tidak ditemukan.</div>
    <div v-if="loading">Memuat...</div>

    <div v-else>
      <h2 v-if="assignment">{{ assignment.title }}</h2>
      <p v-if="assignment && assignment.description">{{ assignment.description }}</p>

      <form @submit.prevent="handleSubmit" class="form">
        <label>Jawaban (opsional teks)</label>
        <textarea v-model="textAnswer" rows="6" placeholder="Tulis jawaban..."></textarea>

        <label>File (boleh lebih dari 1)</label>
        <input type="file" ref="files" multiple />

        <div style="margin-top:0.8rem">
          <button type="submit" :disabled="submitting">{{ submitting ? 'Mengirim...' : 'Kumpulkan' }}</button>
        </div>

        <p v-if="message" :class="{ ok: ok }">{{ message }}</p>
      </form>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { fetchAssignments, submitAssignment } from '@/services/api'

const route = useRoute()
const assignmentId = route.params.id as string

const assignment = ref<any | null>(null)
const loading = ref(true)
const submitting = ref(false)
const textAnswer = ref('')
const files = ref<HTMLInputElement | null>(null)
const message = ref<string | null>(null)
const ok = ref(false)

async function load() {
  loading.value = true
  try {
    const res = await fetchAssignments()
    const list = res.data ?? res
    assignment.value = list.find((x: any) => String(x.id) === String(assignmentId)) ?? null
  } finally {
    loading.value = false
  }
}

async function handleSubmit() {
  if (!assignment.value) return
  message.value = null
  submitting.value = true
  ok.value = false

  try {
    const form = new FormData()
    form.append('text_answer', textAnswer.value || '')

    const input = files.value
    if (input && input.files) {
      for (let i = 0; i < input.files.length; i++) {
        form.append('files[]', input.files[i])
      }
    }

    await submitAssignment(assignmentId, form)
    message.value = 'Berhasil dikumpulkan.'
    ok.value = true
    textAnswer.value = ''
    if (files.value) files.value.value = ''
  } catch (err: any) {
    message.value = err.response?.data?.message || 'Gagal submit.'
    ok.value = false
  } finally {
    submitting.value = false
  }
}

onMounted(load)
</script>

<style scoped>
.form { display: flex; flex-direction: column; gap: 0.6rem; max-width: 720px; }
textarea { padding: 0.6rem; border-radius: 4px; border: 1px solid #ddd; }
input[type="file"] { padding: 0.2rem 0; }
button { padding: 0.5rem 0.9rem; border: none; background: #2563eb; color: white; border-radius: 6px; cursor: pointer; }
button:disabled { opacity: 0.6; cursor: not-allowed; }
p.ok { color: green; }
</style>
