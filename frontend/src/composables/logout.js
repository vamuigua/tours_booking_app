import { reactive } from "vue";
import { useAuthStore } from "@/stores/auth";

export function useLogout() {
    const auth = useAuthStore();
    const errors = reactive({});

    async function handleSubmit() {
        try {
            await window.axios.post('auth/logout');
            await auth.destroyTokenAndRedirectTo();
        } catch (error) {
            if (error.response?.status === 422) {
                errors.value = error.response.data.errors;
            }
        }
    }

    return { handleSubmit, errors };
}