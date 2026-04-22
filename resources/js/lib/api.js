export async function apiRequest(path, { method = 'GET', body, headers } = {}) {
  const normalizedMethod = String(method || 'GET').toUpperCase()
  const init = {
    method: normalizedMethod,
    headers: {
      Accept: 'application/json',
      ...(headers || {}),
    },
  }

  if (body !== undefined) {
    if (body instanceof FormData) {
      if (['PUT', 'PATCH'].includes(normalizedMethod)) {
        init.method = 'POST'

        if (!body.has('_method')) {
          body.append('_method', normalizedMethod)
        }
      }

      init.body = body
    } else {
      init.headers['Content-Type'] = 'application/json'
      init.body = JSON.stringify(body)
    }
  }

  const response = await fetch(path, init)

  if (response.status === 204) {
    return null
  }

  const isJson = response.headers.get('content-type')?.includes('application/json')
  const payload = isJson ? await response.json() : await response.text()

  if (!response.ok) {
    const message =
      (payload && typeof payload === 'object' && payload.message) ||
      (typeof payload === 'string' && payload) ||
      `Request gagal (${response.status})`

    const error = new Error(message)
    error.status = response.status
    error.payload = payload
    throw error
  }

  return payload
}
