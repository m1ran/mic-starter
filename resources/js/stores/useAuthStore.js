import { defineStore } from 'pinia'
import axios from 'axios'
import router from '@/router'
import { useFlashMessageStore } from './useFlashMessageStore'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        loading: false,
        token: localStorage.getItem('token') || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token && !!state.user,
    },

    actions: {
        async login(form) {
            const flashMessageStore = useFlashMessageStore()

            this.loading = true
            try {
                const formData = {
                    email: form.email,
                    password: form.password,
                }

                const response = await axios.post('/api/login', formData)
                this._setAuthData(response?.data)

                flashMessageStore.show('You signed in successfully', 'success')

                router.push({ name: 'home' })
            } catch (error) {
                flashMessageStore.show(error?.response?.data?.message, 'error')
                console.error('Error:', error)
            } finally {
                this.loading = false
            }
        },
        async register(form) {
            const flashMessageStore = useFlashMessageStore()

            this.loading = true
            try {
                const formData = {
                    name: form.name,
                    email: form.email,
                    password: form.password,
                    password_confirmation: form.password_confirmation,
                }

                const response = await axios.post('/api/register', formData)
                this._setAuthData(response?.data)

                flashMessageStore.show('You signed up successfully', 'success')

                router.push({ name: 'home' })
            } catch (error) {
                flashMessageStore.show(error?.response?.data?.message, 'error')
                console.error('Error:', error)
            } finally {
                this.loading = false
            }
        },
        async logout() {
            this.loading = true
            try {
                await axios.post('/api/logout')
                this._clearAuthData()
                router.push({ name: 'home' })
            } catch (error) {
                console.error('Error:', error)
            } finally {
                this.loading = false
            }
        },
        async fetchUser() {
            if (!this.token) return
            try {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
                const { data } = await axios.get('/api/user')
                this.user = data
            } catch (e) {
              this.logout()
            }
        },
        _setAuthData(data) {
            this.token = data.token
            this.user = data.user
            localStorage.setItem('token', data.token)
            axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
        },
        _clearAuthData() {
            this.user = null
            this.token = null
            localStorage.removeItem('token')
            delete axios.defaults.headers.common['Authorization']
        }
    }
})
