import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

export function useLogout() {
    const auth = useAuthStore();
    const router = useRouter();
    const errors = reactive({});

    async function handleSubmit() {
        try {
            await window.axios.post('auth/logout');
            await auth.destroyTokenAndRedirect();
        } catch (error) {
            if (error.response?.status === 422) {
                errors.value = error.response.data.errors;
            }
        } finally {
            router.push({ name: 'login' });
        }
    }

    return { handleSubmit, errors };
}