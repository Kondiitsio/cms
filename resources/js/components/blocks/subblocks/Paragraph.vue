<script setup lang="ts">
import Textarea from '@/components/ui/textarea/Textarea.vue'
// Props
const props = defineProps({
    subblock: {
        type: Object as () => { type: string; text: string },
        required: true,
    },
});

// Emit event to notify parent about updates
const emit = defineEmits(['update-subblock']);

// Update the subblock's text
const updateText = (value: string | number) => {
    const updatedSubblock = { ...props.subblock, text: String(value) };
    emit('update-subblock', updatedSubblock);
};
</script>

<template>
    <Textarea
        :modelValue="subblock.text"
        @update:modelValue="updateText"
        rows="3"
        placeholder="Enter paragraph text"
    ></Textarea>
</template>