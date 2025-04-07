<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/useAuthStore'
import { useFlashMessageStore } from '@/stores/useFlashMessageStore'

const authStore = useAuthStore()
const flashMessageStore = useFlashMessageStore()

const logout = async () => {
  await authStore.logout()
}

onMounted(() => {
    authStore.fetchUser()
})
</script>

<template>
    <v-app>
        <v-app-bar app color="primary" dark>
            <v-toolbar-title>
                <RouterLink to="/" class="title-link">Expenses</RouterLink>
            </v-toolbar-title>

            <v-spacer />

            <div v-if="authStore.isAuthenticated" class="auth-info">
                <span class="mr-4">
                    <v-icon class="mr-2">mdi-account</v-icon>
                    {{ authStore.user?.name }}
                </span>
                <a class="title-link mr-4" href="#" @click.prevent="logout">Log Out</a>
            </div>
            <div v-else>
                <RouterLink to="/login" class="title-link mr-4">Sign In</RouterLink>
            </div>
        </v-app-bar>

        <v-main>
            <v-container class="py-4">
            <router-view />
            </v-container>
        </v-main>

        <v-snackbar v-model="flashMessageStore.flash" :color="flashMessageStore.color" top right>
            {{ flashMessageStore.message }}
        </v-snackbar>
    </v-app>
</template>

<style scoped>
.title-link {
    color: white;
    text-decoration: none;
}
</style>
