<script setup lang="ts">
import { ChevronDown, ChevronUp, OctagonX } from 'lucide-vue-next';
import Input from '@/components/ui/input/Input.vue';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

interface Button {
    text: string;
    url: string;
    style: string;
}

interface Subblock {
    type: string;
    text?: string;
    buttons?: Button[];
}

const props = defineProps<{
    subblock: Subblock;
}>();

const emit = defineEmits<{
    (event: 'update-subblock', payload: Subblock): void;
}>();

const updateButton = (buttonIndex: number, key: keyof Button, value: string) => {
    const updatedButtons = [...(props.subblock.buttons || [])];
    updatedButtons[buttonIndex] = {
        ...updatedButtons[buttonIndex],
        [key]: value,
    };

    emit('update-subblock', { ...props.subblock, buttons: updatedButtons });
};

const addButton = () => {
    if ((props.subblock.buttons ?? []).length < 2) {
        emit('update-subblock', {
            ...props.subblock,
            buttons: [...(props.subblock.buttons ?? []), { text: '', url: '', style: 'default' }],
        });
    }
};

const removeButton = (buttonIndex: number) => {
    const updatedButtons = (props.subblock.buttons ?? []).filter((_, index) => index !== buttonIndex);
    emit('update-subblock', { ...props.subblock, buttons: updatedButtons });
};

const moveButton = (buttonIndex: number, direction: 'up' | 'down') => {
    const buttons = [...(props.subblock.buttons || [])];
    const targetIndex = direction === 'up' ? buttonIndex - 1 : buttonIndex + 1;

    // Swap the buttons
    [buttons[buttonIndex], buttons[targetIndex]] = [buttons[targetIndex], buttons[buttonIndex]];

    emit('update-subblock', { ...props.subblock, buttons });
};
</script>

<template>
    <div class="flex flex-col gap-4">
        <div
            v-for="(button, index) in subblock.buttons"
            :key="index"
            class="flex flex-col gap-2 p-4 border rounded-xl border-sidebar-border/70 dark:border-sidebar-border"
        >
        <div class="flex justify-center">
            <Select
                :modelValue="button.style"
                @update:modelValue="(val) => updateButton(index, 'style', String(val ?? ''))"
            >
                <SelectTrigger>
                <SelectValue placeholder="Select style" />
                </SelectTrigger>
                <SelectContent>
                <SelectGroup>
                    <SelectItem value="colored">
                    Green button
                    </SelectItem>
                    <SelectItem value="arrow">
                    Arrow right
                    </SelectItem>
                </SelectGroup>
                </SelectContent>
            </Select>
        </div>
        <div class="flex gap-2">
            <Label>Text</Label>
            <Input
                type="text"
                placeholder="Button Text"
                :modelValue="button.text"
                @update:modelValue="val => updateButton(index, 'text', String(val))"
            />
        </div>
        <div class="flex gap-2">
            <Label>Url</Label>
            <Input
                type="text"
                placeholder="Button Url"
                :modelValue="button.url"
                @update:modelValue="val => updateButton(index, 'url', String(val))"
            />
        </div>

            <!-- Move Up and Down Buttons -->
            <div class="flex justify-center gap-2">
                <ChevronUp
                    v-if="index > 0"
                    @click="moveButton(index, 'up')"
                >
                </ChevronUp>
                <ChevronDown
                    v-if="index < (subblock.buttons?.length ?? 0) - 1"
                    @click="moveButton(index, 'down')"
                >
                </ChevronDown>
                <OctagonX
                    class="cursor-pointer text-red-500"
                    @click="removeButton(index)"
                />
            </div>
        </div>

        <!-- Add Button -->
        <button
            v-if="(subblock.buttons?.length ?? 0) < 2"
            type="button"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700"
            @click="addButton"
        >
            Add Button
        </button>
    </div>
</template>