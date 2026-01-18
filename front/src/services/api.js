// front/src/services/api.js
const API_URL = 'http://localhost:8000/api'

export async function apiFetch(url, options = {}) {
    const token = localStorage.getItem('token')

    const res = await fetch(API_URL + url, {
        ...options,
        headers: {
            'Content-Type': 'application/json',
            ...(token ? { Authorization: `Bearer ${token}` } : {}),
            ...(options.headers || {})
        }
    })

    if (res.status === 401) {
        localStorage.removeItem('token')
        window.location.href = '/'
        return
    }

    return res.json()
}
