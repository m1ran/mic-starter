import { defineStore } from 'pinia'
import axios from 'axios'
import { useFlashMessageStore } from './useFlashMessageStore'

export const useExpensesStore = defineStore('expenses', {
  state: () => ({
    expenses: [],
    loading: false,
    selectedExpense: null,
    pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
    },
  }),

  actions: {
    async fetchExpenses() {
        const flashMessageStore = useFlashMessageStore()

        this.loading = true
        try {
            const response = await axios.get('/api/expenses', {
                params: {
                    page: this.pagination.current_page,
                    per_page: this.pagination.per_page,
                }
            })
            this.expenses = response.data.data
            this.pagination = response.data.meta
        } catch (error) {
            flashMessageStore.show(error?.response?.data?.message, 'error')
            console.error('Error:', error)
        } finally {
            this.loading = false
        }
    },
    async fetchExpense(id) {
        // find the expense in the expenses array
        const index = this.expenses.findIndex(expense => expense.id === id)
        if (index !== -1 && this.expenses[index].items !== undefined) {
            // if the expense is already loaded, return
            return this.selectedExpense = this.expenses[index]
        }
        const flashMessageStore = useFlashMessageStore()

        this.loading = true
        try {
            const response = await axios.get(`/api/expenses/${id}`)
            this.selectedExpense = response.data.data
            if (index !== -1 && this.expenses[index].items === undefined) {
                this.expenses[index].items = response.data.data.items
            }
        } catch (error) {
            flashMessageStore.show(error?.response?.data?.message, 'error')
            console.error('Error:', error)
        } finally {
            this.loading = false
        }
    },
    async createExpense(form) {
        const flashMessageStore = useFlashMessageStore()

        this.loading = true
        try {
            const formData = {
                date: form.date,
                comment: form.comment,
                items: form.items,
            }

            await axios.post('/api/expenses', formData)
            // update expenses list
            await this.fetchExpenses()
            flashMessageStore.show('Expense created successfully', 'success')
        } catch (error) {
            flashMessageStore.show(error?.response?.data?.message, 'error')
            console.error('Error:', error)
        } finally {
            this.loading = false
        }
    },
    async updateExpense(id, form) {
        const flashMessageStore = useFlashMessageStore()

        this.loading = true
        try {
            const formData = {
                date: form.date,
                comment: form.comment,
                items: form.items,
            }

            const response = await axios.put(`/api/expenses/${id}`, formData)
            const index = this.expenses.findIndex(expense => expense.id === id)
            if (index !== -1) {
                this.expenses[index] = response.data.expense
            }
            flashMessageStore.show('Expense updated successfully', 'success')
        } catch (error) {
            flashMessageStore.show(error?.response?.data?.message, 'error')
            console.error('Error:', error)
        } finally {
            this.loading = false
        }
    },
  },
})
