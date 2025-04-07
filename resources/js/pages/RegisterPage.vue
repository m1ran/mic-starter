<script setup>
    import { ref } from 'vue'
    import { useAuthStore } from '@/stores/useAuthStore'

    const authStore = useAuthStore()

    const form = ref({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    })

    const showPassword = ref(false)
    const valid = ref(false)

    const rules = {
        required: v => !!v || 'Required field',
        email: v => /.+@.+\..+/.test(v) || 'Incorrect email',
        min: l => v => (v && v.length >= l) || `Minimum ${l} symbols`,
        matchPassword: v => v === form.value.password || 'Passwords do not match',
    }

    const submit = async () => {
        await authStore.register(form.value)
    }
</script>

<template>
    <v-container class="d-flex justify-center align-center" style="min-height: 100vh">
        <v-card width="400" class="pa-6 rounded-xl elevation-10">
        <v-card-title class="text-h5 mb-4">Registration</v-card-title>

        <v-form @submit.prevent="submit" ref="form" v-model="valid">
            <v-text-field
                label="Name"
                v-model="form.name"
                :rules="[rules.required]"
                prepend-icon="mdi-account"
                autocomplete="name"
            />

            <v-text-field
                label="Email"
                v-model="form.email"
                :rules="[rules.required, rules.email]"
                prepend-icon="mdi-email"
                autocomplete="email"
            />

            <v-text-field
                label="Password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append="showPassword = !showPassword"
                :rules="[rules.required, rules.min(6)]"
                prepend-icon="mdi-lock"
                autocomplete="new-password"
            />

            <v-text-field
                label="Password confirmation"
                v-model="form.password_confirmation"
                :type="showPassword ? 'text' : 'password'"
                :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append="showPassword = !showPassword"
                :rules="[rules.required, rules.matchPassword]"
                prepend-icon="mdi-lock-check"
                autocomplete="new-password"
            />

            <v-btn
                type="submit"
                color="primary"
                class="mt-4"
                :loading="authStore.loading"
                block
            >
                Sign Up
            </v-btn>

            <v-btn
                variant="text"
                class="mt-2"
                block
                @click="$router.push('/login')"
            >
                Sign In
            </v-btn>
        </v-form>
        </v-card>
    </v-container>
  </template>
