<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Page } from '@/types';
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Hero from '@/components/blocks/Hero.vue';
import Content from '@/components/blocks/Content.vue';
import PageBuilderPreview from '@/components/PageBuilderPreview.vue';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SquarePlus, ChevronDown, ChevronUp, OctagonX } from 'lucide-vue-next';
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from '@/components/ui/collapsible'
import { Label } from '@/components/ui/label';
import Button from '@/components/ui/button/Button.vue';

const props = defineProps({
    page: {
        type: Object as () => Page,
        required: true,
    },
});

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
    {
        title: props.page.title,
        href: `/pages/${props.page.slug}`,
    },
]);

const blocks = ref<{ id: number | null; title: string; content: any; order: number }[]>([]);
const newBlockType = ref(''); // For selecting block type
const openStates = ref<boolean[]>([]);

const fetchBlocks = async () => {
    try {
        const response = await axios.get(`/api/blocks/${props.page.id}`);
        blocks.value = response.data.map((block: any) => ({
            ...block,
            content: block.content ? JSON.parse(block.content) : [], // Parse JSON content or default to an empty array
        }));
        openStates.value = blocks.value.map(() => false); // Initialize open states for each block
    } catch (error) {
        console.error('Error fetching blocks:', error);
    }
};

// Add a new block immediately after selecting a type
const createBlock = (index: number) => {
    if (!newBlockType.value) return;

    const newBlock = {
        id: null,
        title: newBlockType.value,
        content: newBlockType.value === 'Hero' ? [] : { title: '' },
        order: index,
    };

    blocks.value.splice(index, 0, newBlock); // Insert the new block at the specified index
    openStates.value.splice(index, 0, true); // Open the new block's Collapsible
    updateBlockOrder(); // Update the order of all blocks
    newBlockType.value = ''; // Reset the block type selection
};

const deleteBlock = (index: number) => {
    blocks.value.splice(index, 1); // Remove the block from the list
    updateBlockOrder(); // Update the order of remaining blocks
};

const moveBlockUp = (index: number) => {
    if (index > 0) {
        const temp = blocks.value[index];
        blocks.value[index] = blocks.value[index - 1];
        blocks.value[index - 1] = temp;
        updateBlockOrder(); // Update the order values locally
    }
};

const moveBlockDown = (index: number) => {
    if (index < blocks.value.length - 1) {
        const temp = blocks.value[index];
        blocks.value[index] = blocks.value[index + 1];
        blocks.value[index + 1] = temp;

        updateBlockOrder(); // Update the order values locally
    }
};

const updateBlockOrder = () => {
    blocks.value.forEach((block, index) => {
        block.order = index;
    });
};

const updateBlock = ({ index, content }: { index: number; content: any }) => {
    blocks.value[index].content = content;
};

const saveChanges = async () => {
    try {
        const payload = blocks.value.map((block) => ({
            id: block.id,
            title: block.title,
            content: block.content,
            order: block.order,
            page_id: props.page.id,
        }));

        await axios.post('/api/blocks/batch-update', { blocks: payload });
        alert('Changes saved successfully!');
        fetchBlocks(); // Refresh the blocks from the database
    } catch (error) {
        console.error('Error saving changes:', error);
    }
};


onMounted(() => {
    fetchBlocks();
});
</script>

<template>
    <Head :title="`Pages ${page.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col rounded-xl p-4">

            <!-- Dropdown Above All Blocks -->
            <div class="flex justify-center mb-4">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <SquarePlus value="" disabled class="cursor-pointer" />
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="w-56">
                        <DropdownMenuItem
                        @click="() => { newBlockType = 'Hero'; createBlock(0); }"
                        >
                        <span>Hero</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem
                        @click="() => { newBlockType = 'Content'; createBlock(0); }"
                        >
                        <span>Content</span>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <!-- Blocks List -->
            <div v-for="(block, index) in blocks" :key="index"> 
                <div class="p-4 border rounded-xl border-sidebar-border/70 dark:border-sidebar-border">
                    <Collapsible v-model:open="openStates[index]">
                        <div class="flex">
                            <div class="flex gap-2">
                                <ChevronUp
                                    v-if="index > 0"
                                    class="cursor-pointer"
                                    @click="moveBlockUp(index)"
                                />
                                <ChevronDown
                                    v-if="index < blocks.length - 1"
                                    class="cursor-pointer"
                                    @click="moveBlockDown(index)"
                                />
                                <OctagonX
                                    class="cursor-pointer text-red-500"
                                    @click="deleteBlock(index)"
                                />
                            </div>
                        <CollapsibleTrigger class="flex items-center justify-end w-full gap-2 cursor-pointer">
                            <Label class="cursor-pointer">{{ block.title }}</Label>
                            <ChevronDown />
                        </CollapsibleTrigger>
                        </div>
                        <CollapsibleContent class="mt-4">
                        <Hero
                            v-if="block.title === 'Hero'"
                            :block="block"
                            :index="index"
                            @update-block="updateBlock"
                        />
                        <Content
                            v-else-if="block.title === 'Content'"
                            :block="block"
                            :index="index"
                            @update-block="updateBlock"
                        />
                        </CollapsibleContent>
                    </Collapsible>
                </div>
                

                <div class="flex justify-center my-4">
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <SquarePlus value="" disabled class="cursor-pointer" />
                        </DropdownMenuTrigger>
                        <DropdownMenuContent class="w-56">
                            <DropdownMenuItem
                            @click="() => { newBlockType = 'Hero'; createBlock(index + 1); }"
                            >
                            <span>Hero</span>
                            </DropdownMenuItem>
                            <DropdownMenuItem
                            @click="() => { newBlockType = 'Content'; createBlock(index + 1); }"
                            >
                            <span>Content</span>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <PageBuilderPreview :page="page" :blocks="blocks" />
                <Button
                    @click="saveChanges"
                >
                    Save Changes
                </Button>
            </div>
        </div>
    </AppLayout>
</template>