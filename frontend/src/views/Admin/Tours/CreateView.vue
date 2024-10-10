<template>
    <form @submit.prevent="tourStore.createTour">
        <div class="flex flex-col mx-auto md:w-96 w-full">
            <h1 class="text-2xl font-bold mb-4 text-center">Create Tour</h1>

            <div class="flex flex-col gap-2 mb-4">
                <label for="name" class="required">Name:</label>
                <input type="text" name="name" id="name" class="form-input" v-model="tourStore.form.name"
                    :disabled="tourStore.loading" />
                <ValidationError :errors="tourStore.errors" field="name" />
            </div>

            <div class="flex flex-col gap-2 mb-4">
                <label for="description" class="required">Description</label>
                <textarea name="description" id="description" class="form-input" v-model="tourStore.form.description"
                    :disabled="tourStore.loading"></textarea>
                <ValidationError :errors="tourStore.errors" field="description" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="price" class="required"> Price</label>
                <input type="number" name="price" id="price" class="form-input" v-model="tourStore.form.price" min="0"
                    :disabled="tourStore.loading" />
                <ValidationError :errors="tourStore.errors" field="price" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="slots" class="required"> Slots</label>
                <input type="number" name="slots" id="slots" class="form-input" v-model="tourStore.form.slots" min="1"
                    :disabled="tourStore.loading" />
                <ValidationError :errors="tourStore.errors" field="slots" />
            </div>

            <div class="flex flex-col gap-2">
                <label for="destination_id" class="required">Destination</label>
                <select v-model="tourStore.form.destination_id" name="destination_id" id="destination_id"
                    class="form-input" :disabled="tourStore.loading || destinationService.loading.value">
                    <option v-if="destinationService.loading.value" disabled>Loading destinations...</option>
                    <option v-for="destination in destinationService.destinations.value" :value="destination.id"
                        :key="destination.id">
                        {{ destination.name }}
                    </option>
                </select>
                <ValidationError :errors="tourStore.errors" field="destination_id" />
                <div v-if="destinationService.errors" class="text-red-500">
                    {{ destinationService.errors.message }}
                </div>
            </div>

            <div class="flex flex-col gap-2 mb-1">
                <input type="file" name="image" id="image" class="form-input" accept="image/*" @change="previewImage" />
                <div v-if="imagePreview">
                    <img :src="imagePreview" alt="Tour Image Preview" width="200" />
                </div>
                <ValidationError :errors="tourStore.errors" field="image" />
            </div>

            <div class="border-t h-[1px] my-2"></div>

            <button type="submit" class="btn btn-primary" :disabled="tourStore.loading">
                <IconSpinner class="animate-spin" v-show="tourStore.loading" />
                <span v-if="tourStore.loading">Processing...</span>
                <span v-else>Create Tour</span>
            </button>
        </div>
    </form>
</template>

<script setup>
import { onBeforeMount, onMounted, ref } from 'vue'
import { useDestinations } from '@/composables/destinations'
import { useTours } from '@/stores/tours'

const imagePreview = ref(null);
const imageFile = ref(null);

const destinationService = useDestinations()
const tourStore = useTours()

onBeforeMount(tourStore.resetForm);

onMounted(async () => {
    await destinationService.getDestinations()

    if (destinationService.destinations.value.length > 0) {
        tourStore.form.destination_id = destinationService.destinations.value[0].id
    } else {
        destinationService.errors.message = "No destinations available.";
    }
});

const previewImage = (event) => {
    const files = event.target.files;

    if (files.length > 1) {
        alert('Please select only one file.');
        event.target.value = '';
        return;
    }

    const file = files[0];

    if (file) {
        imageFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
        tourStore.form.image = file;
    }
};
</script>