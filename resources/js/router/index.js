import { createRouter, createWebHistory } from 'vue-router'
import api from '../services/api'

import Login from '../pages/auth/Login.vue'
import Register from '../pages/auth/Register.vue'
import Dashboard from '../pages/mahasiswa/Dashboard.vue'
import AdminDashboard from '../pages/admin/Dashboard.vue'

const routes = [
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/dashboard', component: Dashboard },
  { path: '/admin', component: AdminDashboard }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(async (to, from, next) => {
  if (['/login', '/register'].includes(to.path)) {
    return next()
  }

  try {
    const user = (await api.get('/user')).data

    if (to.path.startsWith('/admin') && user.role !== 'admin') {
      return next('/dashboard')
    }

    next()
  } catch {
    next('/login')
  }
})

export default router