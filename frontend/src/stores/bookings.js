import { ref, reactive } from "vue";
import { defineStore } from "pinia";

export const useBookings = defineStore("bookings", () => {
    const errors = reactive({})
    const loading = ref(false)
    const bookingDetails = ref(null)

    async function bookTour(data) {
        if (loading.value) return

        loading.value = true
        errors.value = {}

        return window.axios
            .post("bookings", data)
            .then((response) => {
                bookingDetails.value = response.data;
            })
            .catch((error) => {
                if (error.response.status === 422) {
                    errors.value = error.response.data.errors
                }
            })
            .finally(() => {
                loading.value = false
            });
    }

    async function getBooking(id) {
        if (loading.value) return

        loading.value = true
        errors.value = {}

        return window.axios
            .get(`bookings/${id}`)
            .then((response) => {
                bookingDetails.value = response.data.data
            })
            .finally(() => {
                loading.value = false
            });
    }

    async function getUserBooking(tour_id) {
        if (loading.value) return
        loading.value = true

        return window.axios
            .get(`bookings/${tour_id}/user`)
            .then((response) => {
                bookingDetails.value = response.data.data
            })
            .finally(() => {
                loading.value = false
            });
    }

    function resetErrors() {
        errors.value = {}
    }

    return {
        errors, loading, bookingDetails, bookTour, getBooking, getUserBooking, resetErrors
    };
})