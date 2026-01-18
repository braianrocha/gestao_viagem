import { inject } from 'vue'
// Adiciona o composable useToast para injetar o serviço de toast
export function useToast() {
    const toast = inject('toast')

    if (!toast) {
        throw new Error('Toast não foi registrado')
    }

    return toast
}
