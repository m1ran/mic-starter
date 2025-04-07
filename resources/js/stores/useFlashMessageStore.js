import { defineStore } from 'pinia'

export const useFlashMessageStore = defineStore('flash-message', {
    state: () => ({
        flash: false,
        message: '',
        color: 'success',
    }),

    actions: {
        show(message, color = 'success') {
            this.message = message || 'Something went wrong'
            this.color = color
            this.flash = true

            setTimeout(() => {
              this.flash = false
            }, 5000)
        }
    }
})
