<template>
  <header class="py-6 bg-gray-100 shadow">
    <div class="container md:px-2 px-4 mx-auto">
      <nav class="flex gap-4 justify-between">
        <!-- Left Side: Logo & Navigation Links -->
        <div class="flex gap-4 items-center">
          <h2 class="text-xl font-bold">
            <RouterLink :to="{ name: 'home' }" class="inline-flex items-center">
              <span class="inline-flex items-center justify-center w-6 h-6 text-center text-white rounded"
                aria-label="Tours Booking Logo">
                🧳
              </span>
              Tours Booking
            </RouterLink>
          </h2>

          <!-- Authenticated Links -->
          <template v-if="auth.check">
            <RouterLink class="router-link" :to="{ name: 'tours.index' }">
              Tours
            </RouterLink>
            <RouterLink v-if="auth.isAdmin" class="router-link" :to="{ name: 'admin.tours.create' }">
              Create Tour
            </RouterLink>
            <RouterLink v-if="auth.isAdmin" class="router-link" :to="{ name: 'admin.bookings.index' }">
              Bookings
            </RouterLink>
          </template>

          <!-- Guest Links -->
          <template v-else>
            <RouterLink class="router-link" :to="{ name: 'home' }"> Home </RouterLink>
          </template>
        </div>

        <!-- Right Side: Auth Links -->
        <div class="flex gap-4 items-center">
          <template v-if="auth.check">
            <p class="text-sm">Welcome back, <strong>{{ auth.authUser?.name }}</strong></p>
            <button @click="handleLogout" class="router-link">Logout</button>
          </template>
          <template v-else>
            <RouterLink class="router-link" :to="{ name: 'login' }"> Login </RouterLink>
            <RouterLink class="router-link" :to="{ name: 'register' }"> Register </RouterLink>
          </template>
        </div>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <div class="container md:px-2 px-4 pt-6 md:pt-3 mx-auto">
    <RouterView />
  </div>
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()

const handleLogout = async () => {
  try {
    await auth.logout()
  } catch (error) {
    console.error("Logout failed:", error)
  }
}
</script>

<style scoped>
.router-link {
  color: #3b82f6;
  font-weight: 500;
  transition: color 0.3s;
}

.router-link:hover {
  color: #1e40af;
}

.router-link:focus {
  outline: none;
  text-decoration: underline;
}
</style>
