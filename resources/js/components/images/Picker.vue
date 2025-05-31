<template>
        <div class="flex justify-center items-center align-middle gap-2">
            <!-- Image preview -->
            <div v-if="previewImageUrl" class="flex items-center gap-4">
                <img :src="previewImageUrl" alt="Selected Image" class="w-20 h-20 rounded-md border" />
            </div>

            <!-- Open image picker dialog -->
            <Button @click.prevent="chooseImage" variant="outline">
                {{ previewImageUrl ? 'Change Image' : 'Select Image' }}
            </Button>
        </div>
        <!-- Image picker dialog -->
        <Dialog v-model:open="showImagePickerDialog">
            <DialogScrollContent class="max-w-6xl p-6">
                <DialogTitle class="hidden">Select Image</DialogTitle>
                <DialogDescription class="hidden">In this dialog user can select Image file data.</DialogDescription>
                <Upload @image-uploaded="handleImageUploaded" />
                <ul
                    role="list"
                    class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8"
                >
                    <li
                        v-for="image in images"
                        :key="image.id"
                        class="relative border rounded-xl border-sidebar-border/70 dark:border-sidebar-border"
                    >
                        <div
                            class="items-center justify-center overflow-hidden rounded-lg sm:flex group focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-offset-gray-100"
                        >
                            <img
                                :src="getImageUrl(image)"
                                :alt="image.description || 'No description available'"
                                class="w-20 h-20 mx-auto sm:mx-0"
                            />
                            <div class="flex flex-col mx-2">
                                <Button class="mt-2" @click="pickImage(image)">Choose</Button>
                                <Button @click="editImage(image)" variant="secondary" class="mt-2">
                                    Edit
                                    <span class="sr-only">View details for {{ image.name || 'No name available' }}</span>
                                </Button>
                            </div>
                        </div>

                        <div class="flex flex-col items-center m-2">
                            <p
                                class="block ml-1 text-sm font-medium text-gray-500 truncate pointer-events-none"
                            >
                                {{ image.name || 'No title available' }}
                            </p>
                            <p class="block ml-1 text-sm font-medium text-gray-500 pointer-events-none">
                                {{ formatSize(image.size) }}
                            </p>
                        </div>
                    </li>
                </ul>
            </DialogScrollContent>
        </Dialog>

        <!-- Edit dialog -->
        <Edit
            :image="selectedImage"
            :isOpen="isEditOpen"
            @update:isOpen="isEditOpen = $event"
            @image-updated="handleImageUpdated"
            @image-deleted="handleImageDeleted"
        />
    
</template>

<script setup lang="ts">
import { type Image } from '@/types';
import { Button } from '@/components/ui/button';
import Edit from './Edit.vue';
import Upload from './Upload.vue';
import {
    Dialog,
    DialogScrollContent,
    DialogTitle,
    DialogDescription,
} from '@/components/ui/dialog';
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';

// Props
const props = defineProps({
    previewImageId: {
        type: Number,
        required: false, // Optional, as the image might not be selected yet
    },
});

// Emit event to notify parent about image selection
const emit = defineEmits(['image-picked']);

// Reactive properties
const images = ref<Image[]>([]);
const showImagePickerDialog = ref(false);
const selectedImage = ref<Image | null>(null);
const pickedImage = ref<Image | null>(null);
const isEditOpen = ref(false);
const previewImageUrl = ref<string | null>(null);

// Fetch the image URL based on the image ID
const getImageUrl = (image: Image) => {
    return image.thumbnail_path ? `/storage/${image.thumbnail_path}` : `/storage/${image.path}`;
};

const fetchPreviewImageUrl = async (imageId: number | null) => {
    if (!imageId) {
        previewImageUrl.value = null;
        return;
    }

    try {
        const response = await axios.get(`/api/images/${imageId}`);
        const image = response.data;
        previewImageUrl.value = getImageUrl(image);
    } catch (error) {
        console.error(`Failed to fetch image with ID ${imageId}:`, error);
        previewImageUrl.value = null;
    }
};

// Watch for changes to `previewImageId` and update the preview image URL
watch(
    () => props.previewImageId,
    (newImageId) => {
        fetchPreviewImageUrl(newImageId ?? null);
    },
    { immediate: true }
);

// Fetch images for the picker
const fetchImages = async () => {
    try {
        const response = await axios.get('/api/images');
        images.value = response.data;
    } catch (error) {
        console.error('Error fetching images:', error);
    }
};

// Handle image selection
const pickImage = (image: Image) => {
    pickedImage.value = image;
    emit('image-picked', image);
    showImagePickerDialog.value = false;
};

// Handle image editing
const editImage = (image: Image) => {
    selectedImage.value = image;
    isEditOpen.value = true;
};

// Handle image uploads and deletions
const handleImageUploaded = () => {
    fetchImages();
};

const handleImageUpdated = () => {
    fetchImages();
};

const handleImageDeleted = () => {
    fetchImages();
};

// Open the image picker dialog
const chooseImage = async () => {
    await fetchImages();
    showImagePickerDialog.value = true;
};

const formatSize = (size: number) => {
    const sizeInMB = size / (1024 * 1024);
    return sizeInMB.toFixed(3) + ' MB';
};

// Fetch images on component mount
onMounted(() => {
    fetchImages();
});
</script>



