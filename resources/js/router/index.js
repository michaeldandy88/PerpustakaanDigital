import { createRouter, createWebHistory } from 'vue-router'
import api from '../services/api'

import Welcome from '../pages/Welcome.vue'
import Login from '../pages/auth/Login.vue'
import Register from '../pages/auth/Register.vue'
import Dashboard from '../pages/mahasiswa/Dashboard.vue'
import AdminDashboard from '../pages/admin/Dashboard.vue'

const routes = [
  {
    path: '/',
    name: 'welcome',
    component: Welcome,
    meta: { public: true } // ⬅️ PENTING
  },
  {
    path: '/login',
    component: Login,
    meta: { guest: true }
  },
  {
    path: '/register',
    component: Register,
    meta: { guest: true }
  },

  // MAHASISWA
  {
    path: '/dashboard',
    component: Dashboard,
    meta: { role: 'mahasiswa' }
  },
  {
    path: '/books',
    component: () => import('../pages/mahasiswa/BookList.vue'),
    meta: { role: 'mahasiswa' }
  },
  {
    path: '/books/:id/read',
    component: () => import('../pages/mahasiswa/BookRead.vue'),
    meta: { role: 'mahasiswa' }
  },
  {
    path: '/my-borrowings',
    component: () => import('../pages/mahasiswa/MyBorrowings.vue'),
    meta: { role: 'mahasiswa' }
  },

  // ADMIN
  {
    path: '/admin/dashboard',
    component: AdminDashboard,
    meta: { role: 'admin' }
  },
  {
    path: '/admin/books',
    component: () => import('../pages/admin/BookIndex.vue'),
    meta: { role: 'admin' }
  },
  {
    path: '/admin/books/create',
    component: () => import('../pages/admin/BookCreate.vue'),
    meta: { role: 'admin' }
  },
  {
    path: '/admin/books/:id/edit',
    component: () => import('../pages/admin/BookEdit.vue'),
    meta: { role: 'admin' }
  },
  {
    path: '/admin/categories',
    component: () => import('../pages/admin/CategoryIndex.vue'),
    meta: { role: 'admin' }
  },
  {
    path: '/admin/borrowings',
    component: () => import('../pages/admin/BorrowingIndex.vue'),
    meta: { role: 'admin' }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

/* ===============================
   GLOBAL ROUTE GUARD (FINAL)
================================ */
router.beforeEach(async (to, from, next) => {
  // 1. PUBLIC route (welcome)
  if (to.meta.public) {
    return next()
  }

  // 2. GUEST route (login, register)
  if (to.meta.guest) {
    try {
      const user = (await api.get('/user')).data
      // sudah login → redirect sesuai role
      return next(user.role === 'admin'
        ? '/admin/dashboard'
        : '/dashboard'
      )
    } catch {
      return next()
    }
  }

  // 3. PROTECTED route
  try {
    const user = (await api.get('/user')).data

    // cek role
    if (to.meta.role && user.role !== to.meta.role) {
      return next(user.role === 'admin'
        ? '/admin/dashboard'
        : '/dashboard'
      )
    }

    return next()
  } catch {
    return next('/login')
  }
})

export default router