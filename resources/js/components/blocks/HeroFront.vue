<script setup lang="ts">
import ImageFront from './subblockFronts/ImageFront.vue';

defineProps({
    block: {
        type: Object,
        required: true,
    },
});
</script>

<template>
<section class="relative pb-8 overflow-hidden bg-[#0A2540] isolate pt-6">
    <div class="absolute inset-x-0 overflow-hidden -top-40 -z-10 transform-gpu blur-3xl sm:-top-80" aria-hidden="true">
    <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
    </div>
    <div class="px-6 mx-auto max-w-7xl pt-16 pb-32 lg:px-8 2xl:py-56">
      <div v-for="(subblock, index) in block.content" :key="index" class="flex justify-center my-8 max-w-2xl mx-auto">
        <div v-if="subblock.type === 'Image'" class="relative px-3 py-1 w-52 sm:w-72 text-[#f88379] rounded-full">
            <ImageFront :subblock="subblock" />
        </div>
        <h1 v-if="subblock.type === 'Heading'" class="text-center text-5xl font-semibold tracking-tight text-white text-balance sm:text-7xl">{{ subblock.text }}</h1>
        <p v-else-if="subblock.type === 'Paragraph'" class="text-center text-lg font-medium text-gray-400 font-roboto text-pretty sm:text-xl/8">{{ subblock.text }}</p>
        <div v-else-if="subblock.type === 'Buttons'" class="flex items-center justify-center gap-x-6">
            <div v-for="(button, buttonIndex) in subblock.buttons" :key="buttonIndex">
                <!-- Button with text and URL -->
                <a
                    v-if="button.style === 'colored'"
                    :href="button.url"
                    class="rounded-md bg-[#1DB954] px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#1A9E4A] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1DB954]"
                >
                    {{ button.text }}
                </a>

                <!-- Button with arrow -->
                <a
                    v-else-if="button.style === 'arrow'"
                    :href="button.url"
                    class="font-semibold hover:underline text-white text-sm/6 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#1DB954]"
                >
                    {{ button.text }} <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
      </div>
    </div>
    <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
    <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" />
    </div>
</section>


</template>