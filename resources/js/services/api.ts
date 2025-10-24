import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE || 'http://127.0.0.1:8000',
  withCredentials: true,
  headers: { Accept: 'application/json' }
})

export async function login(email: string, password: string) {
  const res = await api.post('/login', { email, password })
  return res.data
}

export async function fetchUser() {
  const res = await api.get('/api/user')
  return res.data
}

export async function fetchBooks() {
  const res = await api.get('/api/books')
  return res.data
}

export async function fetchAssignments() {
  const res = await api.get('/api/assignments')
  return res.data
}

export async function submitAssignment(assignmentId: string|number, formData: FormData) {
  const res = await api.post(`/api/assignments/${assignmentId}/submit`, formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  })
  return res.data
}

export default api
