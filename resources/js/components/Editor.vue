<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import Quill from 'quill';

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);

// Reference to the Quill editor container
const editorRef = ref<HTMLElement | null>(null);

// Quill instance
let quill: Quill | null = null;

// Watch for changes in `modelValue` and update the Quill editor content
watch(
    () => props.modelValue,
    (newValue) => {
        if (quill && newValue !== quill.root.innerHTML) {
            quill.root.innerHTML = newValue || '<p></p>'; // Ensure valid HTML
        }
    }
);

// Initialize Quill on mount
onMounted(() => {
    if (editorRef.value) {
        quill = new Quill(editorRef.value, {
            theme: 'snow', // Use the Snow theme
            placeholder: 'Start typing...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'], // Formatting buttons
                    [{ header: 2 }, { header: 3 }], // Header buttons
                    [{ list: 'ordered' }, { list: 'bullet' }], // List buttons
                    [{ color: ['#f88379', '#1DB954', '#3498db'] }], // Color and background
                    ['link'],              
                    ['clean'], // Remove formatting
                ],
            },
        });

        // Set initial content
        quill.root.innerHTML = props.modelValue || '<p></p>';

        // Listen for text changes and emit updates
        quill.on('text-change', () => {
            emit('update:modelValue', quill?.root.innerHTML || '');
        });
    }
});

// Clean up Quill instance on unmount
onBeforeUnmount(() => {
    quill = null;
});
</script>

<template>
    <div>
        <!-- Quill Editor Container -->
        <div ref="editorRef" class="border rounded-md"></div>
    </div>
</template>