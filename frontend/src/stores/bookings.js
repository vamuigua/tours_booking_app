import { ref, reactive, computed } from "vue";
import { defineStore } from "pinia";

export const useBookings = defineStore("bookings", () => {
    const errors = reactive({})
    const loading = ref(false)
    const bookingDetails = ref(null)
    const bookings = ref([]);
    const searchQuery = ref('');
    const sortKey = ref('');
    const sortAsc = ref(true);
    const currentPage = ref(1);
    const perPage = ref(10);

    async function fetchBookings() {
        const response = await window.axios.get('bookings');
        bookings.value = response.data.data;
    }

    function bookTour(data) {
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

    function getBooking(id) {
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

    function getUserBooking(tour_id) {
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

    const filteredBookings = computed(() => {
        let filtered = bookings.value;

        if (searchQuery.value) {
            const query = searchQuery.value.toLowerCase();
            filtered = filtered.filter((booking) => {
                return (
                    booking.user.name.toLowerCase().includes(query) ||
                    booking.tour.name.toLowerCase().includes(query) ||
                    booking.status.toLowerCase().includes(query) ||
                    booking.tickets.length.toString().includes(query) ||
                    booking.total_price.toLowerCase().includes(query)
                );
            });
        }

        if (sortKey.value) {
            filtered.sort((a, b) => {
                const aKey = resolveSortKey(a, sortKey.value);
                const bKey = resolveSortKey(b, sortKey.value);

                if (aKey < bKey) return sortAsc.value ? -1 : 1;
                if (aKey > bKey) return sortAsc.value ? 1 : -1;
                return 0;
            });
        }

        const start = (currentPage.value - 1) * perPage.value;
        const end = currentPage.value * perPage.value;
        return filtered.slice(start, end);
    });

    const pageCount = computed(() => {
        return Math.ceil(bookings.value.length / perPage.value);
    });

    function handlePageChange(page) {
        currentPage.value = page;
    }

    function sortBy(key) {
        if (sortKey.value === key) {
            sortAsc.value = !sortAsc.value;
        } else {
            sortKey.value = key;
            sortAsc.value = true;
        }
    }

    // Helper function to resolve nested keys for sorting
    function resolveSortKey(object, key) {
        return key.split('.').reduce((o, i) => (o ? o[i] : null), object);
    }

    return {
        errors,
        loading,
        bookingDetails,
        bookTour,
        getBooking,
        getUserBooking,
        resetErrors,
        bookings,
        searchQuery,
        sortKey,
        sortAsc,
        currentPage,
        perPage,
        filteredBookings,
        pageCount,
        fetchBookings,
        handlePageChange,
        sortBy,
    };
})