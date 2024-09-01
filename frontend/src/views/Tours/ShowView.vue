<template>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Tour</h1>

        <div v-if="tourStore.tourDetails == null" class="text-center my-8">
            Loading...
        </div>

        <div v-else>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-4">{{ tourStore.tourDetails.name }}</h2>
                    <p class="text-gray-600 mb-2">Available Slots: {{ tourStore.tourDetails.slots }}</p>
                    <p class="text-gray-600 mb-2">Destination: {{ tourStore.tourDetails.destination.name }}</p>
                    <p class="text-gray-600 font-semibold mb-4">Pricing: KSH. {{ tourStore.tourDetails.price }}</p>
                    <hr class="mb-4">
                    <p>{{ tourStore.tourDetails.description }}</p>
                </div>

                <div class="mt-2 p-6">
                    <h3 class="text-xl font-semibold mb-4">Booking Details</h3>
                    <hr class="mb-4">

                    <div v-if="bookingsStore.bookingDetails == null" class="space-y-4">
                        <div class="flex justify-center items-center">
                            <button @click="bookingsStore.bookTour({ tour_id: $route.params.id })" type="button"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                :disabled="bookingsStore.loading">
                                <IconSpinner class="animate-spin" v-show="bookingsStore.loading" />
                                Book Tour
                            </button>
                        </div>

                        <ValidationError :errors="bookingsStore.errors" field="tour_id" />
                        <ValidationError :errors="bookingsStore.errors" field="general" />
                    </div>

                    <div v-else class="space-y-4">
                        <p class="text-gray-600">Booked on: {{ Date(bookingsStore.bookingDetails.created_at) }}</p>
                        <p class="text-gray-600">Status: {{ bookingsStore.bookingDetails.status.toUpperCase() }}</p>
                        <p class="text-gray-600">Name: {{ bookingsStore.bookingDetails.user.name }}</p>
                        <p class="text-gray-600">Total Price: KSH. {{ bookingsStore.bookingDetails.total_price }}</p>

                        <div>
                            <p class="text-gray-600">No. of Tickets: {{ bookingsStore.bookingDetails.tickets.length }}
                            </p>
                            <ul v-for="ticket in bookingsStore.bookingDetails.tickets" :key="ticket.id"
                                class="list-disc list-inside pl-5">
                                <li class="text-gray-600">#{{ ticket.ticket_number }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4">Generate Booking Ticket</h3>
                    <form @submit.prevent="generateBookingTicket" novalidate>
                        <div class="space-y-4">
                            <div class="flex flex-col">
                                <label for="slots" class="block text-sm font-medium text-gray-700 mb-1">
                                    Number of Slots
                                </label>
                                <input min="1" v-model="noOfSlots" id="slots" name="slots" type="number"
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required :disabled="ticketsStore.loading" />
                                <ValidationError :errors="ticketsStore.errors" field="slots" />
                                <ValidationError :errors="ticketsStore.errors" field="general" />
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary" :disabled="ticketsStore.loading">
                                    <IconSpinner class="animate-spin" v-show="ticketsStore.loading" />
                                    Get Ticket
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onBeforeUnmount } from 'vue';
import { useTours } from '@/stores/tours';
import { useRoute } from 'vue-router';
import { useBookings } from '@/stores/bookings';
import { useTickets } from '@/stores/tickets';

const route = useRoute();
const tourStore = useTours();
const bookingsStore = useBookings();
const ticketsStore = useTickets();

let noOfSlots = ref(1);

onBeforeUnmount(tourStore.resetErrors);
onBeforeUnmount(bookingsStore.resetErrors);
onBeforeUnmount(ticketsStore.resetErrors);

tourStore.getTour(route.params.id);
bookingsStore.getUserBooking(route.params.id);

function generateBookingTicket() {
    ticketsStore.generateTicket(bookingsStore.bookingDetails.id, noOfSlots.value);
    bookingsStore.getUserBooking(route.params.id);
    noOfSlots.value = 1;
}
</script>
