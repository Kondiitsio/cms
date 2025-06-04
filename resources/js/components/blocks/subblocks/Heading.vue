<script setup lang="ts">
import Input from '@/components/ui/input/Input.vue';

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
    <Input
        :modelValue="subblock.text"
        @update:modelValue="updateText"
        type="text"
        placeholder="Enter heading text"
    />
</template>