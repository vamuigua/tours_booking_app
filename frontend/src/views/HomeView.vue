<template>
    <div class="flex flex-col items-center justify-center px-4 text-center">
        <h1 class="text-4xl font-extrabold text-blue-600 mb-4">🧳 Explore Unforgettable Tours</h1>
        <p class="text-lg text-gray-700 mb-8">
            Your home for unforgettable experiences around the world. Discover the perfect tour for your next adventure!
        </p>

        <img src="https://images.pexels.com/photos/6056012/pexels-photo-6056012.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
            alt="Lion in tall grass" class="mb-6 rounded-lg shadow-lg" style="width: 40%;" />

        <router-link
            class="btn-primary bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-500 transition-all duration-200"
            :to="{ name: 'tours.index' }">
            Book Your Next Tour
        </router-link>
    </div>
</template>

<style scoped>
.btn-primary {
    font-size: 1.2rem;
}
</style>
