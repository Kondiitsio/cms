<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Upload from '@/components/images/Upload.vue';
import ShowAll from '@/components/images/ShowAll.vue';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Images',
        href: '/images',
    },
];

const showAllRef = ref();

const handleImageUploaded = () => {
    if (showAllRef.value) {
        showAllRef.value.fetchImages();
    }
};

</script>

<template>
    <Head title="Images" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <div class="relative min-h-[100vh] flex-1 md:min-h-min">
                <Upload @image-uploaded="handleImageUploaded" />
                <ShowAll ref="showAllRef" />
            </div>
        </div>
    </AppLayout>
</template>