import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue'),
    },
    {
      path: "/register",
      name: "register",
      beforeEnter: guest,
      component: () => import("@/views/Auth/RegisterView.vue"),
    },
    {
      path: '/login',
      name: 'login',
      beforeEnter: guest,
      component: () => import('@/views/Auth/LoginView.vue')
    },
    {
      path: '/tours',
      name: 'tours.index',
      beforeEnter: auth,
      component: () => import('@/views/Tours/IndexView.vue')
    },
    {
      path: '/tours/:id',
      name: 'tours.show',
      beforeEnter: auth,
      component: () => import('@/views/Tours/ShowView.vue')
    },
    {
      path: '/tours/create',
      name: 'admin.tours.create',
      beforeEnter: authAdmin,
      component: () => import('@/views/Admin/Tours/CreateView.vue')
    },
    {
      path: '/bookings',
      name: 'admin.bookings.index',
      beforeEnter: authAdmin,
      component: () => import('@/views/Admin/Bookings/IndexView.vue')
    },
    {
      path: '/bookings/:id/tickets',
      name: 'admin.bookings.tickets',
      beforeEnter: authAdmin,
      component: () => import('@/views/Admin/Bookings/GeneratedTickets.vue')
    },
  ],
})

function auth(to, from, next) {
  const authStore = useAuthStore()
  if (!authStore.isAuthenticated) {
    return next({ name: 'login' })
  }

  next()
}

function authAdmin(to, from, next) {
  const authStore = useAuthStore();
  if (!authStore.isAuthenticated) {
    return next({ name: 'login' });
  }

  if (!authStore.isAdmin) {
    return next({ name: 'tours.index' });
  }

  next();
}

function guest(to, from, next) {
  const authStore = useAuthStore()
  if (authStore.isAuthenticated) {
    return next({ name: 'tours.index' })
  }

  next()
}

export default router