import { ref } from 'vue'
import { apiFetch } from '@/services/api'

const notifications = ref([])
let intervalId = null
let hasReloaded = false 

export function useNotifications(toast) {

    async function fetchUnread() {
        if (hasReloaded) return

        const data = await apiFetch('/notifications/unread')

        const newOnes = data.filter(
            n => !notifications.value.find(o => o.id === n.id)
        )

        if (newOnes.length) {
            for (const n of newOnes) {
                toast.success(n.data.message)
                await markAsRead(n.id) 
            }

            hasReloaded = true
        }

        notifications.value = data
    }

    async function markAsRead(id) {
        await apiFetch(`/notifications/${id}/read`, { method: 'POST' })
    }

    function startPolling() {
        if (intervalId) return
        fetchUnread()
        intervalId = setInterval(fetchUnread, 10000) 
    }

    function stopPolling() {
        clearInterval(intervalId)
        intervalId = null
    }

    return { startPolling, stopPolling }
}
