<script setup lang="ts">
import Picker from '@/components/images/Picker.vue';

const props = defineProps({
    subblock: {
        type: Object as () => { type: string; imageId?: number | null },
        required: true,
    },
});

const emit = defineEmits(['update-subblock']);

const handleImagePicked = (image: { id: number }) => {
    emit('update-subblock', { ...props.subblock, imageId: image.id });
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <Picker :previewImageId="subblock.imageId ?? undefined" @image-picked="handleImagePicked" />
    </div>
</template>