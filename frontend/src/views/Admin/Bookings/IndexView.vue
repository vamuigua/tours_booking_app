<template>
    <div class="mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">All Bookings</h1>

        <div class="flex justify-between items-center mb-4">
            <input type="text" v-model="bookingsStore.searchQuery" placeholder="Search..."
                class="form-input w-full md:w-1/3 border border-gray-300 rounded-lg p-2 shadow-sm focus:outline-none focus:border-blue-500" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-lg rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-left text-sm leading-normal text-gray-600 uppercase">
                        <th @click="bookingsStore.sortBy('user.name')" class="py-3 px-6 cursor-pointer">Username</th>
                        <th @click="bookingsStore.sortBy('tour.name')" class="py-3 px-6 cursor-pointer">Tour</th>
                        <th @click="bookingsStore.sortBy('status')" class="py-3 px-6 cursor-pointer">Status</th>
                        <th @click="bookingsStore.sortBy('tickets.length')" class="py-3 px-6 cursor-pointer">Number of
                            Tickets</th>
                        <th @click="bookingsStore.sortBy('total_price')" class="py-3 px-6 cursor-pointer">Total Price
                        </th>
                        <th class="py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    <tr v-for="booking in bookingsStore.filteredBookings" :key="booking.id"
                        class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ booking.user.name }}</td>
                        <td class="py-3 px-6">{{ booking.tour.name }}</td>
                        <td class="py-3 px-6">
                            <span :class="{
                                'bg-green-100 text-green-800': booking.status === 'confirmed',
                                'bg-yellow-100 text-yellow-800': booking.status === 'pending',
                                'bg-red-100 text-red-800': booking.status === 'canceled',
                            }" class="px-2 py-1 rounded-full text-xs font-bold">
                                {{ booking.status.toUpperCase() }}
                            </span>
                        </td>
                        <td class="py-3 px-6">{{ booking.tickets.length }}</td>
                        <td class="py-3 px-6">KSH.{{ booking.total_price }}</td>
                        <td class="py-3 px-6 text-blue-500 cursor-pointer hover:underline">
                            <router-link :to="{ name: 'admin.bookings.tickets', params: { id: booking.id } }">
                                View Tickets
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <vue-awesome-paginate :total-items="bookingsStore.bookings.length" v-model="bookingsStore.currentPage"
                :items-per-page="bookingsStore.perPage" :max-pages-shown="5" class="pagination" />
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useBookings } from '@/stores/bookings';

const bookingsStore = useBookings();

onMounted(() => {
    bookingsStore.fetchBookings();
});
</script>

<style scoped>
.table th,
.table td {
    padding: 8px 12px;
    border: 1px solid #ddd;
}

.table th {
    cursor: pointer;
}
</style>
