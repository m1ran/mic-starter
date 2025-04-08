<script setup>
import dayjs from 'dayjs'
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/useAuthStore'
import { useExpensesStore } from '@/stores/useExpensesStore'
import ExpenseDialog from '@/components/ExpenseDialog.vue'

const expanded = ref([])
const expensesStore = useExpensesStore()
const authStore = useAuthStore()
const expenseDialogRef = ref(null)

const headers = [
    { title: 'Дата', key: 'date', sortable: false },
    { title: 'Комментарий', key: 'comment', sortable: false },
    { title: 'Сумма операции, $', key: 'total', sortable: false },
    { title: 'Действия', key: 'actions', sortable: false },
]

const  subHeaders = [
    { title: 'Наименование', key: 'title' },
    { title: 'Количество', key: 'quantity' },
    { title: 'Цена', key: 'price' },
    { title: 'Сумма, $', key: 'amount' },
]

const handlePageChange = (page) => {
    expensesStore.pagination.current_page = page
    expensesStore.fetchExpenses()
}

const handlePerPageChange = (perPage) => {
    expanded.value = []
    expensesStore.pagination.per_page = perPage
    //  Fetch expenses only if we are on the first page
    if (expensesStore.pagination.current_page == 1) {
        expensesStore.fetchExpenses()
    }
}

const openEditDialog = async (item) => {
    await expensesStore.fetchExpense(item.id)
    if (expenseDialogRef.value) {
        expenseDialogRef.value.editExpense()
    }
}

const showExpenseDetails = (id) => {
    expensesStore.fetchExpense(id)
}

const  handleExpandedChange = (expanded) => {
    if (expanded.length > 0) {
        const id = expanded.slice(-1)[0]
        showExpenseDetails(id)
    }
}

onMounted(() => {
    expensesStore.fetchExpenses()
})
</script>
<template>
    <div>
        <ExpenseDialog v-if="authStore.isAuthenticated" ref="expenseDialogRef" />

        <v-data-table-server
            :headers="headers"
            :items="expensesStore.expenses"
            :items-per-page="expensesStore.pagination.per_page"
            :loading="expensesStore.loading"
            loading-text="Загрузка данных..."
            no-data-text="Нет расходов"
            :page="expensesStore.pagination.current_page"
            :items-length="expensesStore.pagination.total"
            @update:page="handlePageChange"
            @update:items-per-page="handlePerPageChange"
            @update:expanded="handleExpandedChange"
            v-model:expanded="expanded"
            :mobile-breakpoint="600"
            show-expand
        >
            <template #item-date="{ item }">
                {{ dayjs(item.date).format('DD.MM.YYYY') }}
            </template>

            <template #item.actions="{ item }">
                <v-btn
                    v-if="authStore.user?.id === item.user_id"
                    @click="openEditDialog(item)"
                    icon
                    class="pa-0 ma-0"
                    elevation="0"
                >
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
            </template>

            <template #expanded-row="{ item, columns }">
                <tr class="expanded-row" v-if="item.items && item.items.length > 0">
                    <td :colspan="columns.length">
                        <v-data-table
                            :headers="subHeaders"
                            :items="item.items"
                            hide-default-footer
                            class="expanded-sub-table"
                        />
                    </td>
                </tr>
            </template>
        </v-data-table-server>
    </div>
</template>

<style scoped>
 .expanded-row, .expanded-sub-table {
    background-color: #f5f5f5;
 }
 .expanded-row td {
    padding: 0;
 }
</style>
