<template>
    <ul role="list" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
      <li v-for="image in images" :key="image.id" class="relative border rounded-xl border-sidebar-border/70 dark:border-sidebar-border">
        <div class="flex overflow-hidden rounded-lg group focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100">
          <img :src="getImageUrl(image)" :alt="image.description || 'No description available'" class="w-20 h-20 pointer-events-none group-hover:opacity-75" />
          <button type="button" @click="editImage(image)" class="absolute inset-0 focus:outline-none">
            <span class="sr-only">View details for {{ image.name }}</span>
          </button>
          <div class="flex-none">
            <p class="block mt-2 ml-1 text-sm font-medium text-gray-500 truncate pointer-events-none">{{ image.name }}</p>
            <p class="block ml-1 text-sm font-medium text-gray-500 pointer-events-none">{{ formatSize(image.size) }}</p>
          </div>
        </div>
      </li>
    </ul>
    <Edit
      :image="selectedImage"
      :isOpen="isEditOpen"
      @update:isOpen="isEditOpen = $event"
      @image-updated="handleImageUpdated"
      @image-deleted="handleImageDeleted" />
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import Edit from './Edit.vue';
  import { type Image } from '@/types';
  
  const images = ref<Image[]>([]);
  const selectedImage = ref<Image | null>(null);
  const isEditOpen = ref(false);
  
  const editImage = (image: Image) => {
    selectedImage.value = image;
    isEditOpen.value = true;
  };
  
  const fetchImages = async () => {
    try {
      const response = await axios.get('/api/images');
      images.value = response.data;
    } catch (error) {
      console.error('Error fetching images:', error);
    }
  };
  
  const handleImageUpdated = () => {
    fetchImages(); // Refresh the Image items list
  };
  
  const handleImageDeleted = () => {
    fetchImages(); // Refresh the Image items list
  };
  
  const getImageUrl = (image: Image) => {
      const path = image.thumbnail_path ? image.thumbnail_path : image.path;
      return `/storage/${path.replace(/^\/?storage\//, '')}`;
  }
  
  const formatSize = (size: number) => {
    const sizeInMB = size / (1024 * 1024);
    return sizeInMB.toFixed(3) + ' MB';
  };
  
  onMounted(() => {
    fetchImages();
  });
  
  defineExpose({
    fetchImages,
  });
  </script>