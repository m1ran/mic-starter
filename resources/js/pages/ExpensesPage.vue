<script setup>
import ExpensesTable from '../components/ExpensesTable.vue'
</script>
<template>
    <div>
        <v-card>
            <v-card-title>
                <span class="text-h6">Расходы</span>
            </v-card-title>

            <v-card-text>
                <!-- <v-alert v-if="error" type="error" dismissible>
                    {{ error }}
                </v-alert> -->

                <v-container fluid>
                    <v-row>
                        <v-col cols="12">
                            <ExpensesTable />
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
        </v-card>
    </div>
</template>
