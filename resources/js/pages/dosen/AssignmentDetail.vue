<template>
  <div>
    <h1>Detail Tugas (Dosen)</h1>
    <p>ID: {{ id }}</p>
    <router-link :to="{ name:'dosen.grade', params: { id } }">Nilai submission</router-link>
  </div>
</template>
<script setup lang="ts">
import { useRoute } from 'vue-router'
const id = useRoute().params.id
</script>