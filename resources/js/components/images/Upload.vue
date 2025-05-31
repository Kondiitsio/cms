<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';

const successMessage = ref<string>('');
const fileInput = ref<HTMLInputElement | null>(null);

interface ImageForm {
  image: File | null;
  [key: string]: File | null;
}

const form = useForm<ImageForm>({
  image: null,
});

const emit = defineEmits(['image-uploaded']);

const uploadImage = () => {
  form.post(route('images.upload'), {
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = 'Image uploaded successfully!';
      form.reset(); // Reset the form
      if (fileInput.value) {
        fileInput.value.value = ''; // Clear the file input field
      }
      emit('image-uploaded'); // Fetch the updated list of image items
      setTimeout(() => {
        successMessage.value = ''; // Clear the success message after 3 seconds
      }, 3000);
    },
    onError: () => {
      alert('Failed to upload image.');
    },
  });
};

const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    form.image = target.files[0];
    uploadImage(); // Automatically upload the Image when a file is selected
  }
};

const triggerFileInput = () => {
  if (fileInput.value) {
    fileInput.value.click(); // Programmatically trigger the file input click event
  }
};
</script>

<template>
    <form @submit.prevent="uploadImage" enctype="multipart/form-data" class="mb-4">
        <input type="file" name="image" id="image" @change="onFileChange" ref="fileInput" style="display: none;" required>
        <Button @click.prevent="triggerFileInput" variant="outline">Upload</Button>
        <span v-if="successMessage" class="ml-4 text-green-500">{{ successMessage }}</span>
    </form>
</template>