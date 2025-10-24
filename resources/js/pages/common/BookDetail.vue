<template>
  <div>
    <h2>Detail Buku</h2>
    <p>ID: {{ id }}</p>
    <p>Judul (mock): Buku contoh</p>
  </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
const route = useRoute()
const id = route.params.id
</script>