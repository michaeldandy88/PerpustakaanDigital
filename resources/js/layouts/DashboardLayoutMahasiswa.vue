<script setup>
import Sidebar from '../components/Sidebarmahasiswa.vue'
import Navbar from '../components/NavBar.vue'
</script>

<template>
  <div class="min-h-screen flex bg-gray-100">

    <Sidebar />

    <div class="flex-1 flex flex-col">

      <Navbar />

      <main class="flex-1 p-6 overflow-y-auto">
        <slot />
      </main>

    </div>
  </div>
</template>