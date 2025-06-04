<script setup lang="ts">
import Picker from '@/components/images/Picker.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import Input from '@/components/ui/input/Input.vue';

const props = defineProps({
    subblock: {
        type: Object as () => { type: string; text: string; imageId?: number | null; author?: string; position?: string },
        required: true,
    },
});

const emit = defineEmits(['update-subblock']);

const updateField = (field: string, value: unknown) => {
    const updatedSubblock = { ...props.subblock, [field]: String(value ?? '') };
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
        <Textarea
            :modelValue="subblock.text ?? ''"
            @update:modelValue="(val: string | number) => updateField('text', val)"
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