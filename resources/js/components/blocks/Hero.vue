<script setup lang="ts">
import SubblockManager from './SubblockManager.vue';
import Heading from './subblocks/Heading.vue';
import Paragraph from './subblocks/Paragraph.vue';
import Image from './subblocks/Image.vue';
import Buttons from './subblocks/Buttons.vue';

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

const updateContent = (updatedContent: any[]) => {
    emit('update-block', { index: props.index, content: updatedContent });
};

const updateSubblock = (subIndex: number, updatedSubblock: any) => {
    const updatedContent = [...props.block.content];
    updatedContent[subIndex] = {
        ...updatedContent[subIndex],
        ...updatedSubblock,
    };
    updateContent(updatedContent);
};

const subblockTypes = [
    { label: 'Heading', value: 'Heading' },
    { label: 'Paragraph', value: 'Paragraph' },
    { label: 'Image', value: 'Image' },
    { label: 'Buttons', value: 'Buttons' },
];
</script>

<template>
    <div>
        <SubblockManager
            :content="block.content"
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
                    v-else-if="subblock.type === 'Paragraph'"
                    :subblock="subblock"
                    @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
                />
                <Image
                    v-else-if="subblock.type === 'Image'"
                    :subblock="subblock"
                    @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
                />
                <Buttons
                    v-else-if="subblock.type === 'Buttons'"
                    :subblock="subblock"
                    @update-subblock="(updatedSubblock) => updateSubblock(subIndex, updatedSubblock)"
                />
            </template>
        </SubblockManager>
    </div>
</template>