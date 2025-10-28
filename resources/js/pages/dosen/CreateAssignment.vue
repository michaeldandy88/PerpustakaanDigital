<template>
  <section class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Buat Tugas</h1>

    <form @submit.prevent="submit" class="space-y-3 bg-white shadow p-4 rounded-lg">
      <div>
        <label class="block text-sm text-gray-600">Judul</label>
        <input
          v-model="title"
          class="w-full border px-3 py-2 rounded-lg"
          placeholder="Judul tugas"
          required
        />
      </div>

      <div>
        <label class="block text-sm text-gray-600">Deskripsi</label>
        <textarea
          v-model="desc"
          class="w-full border px-3 py-2 rounded-lg"
          placeholder="Deskripsi tugas"
        ></textarea>
      </div>

      <button
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
      >
        Simpan
      </button>
    </form>

    <p
      v-if="msg"
      class="mt-4 text-green-600 font-semibold"
    >
      ✅ {{ msg }}
    </p>

    <p
      v-if="error"
      class="mt-4 text-red-600 font-semibold"
    >
      ❌ {{ error }}
    </p>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { createAssignment } from '@/services/api'

const title = ref('')
const desc = ref('')
const msg = ref('')
const error = ref('')

async function submit() {
  msg.value = ''
  error.value = ''

  try {
    const data = await createAssignment(title.value, desc.value)
    msg.value = `Tugas "${data.title}" berhasil dibuat`
    title.value = ''
    desc.value = ''
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Gagal membuat tugas'
  }
}
</script>
