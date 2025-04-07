<script setup>
import { useExpensesStore } from '@/stores/useExpensesStore'

const expensesStore = useExpensesStore()
</script>
<template>
    <div>
        <v-card v-if="expensesStore.selectedExpense">
          <v-card-text>Детали расхода</v-card-text>
          <v-card-text>
            <v-list density="compact">
              <v-list-item
                v-for="item in expensesStore.selectedExpense.items"
                :key="item.id"
              >
                <v-list-item-title>
                  {{ item.title }} — {{ item.quantity }} × {{ item.price }} = {{ item.amount }}
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
        <v-card v-else>
          <v-card-text>Выберите расход из таблицы</v-card-text>
        </v-card>
    </div>
</template>
