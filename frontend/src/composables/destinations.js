import { ref, reactive } from "vue";

export function useDestinations() {
    const loading = ref(false)
    const errors = reactive({})
    const destinations = ref([])

    async function getDestinations() {
        if (loading.value) return;
        loading.value = true;
        errors.value = {};

        try {
            const response = await window.axios.get("destinations")
            destinations.value = response.data.data
        } catch (error) {
            errors.message = "Failed to fetch destinations.";
        } finally {
            loading.value = false;
        }
    }

    return { errors, loading, destinations, getDestinations }
};