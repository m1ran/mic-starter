<script setup>
import { ref, shallowRef, defineExpose } from 'vue'
import ExpenseForm from './ExpenseForm.vue'
import { useExpensesStore } from '../stores/useExpensesStore'

const form = ref(null)
const dialog = shallowRef(false)
const expensesStore = useExpensesStore()

const openDialog = () => {
    form.value = null
    dialog.value = true
    expensesStore.selectedExpense = null
}

const editExpense = () => {
    dialog.value = true
    form.value = {
        ...expensesStore.selectedExpense,
        items: expensesStore.selectedExpense.items.map(item => ({
            ...item,
        })),
    }
}

const submit = async () => {
    try {
        if (form.value.id) {
            await expensesStore.updateExpense(form.value.id, form.value)
        } else {
            await expensesStore.createExpense(form.value)
        }

        dialog.value = false
    } catch (error) {
        console.error('Error:', error)
    }
}

defineExpose({
    editExpense,
})
</script>

<template>
    <v-dialog v-model="dialog" max-width="700">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn color="primary" v-bind="activatorProps" @click="openDialog">
                <v-icon left>mdi-plus</v-icon>
                Добавить расход
            </v-btn>
        </template>

        <v-card>
            <v-card-title>Добавить расход</v-card-title>
            <v-card-text>
                <ExpenseForm v-model="form" />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn text @click="dialog = false">Отмена</v-btn>
                <v-btn color="primary" :loading="expensesStore.loading" @click="submit">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
