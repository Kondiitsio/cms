<script setup lang="ts">
import Picker from '@/components/images/Picker.vue';

// Props
const props = defineProps({
    subblock: {
        type: Object as () => { type: string; imageId?: number | null },
        required: true,
    },
});

// Emit event to notify parent about updates
const emit = defineEmits(['update-subblock']);

// Handle image selection
const handleImagePicked = (image: { id: number }) => {
    emit('update-subblock', { ...props.subblock, imageId: image.id });
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <!-- Use Picker with previewImageId -->
        <Picker :previewImageId="subblock.imageId ?? undefined" @image-picked="handleImagePicked" />
    </div>
</template>