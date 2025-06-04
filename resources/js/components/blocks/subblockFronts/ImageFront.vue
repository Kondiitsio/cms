<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    subblock: {
        type: Object,
        required: true,
    },
    imgClass: {
        type: String,
        default: '', // Default to an empty string if no class is provided
    },
});

// Reactive properties to store image data
const imageUrl = ref<string | null>(null);
const altText = ref<string | null>(null);

// Fetch image data based on imageId
const fetchImageData = async () => {
    if (!props.subblock.imageId) return;

    try {
        const response = await axios.get(`/api/images/${props.subblock.imageId}`);
        const image = response.data;

        // Set the image URL and alt text
        imageUrl.value = `/storage/${image.path}`;
        altText.value = image.description || 'Image';
    } catch (error) {
        console.error(`Failed to fetch image with ID ${props.subblock.imageId}:`, error);
    }
};

onMounted(fetchImageData);
</script>

<template>
    <div v-if="imageUrl">
        <img :src="imageUrl" :alt="altText || 'Image description not available'" :class="imgClass" />
    </div>
    <div v-else class="text-center text-red-500">Image not found</div>
</template>