<script setup lang="ts">
import Editor from '@/components/Editor.vue';

const props = defineProps({
    subblock: {
        type: Object as () => { type: string; text: string; },
        required: true,
    },
});

const emit = defineEmits(['update-subblock']);

// Update the subblock's text
const updateText = (newText: string) => {
    const updatedSubblock = { ...props.subblock, text: newText };
    emit('update-subblock', updatedSubblock);
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <Editor :modelValue="subblock.text || '<p></p>'" @update:modelValue="updateText" />
    </div>
</template>