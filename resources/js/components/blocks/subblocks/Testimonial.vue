<script setup lang="ts">
import Picker from '@/components/images/Picker.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import Input from '@/components/ui/input/Input.vue';

// Props
const props = defineProps({
    subblock: {
        type: Object as () => { type: string; text: string; imageId?: number | null; author?: string; position?: string },
        required: true,
    },
});

// Emit event to notify parent about updates
const emit = defineEmits(['update-subblock']);

// Update a field in the subblock
const updateField = (field: string, value: unknown) => {
    const updatedSubblock = { ...props.subblock, [field]: String(value ?? '') };
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
        <Textarea
            :modelValue="subblock.text ?? ''"
            @update:modelValue="(val: string) => updateField('text', val)"
            rows="3"
            placeholder="Enter testimonial text"
        />
        <Input
            type="text"
            :modelValue="subblock.author ?? ''"
            @update:modelValue="val => updateField('author', val)"
            placeholder="Enter author name"
        />
        <Input
            type="text"
            :modelValue="subblock.position ?? ''"
            @update:modelValue="val => updateField('position', val)"
            placeholder="Enter author position"
        />
    </div>
</template>