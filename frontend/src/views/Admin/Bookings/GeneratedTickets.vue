<template>
    <div class="container mx-auto p-4">
        <div v-if="bookingsStore.bookingDetails == null">
            <p>Loading...</p>
        </div>

        <div v-else>
            <div class="mb-4">
                <h1 class="text-2xl font-bold mb-4 text-gray-800">
                    Tickets for Booking #{{ bookingsStore.bookingDetails.id }}
                </h1>

                <div v-if="bookingsStore.bookingDetails.tickets.length <= 0"
                    class="bg-yellow-100 text-yellow-800 p-4 rounded-lg">
                    <p>No tickets generated for this booking.</p>
                </div>

                <div v-else class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th
                                    class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Ticket Number
                                </th>
                                <th
                                    class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Date of Purchase
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ticket in bookingsStore.bookingDetails.tickets" :key="ticket.id"
                                class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-4 px-6 text-sm text-gray-700">#{{ ticket.ticket_number }}</td>
                                <td class="py-4 px-6 text-sm text-gray-700">{{ formatDate(ticket.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useBookings } from '@/stores/bookings';

const route = useRoute();
const bookingsStore = useBookings();

onMounted(() => {
    bookingsStore.getBooking(route.params.id);
});

function formatDate(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    return new Date(date).toLocaleDateString(undefined, options);
}
</script>
