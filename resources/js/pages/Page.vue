<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import HeroFront from '@/components/blocks/HeroFront.vue';
import ContentFront from '@/components/blocks/ContentFront.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    page: {
        type: Object,
        required: true,
    },
    blocks: {
        type: Array as () => unknown[], // Explicitly define the type as an array of unknown
        required: false, // Optional prop for edited blocks
    },
});

// Reactive property to store blocks data
interface Block {
    id: number;
    title: string;
    content: any; // Adjust the type of content if you know its structure
}

const blocksData = ref<Block[]>([]);

// Fetch blocks data if not provided via props
const fetchBlocks = async () => {
    if (props.blocks) {
        blocksData.value = props.blocks as Block[];
    } else {
        // Fetch blocks from the database
        try {
            const response = await axios.get(`/api/blocks/${props.page.id}`);
            blocksData.value = response.data.map((block: any) => ({
                ...block,
                content: JSON.parse(block.content), // Parse the JSON string into an object
            }));
        } catch (error) {
            console.error('Failed to fetch blocks:', error);
        }
    }
};

// Fetch blocks on component mount
onMounted(fetchBlocks);
</script>

<template>
    <Head :title="page.title">
        <meta name="description" :content="page.seo" />
    </Head>
    <div class="bg-[#F8F9FA]">
        <!-- Render blocks dynamically -->
        <div v-for="block in blocksData" :key="block.id">
            <HeroFront v-if="block.title === 'Hero'" :block="block" />
            <ContentFront v-else-if="block.title === 'Content'" :block="block" id="content" />
            <div v-else class="text-red-500">Unknown block title: {{ block.title }}</div>
        </div>
    </div>
</template>