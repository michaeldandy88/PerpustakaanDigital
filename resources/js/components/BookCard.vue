<script setup>
defineProps({ book: Object })
</script>

<template>
  <div class="bg-white rounded-xl p-4 shadow hover:shadow-md transition">
    <h3 class="text-lg font-semibold text-gray-800">
      {{ book.title }}
    </h3>

    <p class="text-sm text-gray-600 mt-1">
      {{ book.author }}
    </p>

    <router-link
      :to="`/books/${book.id}/read`"
      class="inline-block mt-3 px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition"
    >
      Baca
    </router-link>
  </div>
</template>