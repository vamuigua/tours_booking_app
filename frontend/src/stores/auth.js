import { computed, ref } from 'vue'
import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'
import { useRouter } from 'vue-router'

export const useAuth = defineStore('auth', () => {
  const router = useRouter()
  const isAuthenticated = useStorage('isAuthenticated', false)
  const check = computed(() => isAuthenticated.value)
  const authUser = ref(null);
  const isAdmin = computed(() => authUser.value?.role === 'admin');

  function setAuthStatus(authStatus) {
    isAuthenticated.value = authStatus
  }

  function login() {
    setAuthStatus(true)

    getUserData();    

    if (isAdmin.value) return router.push({ name: 'admin.bookings.index' })
    if (!isAdmin.value) return router.push({ name: 'tours.index' })
  }

  async function getUserData() {
    try {
      const response = await window.axios.get('auth/user');
      authUser.value = response.data.data;
    } catch (error) {
      console.error(error);
    }
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

  return { login, logout, check, destroyTokenAndRedirectTo, isAuthenticated, authUser, getUserData, isAdmin }
},
  {
    persist: true,
  }
)
