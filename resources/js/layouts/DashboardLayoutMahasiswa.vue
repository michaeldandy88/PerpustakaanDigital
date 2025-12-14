<script setup>
import Navbar from '../components/NavBar.vue'
</script>

<template>
  <div class="min-h-screen flex bg-gray-100">

    <!-- Main content -->
    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <Navbar />

      <!-- Page content -->
      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>