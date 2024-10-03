<template>
    <div>
        <h1 class="text-3xl font-bold mb-4 text-center">Available Tours</h1>
        <div v-if="tourStore.tours.length <= 0" class="text-center my-8">
            Loading...
        </div>
        <div v-else>
            <div v-for="tour in tourStore.tours" :key="tour.id" class="my-4 p-4 border-2 border-gray-200 rounded">
                <h2 class="text-xl font-semibold">📍 {{ tour.name }}</h2>
                <p class="text-gray-600">Destination: {{ tour.destination.name }}</p>
                <p class="text-gray-600 font-semibold">Pricing: KSH. {{ tour.price }}</p>
                <p class="text-black font-semibold">Available Slots: {{ tour.slots }}</p>
                <hr class="my-2">
                <p>{{ tour.description }}</p>
                <RouterLink :to="{ name: 'tours.show', params: { id: tour.id } }"
                    class="btn btn-secondary uppercase my-4">
                    View Details
                </RouterLink>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useTours } from '@/stores/tours';

const tourStore = useTours();

onMounted(() => {
    tourStore.getTours();
});
</script>
