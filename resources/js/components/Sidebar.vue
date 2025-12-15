<template>
  <aside class="w-60 bg-blue-800 text-white p-5 flex flex-col">
    <h2 class="text-lg font-semibold mb-5">
      Menu
    </h2>

    <nav class="flex flex-col gap-1">
      <router-link
        to="/dashboard"
        class="px-3 py-2 rounded-md hover:bg-white/15 transition"
      >
        Dashboard
      </router-link>

      <router-link
        to="/admin/books"
        class="px-3 py-2 rounded-md hover:bg-white/15 transition"
      >
        Data Buku
      </router-link>

      <router-link
        to="/admin/categories"
        class="px-3 py-2 rounded-md hover:bg-white/15 transition"
      >
        Kategori
      </router-link>

      <router-link
        to="/admin/borrowings"
        class="px-3 py-2 rounded-md hover:bg-white/15 transition"
      >
        Pengembalian
      </router-link>
    </nav>
  </aside>
</template>