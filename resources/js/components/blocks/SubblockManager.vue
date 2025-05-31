<script setup lang="ts">
import { ref } from 'vue';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SquarePlus, ChevronDown, ChevronUp, OctagonX } from 'lucide-vue-next';
import { Label } from '@/components/ui/label';

// Props
const props = defineProps({
    content: {
        type: Array as () => { type: string; text: string; }[],
        required: true,
    },
    index: {
        type: Number,
        required: true,
    },
    subblockTypes: {
        type: Array as () => { label: string; value: string; }[],
        required: true,
    },
});

// Emit event to notify parent about updates
const emit = defineEmits(['update-content']);

// Local state for subblock type selection
const newSubblockType = ref('');

// Add a subblock
const addSubblock = (subblockIndex: number, type?: string) => {
    const subblockType = type ?? newSubblockType.value;
    if (!subblockType) return;

    // Dynamically create the subblock based on its type
    const newSubblock: any = { type: subblockType };
    if (subblockType !== 'Image' && subblockType !== 'Buttons' && subblockType !== 'Spacer') {
        newSubblock.text = ''; // Add `text` only for non-Image subblocks
    }

    const updatedContent = [...props.content];
    updatedContent.splice(subblockIndex, 0, newSubblock); // Insert the new subblock at the specified index
    emit('update-content', updatedContent); // Emit the updated content
    newSubblockType.value = ''; // Reset the dropdown
};

// Delete a subblock
const deleteSubblock = (subblockIndex: number) => {
    const updatedContent = [...props.content];
    updatedContent.splice(subblockIndex, 1); // Remove the subblock
    emit('update-content', updatedContent); // Emit the updated content
};

// Move a subblock up
const moveSubblockUp = (subblockIndex: number) => {
    if (subblockIndex > 0) {
        const updatedContent = [...props.content];
        const temp = updatedContent[subblockIndex];
        updatedContent[subblockIndex] = updatedContent[subblockIndex - 1];
        updatedContent[subblockIndex - 1] = temp;
        emit('update-content', updatedContent); // Emit the updated content
    }
};

// Move a subblock down
const moveSubblockDown = (subblockIndex: number) => {
    if (subblockIndex < props.content.length - 1) {
        const updatedContent = [...props.content];
        const temp = updatedContent[subblockIndex];
        updatedContent[subblockIndex] = updatedContent[subblockIndex + 1];
        updatedContent[subblockIndex + 1] = temp;
        emit('update-content', updatedContent); // Emit the updated content
    }
};
</script>

<template>
    <div>
        
        <div class="flex justify-center mb-4">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <SquarePlus value="" disabled class="cursor-pointer" />
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="w-56">
                        <DropdownMenuItem
                            @click="addSubblock(0, type.value)"
                            v-for="type in subblockTypes"
                            :key="type.value"
                        >
                            <span>{{ type.label }}</span>
                        </DropdownMenuItem>
                     
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>



        <!-- Subblocks List -->
        <div v-for="(subblock, subIndex) in content" :key="subIndex">
            <div class="p-4 border rounded-xl border-sidebar-border/70 dark:border-sidebar-border">
                
                <div class="flex items-center justify-between mb-4">
                    <!-- Render Subblock Content -->
                    <Label>{{ subblock.type }}</Label>
                    

                    <!-- Subblock Action Buttons -->
                    <div class="flex items-center gap-2">
                    <ChevronUp v-if="subIndex > 0" class="cursor-pointer" @click="moveSubblockUp(subIndex)" />
                    <ChevronDown v-if="subIndex < content.length - 1" class="cursor-pointer" @click="moveSubblockDown(subIndex)" />
                    <OctagonX class="cursor-pointer text-red-500" @click="deleteSubblock(subIndex)" />
                    </div>
                </div>

                <slot :subblock="subblock" :subIndex="subIndex" />
            </div>


            <div class="flex justify-center my-4">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <SquarePlus value="" disabled class="cursor-pointer" />
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="w-56">
                        <DropdownMenuItem
                            @click="addSubblock(subIndex + 1, type.value)"
                            v-for="type in subblockTypes"
                            :key="type.value"
                        >
                            <span>{{ type.label }}</span>
                        </DropdownMenuItem>
                     
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

        </div>
    </div>
</template>