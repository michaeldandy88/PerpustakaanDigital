export type Role = 'mahasiswa'|'dosen'|'pustakawan'|null;

export interface AuthUser {
  id : number
  name : string
  role : Role
  token : string
}

const KEY = 'app_auth_user';

export function saveUser(user: AuthUser) {
  localStorage.setItem(KEY, JSON.stringify(user))
}

export function clearUser() {
  localStorage.removeItem(KEY);
}

export function getUser(): AuthUser | null {
  const s = localStorage.getItem(KEY)
  return s ? JSON.parse(s) : null
}

export function getUserRole(): Role {
  const u = getUser();
  return u ? u.role : null;
}

export function getToken(): string | null {
  const u = getUser()
  return u ? u.token : null
}

export function isAuthenticated(): boolean {
  return !!getUser();
}

export async function login(email: string, password: string) {
  const res = await fetch('/api/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  })

  if (!res.ok) {
    const data = await res.json().catch(() => ({}));
    throw new Error(data.message || 'Login gagal')
  }

  const user = await res.json()
  saveUser(user)
  return user
}

export async function logout() {
  clearUser();
}