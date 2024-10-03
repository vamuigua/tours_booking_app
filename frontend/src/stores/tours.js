import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import { useRouter } from 'vue-router'

export const useTours = defineStore('tours', () => {
    const router = useRouter()
    const errors = reactive({})
    const loading = ref(false)
    const tours = ref([])
    const tourDetails = ref(null)
    const form = reactive({
        name: '',
        description: '',
        price: 0,
        slots: 1,
        destination_id: null
    })

    async function getTours() {
        try {
            const response = await window.axios.get('tours');
            tours.value = response.data.data
        } catch (error) {
            console.error('Error fetching tours:', error);
        }
    }

    function getTour(id) {
        return window.axios.get(`tours/${id}`).then((response) => (
            tourDetails.value = response.data.data
        ))
    }

    function resetErrors() {
        errors.value = {}
    }

    function resetForm() {
        form.value = {
            name: '',
            description: '',
            price: 0,
            slots: 1,
            destination_id: null
        }

        resetErrors()
    }

    async function createTour() {
        if (loading.value) return

        loading.value = true
        resetErrors()

        return window.axios
            .post("tours", form)
            .then(() => {
                router.push({ name: 'tours.index' })
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

    return { errors, loading, getTours, getTour, tours, tourDetails, resetErrors, resetForm, createTour, form };
});