import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'

export const useTours = defineStore('tours', () => {
    const errors = reactive({})
    const loading = ref(false)
    const tours = ref([])
    const tourDetails = ref(null)

    function getTours() {
        return window.axios.get('tours').then((response) => {
            tours.value = response.data.data;
        })
    }

    function getTour(id) {
        return window.axios.get(`tours/${id}`).then((response) => {
            tourDetails.value = response.data.data
        })
    }

    return { errors, loading, getTours, getTour, tours, tourDetails };
});