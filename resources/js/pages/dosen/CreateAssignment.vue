<template>
  <div>
    <h1>Buat Tugas (Dosen)</h1>
    <form @submit.prevent="create">
      <input v-model="title" placeholder="Judul" />
      <textarea v-model="desc" placeholder="Deskripsi"></textarea>
      <button>Buat</button>
    </form>
    <div v-if="msg">{{ msg }}</div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const title = ref('')
const desc = ref('')
const msg = ref('')

function create() {
  msg.value = `Tugas "${title.value}" dibuat (mock)`
  title.value = ''
  desc.value = ''
}
</script>