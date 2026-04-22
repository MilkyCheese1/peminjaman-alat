export async function apiRequest(path, { method = 'GET', body, headers } = {}) {
  const init = {
    method,
    headers: {
      Accept: 'application/json',
      ...(headers || {}),
    },
  }

  if (body !== undefined) {
    if (body instanceof FormData) {
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
