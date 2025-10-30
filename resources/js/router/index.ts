import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'

// common
import Login from '@/pages/common/Login.vue'
import NotFound from '@/pages/common/NotFound.vue'
import BookDetail from '@/pages/common/BookDetail.vue'

// mahasiswa
import BookList from '@/pages/mahasiswa/BookList.vue'
import MyLoans from '@/pages/mahasiswa/MyLoans.vue'
import AssignmentList from '@/pages/mahasiswa/AssignmentList.vue'

// dosen
import CreateAssignment from '@/pages/dosen/CreateAssignment.vue'
import AssignmentDetail from '@/pages/dosen/AssignmentDetail.vue'
import GradeSubmission from '@/pages/dosen/GradeSubmission.vue'

// pustakawan
import ManageBooks from '@/pages/pustakawan/ManageBooks.vue'
import ManageCopies from '@/pages/pustakawan/ManageCopies.vue'
import Dashboard from '@/pages/pustakawan/Dashboard.vue'

const routes: Array<RouteRecordRaw> = [
  { path: '/login', name: 'login', component: Login },
  { path: '/', name: 'books', component: BookList },
  { path: '/books/:id', name: 'book.detail', component: BookDetail, props: true },

  // mahasiswa routes
  { path: '/my/loans', name: 'my.loans', component: MyLoans, meta: { requiresAuth: true, requiresRole: ['mahasiswa'] } },
  { path: '/my/assignments', name: 'my.assignments', component: AssignmentList, meta: { requiresAuth: true, requiresRole: ['mahasiswa'] } },

  // dosen routes
  { path: '/dosen/assignments/create', name: 'dosen.createAssignment', component: CreateAssignment, meta: { requiresAuth: true, requiresRole: ['dosen'] } },
  { path: '/dosen/assignments/:id', name: 'dosen.assignmentDetail', component: AssignmentDetail, props: true, meta: { requiresAuth: true, requiresRole: ['dosen'] } },
  { path: '/dosen/submissions/:id/grade', name: 'dosen.grade', component: GradeSubmission, props: true, meta: { requiresAuth: true, requiresRole: ['dosen'] } },

  // pustakawan routes
  { path: '/pustakawan/dashboard', name: 'pustakawan.dashboard', component: Dashboard, meta: { requiresAuth: true, requiresRole: ['pustakawan'] } },
  { path: '/pustakawan/books', name: 'pustakawan.manageBooks', component: ManageBooks, meta: { requiresAuth: true, requiresRole: ['pustakawan'] } },
  { path: '/pustakawan/copies', name: 'pustakawan.manageCopies', component: ManageCopies, meta: { requiresAuth: true, requiresRole: ['pustakawan'] } },

  // catch-all
  { path: '/:pathMatch(.*)*', name: 'notfound', component: NotFound }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// lazy guard here: import auth service
import { getUserRole, isAuthenticated } from '@/services/auth'

router.beforeEach((to, from, next) => {
  const requiresAuth = to.meta?.requiresAuth as boolean | undefined
  const roles = to.meta?.requiresRole as string[] | undefined

  if (requiresAuth && !isAuthenticated()) {
    return next({ name: 'login' })
  }

  if (roles && roles.length) {
    const role = getUserRole()
    if (!role || !roles.includes(role)) {
      // redirect to login if not logged, otherwise go to forbidden (we reuse login)
      return next({ name: 'login' })
    }
  }

  return next()
})

export default router