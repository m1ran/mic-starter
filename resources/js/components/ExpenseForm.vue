<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    modelValue: Object,
})
const emit = defineEmits(['update:modelValue'])

const form = ref(JSON.parse(JSON.stringify(props.modelValue || {
    date: new Date().toISOString().substr(0, 10),
    comment: '',
    items: [
        { title: '', quantity: 1, price: 0.01 },
    ]
})))

watch(form, () => {
    emit('update:modelValue', form.value)
}, { deep: true })

const total = computed(() => {
    const sum = form.value.items.reduce((acc, item) => {
        return acc + item.quantity * item.price
    }, 0)
    return sum.toFixed(2)
})

const addRow = () => {
    form.value.items.push({ title: '', quantity: 1, price: 0.01 })
}

const removeRow = (index) => {
    form.value.items.splice(index, 1)
}
</script>

<template>
    <v-form>
    <v-text-field
        v-model="form.date"
        label="Дата"
        type="date"
        required
    />
    <v-textarea
        v-model="form.comment"
        label="Комментарий"
        auto-grow
    />

    <v-table>
        <thead>
            <tr>
                <th>Наименование</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Сумма</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in form.items" :key="index">
                <td>
                    <v-text-field v-model="item.title" width="200" />
                </td>
                <td>
                    <v-text-field v-model.number="item.quantity" width="100" type="number" min="1" />
                </td>
                <td>
                    <v-text-field v-model.number="item.price" type="number" width="100" min="0.01" />
                </td>
                <td>{{ (item.quantity * item.price).toFixed(2) }}</td>
                <td>
                <v-btn icon @click="removeRow(index)" v-if="form.items.length > 1">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
                </td>
            </tr>
        </tbody>
    </v-table>

    <v-btn block text @click="addRow">
        <v-icon left>mdi-plus</v-icon>
        Добавить статью расхода
    </v-btn>

    <v-divider class="my-2" />
        <v-alert type="info" class="mt-2">
            Общая сумма: {{ total }} $
        </v-alert>
    </v-form>
</template>
