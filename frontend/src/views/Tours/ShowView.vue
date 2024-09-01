<template>
    <div>
        <h1 class="text-3xl font-bold mb-4">Tour</h1>
        <div v-if="tourStore.tourDetails == null" class="text-center my-8">
            Loading...
        </div>
        <div v-else>
            <div class="my-4 p-4 border-2 border-gray-200">
                <h1 class="text-2xl font-bold">{{ tourStore.tourDetails.name }}</h1>
                <p class="text-black font-semibold">Available Slots: {{ tourStore.tourDetails.slots }}</p>
                <p class="text-gray-600">Destination: {{ tourStore.tourDetails.destination.name }}</p>
                <p class="text-gray-600 font-semibold">Pricing: KSH. {{ tourStore.tourDetails.price }}</p>
                <hr class="my-2">
                <p>{{ tourStore.tourDetails.description }}</p>
            </div>
            <!-- Booking Details -->
            <div v-if="bookingsStore.bookingDetails == null">
                <div class="my-4">
                    <button @click="bookingsStore.bookTour({ tour_id: $route.params.id })" type="button"
                        class="btn btn-primary" :disabled="bookingsStore.loading">
                        <IconSpinner class="animate-spin" v-show="bookingsStore.loading" /> Book Tour
                    </button>
                    <ValidationError :errors="bookingsStore.errors" field="tour_id" />
                    <ValidationError :errors="bookingsStore.errors" field="general" />
                </div>
            </div>
            <div v-else>
                <h2 class="text-xl font-semibold">Booking Details</h2>
                <p class="text-gray-600">Booked on: {{ Date(bookingsStore.bookingDetails.created_at) }}</p>
                <p class="text-gray-600">Status: {{ bookingsStore.bookingDetails.status.toUpperCase() }}</p>
                <p class="text-gray-600">Name: {{ bookingsStore.bookingDetails.user.name }}</p>
                <p class="text-gray-600">Total Price: KSH. {{ bookingsStore.bookingDetails.total_price }}</p>
                <div>
                    <p class="text-gray-600">No. of Tickets: {{ bookingsStore.bookingDetails.tickets.length }}</p>
                    <ul v-for="ticket in bookingsStore.bookingDetails.tickets" :key="ticket.id">
                        <li class="text-gray-600">Ticket Number: {{ ticket.ticket_number }}</li>
                    </ul>
                </div>

                <form @submit.prevent="generateBookingTicket" novalidate>
                    <div class="flex flex-col my-4 p-4 md:w-96 w-full border-2 border-gray-200">
                        <h1 class="text-2xl font-bold mb-4 text-center">Enter number of tickets</h1>
                        <div class="flex flex-col gap-2 mb-4">
                            <label for="slots" class="required">Slots</label>
                            <input min="1" v-model="noOfSlots" id="slots" name="slots" type="number" class="form-input"
                                autofocus autocomplete="slots" required :disabled="ticketsStore.loading" />
                            <ValidationError :errors="ticketsStore.errors" field="slots" />
                            <ValidationError :errors="ticketsStore.errors" field="general" />
                        </div>

                        <div class="flex flex-col gap-2">
                            <button type="submit" class="btn btn-primary" :disabled="ticketsStore.loading">
                                <IconSpinner class="animate-spin" v-show="ticketsStore.loading" />
                                Generate Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useTours } from '@/stores/tours';
import { useRoute } from 'vue-router';
import { useBookings } from '@/stores/bookings';
import { useTickets } from '@/stores/tickets';

const route = useRoute();
const tourStore = useTours();
const bookingsStore = useBookings();
const ticketsStore = useTickets();

let noOfSlots = ref(1);

tourStore.getTour(route.params.id)
bookingsStore.getUserBooking(route.params.id)

function generateBookingTicket() {
    ticketsStore.generateTicket(bookingsStore.bookingDetails.id, noOfSlots.value)
    bookingsStore.getUserBooking(route.params.id)
    noOfSlots.value = 1;
}

</script>
