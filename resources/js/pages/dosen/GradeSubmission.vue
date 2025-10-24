<template>
  <div>
    <h1>Nilai Submission</h1>
    <p>Form nilai (mock)</p>
    <input v-model="score" type="number" />
    <button @click="save">Simpan</button>
  </div>
</template>
<script setup lang="ts">
import { ref } from 'vue'
const score = ref<number | null>(null)
function save() { alert('Disimpan (mock): ' + score.value) }
</script>