<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'
import DashboardLayout from '../../layouts/DashboardLayout.vue'

const categories = ref([])
const name = ref('')
const editing = ref(null)

const load = async () => {
  categories.value = (await api.get('/categories')).data
}

onMounted(load)

const save = async () => {
  if (!name.value.trim()) return

  if (editing.value) {
    await api.put(`/categories/${editing.value}`, { name: name.value })
  } else {
    await api.post('/categories', { name: name.value })
  }

  name.value = ''
  editing.value = null
  load()
}

const edit = (category) => {
  name.value = category.name
  editing.value = category.id
}

const remove = async (id) => {
  if (confirm('Hapus kategori ini?')) {
    await api.delete(`/categories/${id}`)
    load()
  }
}
</script>

<template>
  <DashboardLayout>
    <h1 class="text-xl font-semibold mb-6">Kelola Kategori</h1>

    <!-- Form -->
    <div class="bg-white p-5 rounded-xl shadow mb-6 max-w-xl">
      <div class="flex gap-3">
        <input
          v-model="name"
          placeholder="Nama kategori"
          class="flex-1 px-3 py-2 border border-gray-300 rounded-md
                 focus:outline-none focus:ring-2 focus:ring-blue-500"
        />

        <button
          @click="save"
          class="px-4 py-2 rounded-md text-white text-sm font-medium
                 bg-blue-600 hover:bg-blue-700 transition"
        >
          {{ editing ? 'Update' : 'Tambah' }}
        </button>
      </div>
    </div>

    <!-- List -->
    <div class="bg-white rounded-xl shadow max-w-xl">
      <ul>
        <li
          v-for="c in categories"
          :key="c.id"
          class="flex items-center justify-between px-4 py-3 border-t first:border-t-0"
        >
          <span class="text-gray-800">{{ c.name }}</span>

          <div class="flex gap-2">
            <button
              @click="edit(c)"
              class="px-3 py-1 rounded-md text-sm
                     bg-gray-100 hover:bg-gray-200"
            >
              Edit
            </button>

            <button
              @click="remove(c.id)"
              class="px-3 py-1 rounded-md text-sm
                     bg-red-600 text-white hover:bg-red-700"
            >
              Hapus
            </button>
          </div>
        </li>
      </ul>
    </div>
  </DashboardLayout>
</template>
