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
                <input type="number" name="price" id="price" class="form-input" v-model="tourStore.form.price"
                    :disabled="tourStore.loading" />
            </div>
            <div class="flex flex-col gap-2">
                <label for="slots" class="required"> Slots</label>
                <input type="number" name="slots" id="slots" class="form-input" v-model="tourStore.form.slots"
                    :disabled="tourStore.loading" />
            </div>

            <div class="flex flex-col gap-2 mb-4">
                <label for="destination_id" class="required">Destination</label>
                <select v-model="tourStore.form.destination_id" name="destination_id" id="destination_id"
                    class="form-input" :disabled="tourStore.loading">
                    <option v-for="destination in destinationStore.destinations" :value="destination.id"
                        :key="destination.id">
                        {{ destination.name }}
                    </option>
                </select>
                <ValidationError :errors="tourStore.errors" field="destination_id" />
            </div>

            <div class="border-t h-[1px] my-6"></div>

            <button type="submit" class="btn btn-primary" :disabled="tourStore.loading">
                <IconSpinner class="animate-spin" v-show="tourStore.loading" />
                Create Tour
            </button>
        </div>
    </form>
</template>

<script setup>
import { onBeforeUnmount } from 'vue'
import { useDestinations } from '@/stores/destinations'
import { useTours } from '@/stores/tours'

const destinationStore = useDestinations()
const tourStore = useTours()

onBeforeUnmount(tourStore.resetForm)

destinationStore.getDestinations().then((response) => {
    if (response.length > 0) {
        tourStore.form.destination_id = response[0].id
    }
})
</script>