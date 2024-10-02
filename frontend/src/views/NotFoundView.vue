<template>
    <div class="not-found">
        <h1>404</h1>
        <p>Oops! The page you're looking for doesn't exist.</p>
        <router-link :to="{ name: 'home' }">Go back to Home</router-link>
        <br />
        <a v-if="canGoBack" @click.prevent="goBack">Go back to Last Page</a>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

export default {
    name: 'NotFound',
    setup() {
        const canGoBack = ref(false);
        const router = useRouter();

        onMounted(() => {
            if (window.history.length > 1) {
                canGoBack.value = true;
            }
        });

        const goBack = () => {
            router.back();
        };

        return {
            canGoBack,
            goBack
        };
    }
}
</script>

<style scoped>
.not-found {
    text-align: center;
    margin-top: 100px;
}

h1 {
    font-size: 6rem;
    margin-bottom: 20px;
    color: #ff6b6b;
}

p {
    font-size: 1.5rem;
    margin-bottom: 20px;
}

a {
    text-decoration: none;
    color: #3498db;
    font-size: 1.2rem;
    cursor: pointer;
}
</style>