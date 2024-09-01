import { computed } from 'vue'
import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'
import { useRouter } from 'vue-router'

export const useAuth = defineStore('auth', () => {
  const router = useRouter()
  const isAuthenticated = useStorage('isAuthenticated', false)
  const check = computed(() => isAuthenticated.value)

  function setAuthStatus(authStatus) {
    isAuthenticated.value = authStatus
  }

  function login(origin = 'login') {
    setAuthStatus(true)

    // TODO: Check role of auth user and redirect them to appropriate page.

    if (origin === 'login') return router.push({ name: 'tours.index' })
    if (origin === 'register') return router.push({ name: 'tours.index' })
  }

  function destroyTokenAndRedirectTo(routeName = 'login') {
    setAuthStatus(false)
    router.push({ name: routeName })
  }

  async function logout() {
    return window.axios.post('auth/logout').finally(() => {
      destroyTokenAndRedirectTo()
    })
  }

  return { login, logout, check, destroyTokenAndRedirectTo, isAuthenticated }
})
