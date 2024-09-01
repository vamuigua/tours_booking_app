import { ref, reactive } from "vue";
import { defineStore } from "pinia";

export const useTickets = defineStore("tickets", () => {
    const errors = reactive({})
    const loading = ref(false)
    const tickets = ref(null)

    async function getTickets() {
        if (loading.value) return
        loading.value = true

        return window.axios
            .get("tickets")
            .then((response) => {
                tickets.value = response.data.data
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

    async function generateTicket(booking_id, slots) {
        if (loading.value) return
        loading.value = true

        return window.axios
            .post('tickets', { booking_id: booking_id, slots: slots })
            .then((response) => {
                tickets.value = response.data
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


    function resetErrors() {
        errors.value = {}
    }

    return { errors, loading, tickets, getTickets, generateTicket, resetErrors };
})