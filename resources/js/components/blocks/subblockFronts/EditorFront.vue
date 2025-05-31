<script setup lang="ts">
const props = defineProps({
    subblock: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div v-html="props.subblock.text" class="quill-content"></div>
</template>

<style>
.quill-content {
    /* font-family: 'Roboto', sans-serif; */
    line-height: 2rem;
}

.quill-content h2 {
    font-size: 3rem;
    line-height: 1.2;
    font-weight: 600;
    letter-spacing: -0.025em;
    color: #111827;
    text-wrap: balance;
}

@media (min-width: 640px) {
    .quill-content h2 {
        font-size: 3.75rem;
        line-height: 1.1;
    }
}

.quill-content h3 {
    font-size: 2rem;
    line-height: 1.2;
    font-weight: 600;
    letter-spacing: -0.025em;
    color: #111827;
    text-wrap: balance;
}

@media (min-width: 640px) {
    .quill-content h3 {
        font-size: 2.5rem;
        line-height: 1.1;
    }
}

.quill-content p {
    font-size: 1.125rem; /* Example: Adjust paragraph size */
    color: #4a5565; /* Example: Darker gray color */
}

.quill-content strong {
    font-weight: 600; /* Example: Bold text */
    color: #111827;
}

.quill-content em {
    font-style: italic; /* Example: Italic text */
}
</style>