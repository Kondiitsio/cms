<script setup lang="ts">
import { ref, watch } from 'vue';
import SubblockManager from './SubblockManager.vue';
import Heading from './subblocks/Heading.vue';
import Paragraph from './subblocks/Paragraph.vue';
import Editor from './subblocks/Editor.vue';
import Spacer from './subblocks/Spacer.vue';
import EditorAndImage from './subblocks/EditorAndImage.vue';
import ImageAndEditor from './subblocks/ImageAndEditor.vue';
import Testimonial from './subblocks/Testimonial.vue';

const props = defineProps({
    block: {
        type: Object,
        required: true,
    },
    index: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(['update-block']);

// Create a local reactive copy of `block.content`
const content = ref(Array.isArray(props.block.content) ? props.block.content : []);

// Watch for changes in `props.block.content` and update the local copy
watch(
    () => props.block.content,
    (newContent) => {
        if (Array.isArray(newContent)) {
            content.value = newContent;
        }
    },
    { immediate: true }
);

const updateContent = (updatedContent: any[]) => {
    content.value = updatedContent; // Update the local copy
    emit('update-block', { index: props.index, content: updatedContent }); // Emit the updated content to the parent
};

const updateSubblock = (subIndex: number, updatedSubblock: any) => {
    const updatedContent = [...content.value];
    updatedContent[subIndex] = {
        ...updatedContent[subIndex],
        ...updatedSubblock,
    };
    updateContent(updatedContent);
};

const subblockTypes = [
    { label: 'Heading', value: 'Heading' },
    { label: 'Paragraph', value: 'Paragraph' },
    { label: 'Editor', value: 'Editor' },
    { label: 'Spacer', value: 'Spacer' },
    { label: 'Editor <-> Image', value: 'EditorAndImage' },
    { label: 'Image <-> Editor', value: 'ImageAndEditor' },
    { label: 'Testimonial', value: 'Testimonial' },
];
</script>

<template>
    <div>
        <SubblockManager
            :content="content"
            :index="index"
            :subblockTypes="subblockTypes"
            @update-content="updateContent"
        >
        <template #default="{ subblock, subIndex }">
            <Heading
                v-if="subblock.type === 'Heading'"
                :subblock="subblock"
                @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
            />
            <Paragraph
                v-if="subblock.type === 'Paragraph'"
                :subblock="subblock"
                @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
            />
            <Editor
                v-if="subblock.type === 'Editor'"
                :subblock="subblock"
                @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
            />
            <Spacer
                v-if="subblock.type === 'Spacer'"
                :subblock="subblock"
                @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
            />
            <EditorAndImage
                v-if="subblock.type === 'EditorAndImage'"
                :subblock="subblock"
                @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
            />
            <ImageAndEditor
                v-if="subblock.type === 'ImageAndEditor'"
                :subblock="subblock"
                @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
            />
            <Testimonial
                v-if="subblock.type === 'Testimonial'"
                :subblock="subblock"
                @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
            />
        </template>
        </SubblockManager>
    </div>
</template>