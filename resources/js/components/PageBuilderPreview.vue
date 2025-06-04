<script setup lang="ts">
import { Button } from '@/components/ui/button';
import PageComponent from '@/pages/Page.vue';
import {
    Dialog,
    DialogTrigger,
    DialogScrollContent,
    DialogTitle,
    DialogDescription
} from '@/components/ui/dialog';
import { type Page } from '@/types';

defineProps({
    page: {
        type: Object as () => Page,
        required: true,
    },
    blocks: {
        type: Array,
        required: true, // Pass edited blocks from PageBuilder.vue
    },
});
</script>

<template>
    <Dialog>
        <DialogTrigger as-child>
            <Button variant="secondary">Preview</Button>
        </DialogTrigger>
        <DialogScrollContent class="p-0 max-w-7xl">
            <DialogTitle class="hidden">Preview of the page.</DialogTitle>
            <DialogDescription class="hidden">This dialog shows a copy of the page currently being edited.</DialogDescription>
            <!-- Wrapper -->
            <div class="relative">
                <!-- Transparent overlay -->
                <div
                    class="absolute inset-0 z-10 bg-transparent cursor-not-allowed"
                ></div>
                <!-- Page content -->
                <PageComponent :page="page" :blocks="blocks" /> 
            </div>
        </DialogScrollContent>
    </Dialog>
</template>