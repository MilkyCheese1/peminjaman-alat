const AUTH_SESSION_KEY = 'trustequip_auth_session'

export function getAuthSession() {
  if (typeof window === 'undefined') {
    return null
  }

  try {
    const raw = window.localStorage.getItem(AUTH_SESSION_KEY)
    if (!raw) return null

    const parsed = JSON.parse(raw)
    if (!parsed || typeof parsed !== 'object') return null
    if (!parsed.email || !parsed.role) return null

    return parsed
  } catch (error) {
    return null
  }
}

export function isAuthenticated() {
  return !!getAuthSession()
}

export function roleRedirectPath(role) {
  const normalized = String(role || '').trim().toLowerCase()

  if (normalized === 'admin') return '/dashboard/admin'
  if (normalized === 'owner') return '/dashboard/owner'
  if (normalized === 'staff') return '/dashboard/staff'
  if (normalized === 'peminjam') return '/dashboard/peminjam'

  return '/dashboard/peminjam'
}

export function setAuthSession(session) {
  if (typeof window === 'undefined') {
    return
  }

  const payload = session && typeof session === 'object'
    ? { ...session, createdAt: Date.now() }
    : { createdAt: Date.now() }

  window.localStorage.setItem(AUTH_SESSION_KEY, JSON.stringify(payload))
}

export function clearAuthSession() {
  if (typeof window === 'undefined') {
    return
  }

  window.localStorage.removeItem(AUTH_SESSION_KEY)
}
