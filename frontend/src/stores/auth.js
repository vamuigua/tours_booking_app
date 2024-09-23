import { defineStore } from 'pinia';
import { useStorage } from '@vueuse/core';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isAuthenticated: useStorage('isAuthenticated', false),
    authUser: null,
  }),

  getters: {
    check: (state) => state.isAuthenticated,
    isAdmin: (state) => state.authUser?.role === 'admin',
  },

  actions: {
    async setAuthStatus(authStatus) {
      if (authStatus) {
        await this.getUserData();
      } else {
        this.authUser = null;
      }
      this.isAuthenticated = authStatus;
    },

    async login() {
      await this.setAuthStatus(true);
      return this.isAuthenticated;
    },

    async getUserData() {
      try {
        const response = await window.axios.get('auth/user');
        this.authUser = response.data.data;
      } catch (error) {
        console.error('Error fetching user data:', error);
      }
    },

    async destroyTokenAndRedirect() {
      await this.setAuthStatus(false);
    },
  },

  persist: true,
});
