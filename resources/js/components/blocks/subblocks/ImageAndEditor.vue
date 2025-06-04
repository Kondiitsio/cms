<script setup lang="ts">
import Picker from '@/components/images/Picker.vue';
import Editor from '@/components/Editor.vue';

const props = defineProps({
    subblock: {
        type: Object as () => { type: string; text: string; imageId?: number | null },
        required: true,
    },
});

const emit = defineEmits(['update-subblock']);

const updateText = (newText: string) => {
    const updatedSubblock = { ...props.subblock, text: newText };
    emit('update-subblock', updatedSubblock);
};

const handleImagePicked = (image: { id: number }) => {
    const updatedSubblock = { ...props.subblock, imageId: image.id };
    emit('update-subblock', updatedSubblock);
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <div>
            <Picker :previewImageId="subblock.imageId ?? undefined" @image-picked="handleImagePicked" />
        </div>
        <Editor :modelValue="subblock.text || '<p></p>'" @update:modelValue="updateText" />
    </div>
</template>