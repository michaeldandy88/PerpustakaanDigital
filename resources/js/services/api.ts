import axios from 'axios'
import { getToken } from '@/services/auth'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE || 'http://127.0.0.1:8000',
  withCredentials: true,
  headers: { Accept: 'application/json' }
})

api.interceptors.request.use(config => {
  const token = getToken()
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

export async function getLibrarianStats() {
  const res = await api.get('/api/stats/pustakawan')
  return res.data
}

export async function listBooks(params?:any) {
  const res = await api.get('/api/books', {params})
  return res.data
}

export async function createBookApi(formData: FormData) {
  const res = await api.post('/api/books', formData,{
    headers: {'Content-Type': 'multipart/form-data'}
  })
  return res.data  
}

export async function updateBookApi(id:number|string, formData: FormData) {
  formData.append('_method', 'PUT')
  const res = await api.post(`/api/books/${id}`, formData, {
    headers : {'Content-Type': 'multipart/form-data'}
  })
  return res.data
}

export async function deleteBookApi(id:number|string) {
  const res = await api.delete(`/api/books/${id}`)
  return res.data
}

export async function listCopies(params?:any) {
  const res = await api.get('/api/copies',{params})
  return res.data
}

export async function createCopyApi(payload: {
  book_id: number
  barcode: string
  status: 'tersedia' | 'dipinjam'
  copy_type?: string
  location?: string
}) {
  const res = await api.post('/api/copies',payload)
  return res.data
}

export async function updateCopyApi(id: number | string, payload: {
  book_id?: number
  barcode?: string
  status?: 'tersedia' | 'dipinjam'
  copy_type?: string
  location?: string
}) {
  const res = await api.put(`/api/copies/${id}`,payload)
  return res.data
}

export async function deleteCopyApi(id: number | string) {
  const res = await api.delete(`/api/copies/${id}`)
  return res.data
}

export async function createAssignment(title: string, description: string) {
  const res = await api.post('/api/assignments', {
    title,
    description
  })
  return res.data
}

export async function getAssignment(id: string | number) {
  const res = await api.get(`/api/assignments/${id}`)
  return res.data
}

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
