import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/useAuthStore'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import ExpensesPage from '@/pages/ExpensesPage.vue'
import NotFoundPage from '@/pages/NotFoundPage.vue'
import LoginPage from '../pages/LoginPage.vue'
import RegisterPage from '../pages/RegisterPage.vue'

const routes = [
  {
    path: '/',
    component: DefaultLayout,
    children: [
      { path: '', name: 'home', component: ExpensesPage },
      { path: '/login', name: 'login', component: LoginPage, meta: { guestOnly: true } },
      { path: '/register', name: 'register', component: RegisterPage, meta: { guestOnly: true } },
      { path: '/expenses', redirect: { name: 'home' } },
      { path: '/:pathMatch(.*)*', name: '404', component: NotFoundPage },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        return next('/login')
    }

    if (to.meta.guestOnly && authStore.isAuthenticated) {
        return next('/')
    }

    return next()
})

export default router
