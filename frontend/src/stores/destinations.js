import { ref, reactive } from "vue";
import { defineStore } from "pinia";

export const useDestinations = defineStore("tickets", () => {
    const errors = reactive({})
    const loading = ref(false)
    const destinations = ref([])

    function getDestinations() {
        return window.axios.get("destinations").then((response) => (
            destinations.value = response.data.data
        ))
    }

    return { errors, loading, destinations, getDestinations }
});