<script setup lang="ts">
import Textarea from '@/components/ui/textarea/Textarea.vue'

const props = defineProps({
    subblock: {
        type: Object as () => { type: string; text: string },
        required: true,
    },
});

const emit = defineEmits(['update-subblock']);

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