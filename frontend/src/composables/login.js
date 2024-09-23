import { reactive, ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

export function useLogin() {
  const router = useRouter();
  const auth = useAuthStore();
  const errors = reactive({});
  const loading = ref(false);
  const form = reactive({
    email: '',
    password: '',
    remember: false
  });

  function resetForm() {
    form.email = '';
    form.password = '';
    form.remember = false;
    errors.value = {}
  }

  async function handleSubmit() {
    if (loading.value) return;

    loading.value = true;
    errors.value = {}

    try {
      await window.axios.post('auth/login', form);
      await auth.login();

      if (auth.isAuthenticated) {
        if (auth.isAdmin) {
          return router.push({ name: 'admin.bookings.index' });
        } else {
          return router.push({ name: 'tours.index' });
        }
      }
    } catch (error) {
      if (error.response?.status === 422) {
        errors.value = error.response.data.errors;
      }
    } finally {
      form.email = '';
      form.password = '';
      loading.value = false;
    }
  }

  return { form, errors, loading, resetForm, handleSubmit };
}
