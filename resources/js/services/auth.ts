export type Role = 'mahasiswa'|'dosen'|'pustakawan'|null;

const KEY = 'app_auth_user';

export function saveUser(user: { id:number, name:string, role: Role }) {
  localStorage.setItem(KEY, JSON.stringify(user));
}

export function clearUser() {
  localStorage.removeItem(KEY);
}

export function getUser(): { id:number, name:string, role: Role } | null {
  const s = localStorage.getItem(KEY);
  return s ? JSON.parse(s) : null;
}

export function getUserRole(): Role {
  const u = getUser();
  return u ? u.role : null;
}

export function isAuthenticated(): boolean {
  return !!getUser();
}

// Fake login for quick dev — replace with real API call
export async function loginMock(email:string, password:string) {
  // simple mapping by email
  let role: Role = 'mahasiswa';
  if (email.includes('dosen')) role = 'dosen';
  if (email.includes('pustakawan')) role = 'pustakawan';
  const user = { id: Date.now(), name: email.split('@')[0], role };
  saveUser(user);
  return user;
}

export async function logout() {
  clearUser();
}