<script setup lang="ts">
import Picker from '@/components/images/Picker.vue';
import Editor from '@/components/Editor.vue'; // Updated Editor component using Quill

// Props
const props = defineProps({
    subblock: {
        type: Object as () => { type: string; text: string; imageId?: number | null },
        required: true,
    },
});

// Emit event to notify parent about updates
const emit = defineEmits(['update-subblock']);

// Update the subblock's text
const updateText = (newText: string) => {
    const updatedSubblock = { ...props.subblock, text: newText };
    emit('update-subblock', updatedSubblock);
};

// Handle image selection
const handleImagePicked = (image: { id: number }) => {
    const updatedSubblock = { ...props.subblock, imageId: image.id };
    emit('update-subblock', updatedSubblock);
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <!-- Image picker -->
        <div>
            <Picker :previewImageId="subblock.imageId ?? undefined" @image-picked="handleImagePicked" />
        </div>
        <!-- Editor for text input -->
        <Editor :modelValue="subblock.text || '<p></p>'" @update:modelValue="updateText" />
    </div>
</template>