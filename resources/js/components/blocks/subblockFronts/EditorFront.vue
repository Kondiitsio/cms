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
    line-height: 2rem;
}

.quill-content h2 {
    font-size: 3rem;
    line-height: 1.2;
    font-weight: 600;
    letter-spacing: -0.025em;
    color: #0A2540;
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
    color: #0A2540;
    text-wrap: balance;
}

@media (min-width: 640px) {
    .quill-content h3 {
        font-size: 2.5rem;
        line-height: 1.1;
    }
}

.quill-content p {
    font-size: 1.125rem;
    color: #4a5565;
}

.quill-content strong {
    font-weight: 600;
    color: #0A2540;
}

.quill-content em {
    font-style: italic;
}

.quill-content a {
    color: #3498db;
    text-decoration: underline;
}
</style>