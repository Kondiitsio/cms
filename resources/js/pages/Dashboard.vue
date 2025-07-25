<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
// import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { type Image, Page } from '@/types';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const images = ref<Image[]>([]);
const pages = ref<Page[]>([]);

const fetchImages = async () => {
    try {
      const response = await axios.get('/api/images');
      images.value = response.data;
    } catch (error) {
      console.error('Error fetching images:', error);
    }
};

const imageCount = computed(() => images.value.length);

const totalSizeMB = computed(() => {
  const totalBytes = images.value.reduce((sum, img) => sum + (img.size || 0), 0);
  return (totalBytes / (1024 * 1024)).toFixed(2);
});

const fetchPages = async () => {
    try {
        const response = await axios.get('/api/pages');
        pages.value = response.data;
    } catch (error) {
        console.error('Error fetching pages:', error);
    }
};

const pageCount = computed(() => pages.value.length);

onMounted(() => {
    fetchImages();
    fetchPages();
});

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="mb-4 text-lg flex flex-col items-center justify-center h-full">
                        <div>Page count: {{ pageCount }}</div>
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="mb-4 text-lg flex flex-col items-center justify-center h-full">
                        <div>Image count: {{ imageCount }}</div>
                        <div>Total size: {{ totalSizeMB }} MB</div>
                    </div>
                </div>
                <!-- <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div> -->
            </div>
            <!-- <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div> -->
        </div>
    </AppLayout>
</template>
