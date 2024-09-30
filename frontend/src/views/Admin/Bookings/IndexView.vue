<template>
    <div class="mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">All Bookings</h1>

        <div class="flex flex-col md:flex-row justify-between md:space-x-4 items-center mb-4">
            <!-- Search input -->
            <input type="text" v-model="searchQuery" placeholder="Search username or tour..."
                class="form-input w-full md:w-1/3 border border-gray-300 rounded-lg p-2 shadow-sm focus:outline-none focus:border-blue-500 mb-2 md:mb-0" />

            <!-- Booking status dropdown -->
            <select name="booking-status" id="bookingStatus"
                class="form-input w-full md:w-1/3 border border-gray-300 rounded-lg p-2 shadow-sm focus:outline-none focus:border-blue-500"
                v-model="selectedBookingStatus">
                <option value="" selected>-- Filter by Status --</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>


        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-lg rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-200 text-left text-sm leading-normal text-gray-600 uppercase">
                        <th class="py-3 px-6 cursor-pointer">Username</th>
                        <th class="py-3 px-6 cursor-pointer">Tour</th>
                        <th class="py-3 px-6 cursor-pointer">Status</th>
                        <th class="py-3 px-6 cursor-pointer">Number of Tickets</th>
                        <th class="py-3 px-6 cursor-pointer">Total Price</th>
                        <th class="py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    <tr v-for="booking in bookingsStore.bookings.data" :key="booking.id"
                        class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ booking.user.name }}</td>
                        <td class="py-3 px-6">{{ booking.tour.name }}</td>
                        <td class="py-3 px-6">
                            <span :class="{
                                'bg-green-100 text-green-800': booking.status === 'confirmed',
                                'bg-yellow-100 text-yellow-800': booking.status === 'pending',
                                'bg-red-100 text-red-800': booking.status === 'cancelled',
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
            <div v-if="bookingsStore.bookings.data == null" class="bg-yellow-100 text-yellow-800 p-4 rounded-lg">
                <p>Loading...</p>
            </div>
            <div v-if="bookingsStore.bookings.data <= 0" class="bg-yellow-100 text-yellow-800 p-4 rounded-lg">
                <p>Data Not Available.</p>
            </div>
        </div>

        <div class="mt-4">
            <TailwindPagination :data="bookingsStore.bookings"
                @pagination-change-page="page => bookingsStore.fetchBookings(page, searchQuery, selectedBookingStatus)" />
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import { useBookings } from '@/stores/bookings';

const bookingsStore = useBookings();
const searchQuery = ref('');
const selectedBookingStatus = ref('');
let debounceTimeout = null;

onMounted(() => {
    bookingsStore.fetchBookings();
});

watch(searchQuery, (newQuery) => {
    if (debounceTimeout) clearTimeout(debounceTimeout);

    debounceTimeout = setTimeout(() => {
        bookingsStore.fetchBookings(1, newQuery, selectedBookingStatus.value);
    }, 500);
});

watch(selectedBookingStatus, (newStatus) => {
    bookingsStore.fetchBookings(1, searchQuery.value, newStatus);
});
</script>
