<template>
    <Dialog :open="isOpen" @update:open="updateOpen">
      <DialogContent class="max-w-xl p-6">
        <DialogTitle class="hidden">Edit image file</DialogTitle>
        <DialogDescription class="hidden">In this dialog user can edit image file data.</DialogDescription>
        <div v-if="image">
          <form @submit.prevent="handleSubmit">
            <Label>Name</Label>
            <Input
              id="name"
              type="text"
              class="block w-full mt-1 mb-3"
              placeholder="Insert name"
              v-model="updateForm.name"
            />
            <Label>Description</Label>
            <Input
              id="description"
              type="text"
              class="block w-full mt-1 mb-3"
              placeholder="Insert description"
              v-model="updateForm.description"
            />
            <Button variant="destructive" @click.prevent="handleDelete">Delete</Button>
            <Button class="ml-2">Save</Button>
            <span v-if="updateSuccessMessage" class="ml-2 text-green-500">{{ updateSuccessMessage }}</span>
          </form>
        </div>
      </DialogContent>
    </Dialog>
    <Dialog v-model:open="showConfirmDeleteDialog">
      <DialogContent class="max-w-md p-6">
        <DialogTitle>Are you sure, you want to delete this image?</DialogTitle>
        <DialogDescription>Make sure it is not in use somewhere in the system, before deleting.</DialogDescription>
        <Button @click.prevent="confirmDelete" variant="destructive">Delete</Button>
        <Button @click="cancelDelete" variant="secondary">Cancel</Button>
      </DialogContent>
    </Dialog>
  </template>
  
  <script setup lang="ts">
  import {
    Dialog,
    DialogContent,
    DialogTitle,
    DialogDescription
  } from '@/components/ui/dialog';
  import { watch, ref } from 'vue';
  import { Input } from '@/components/ui/input';
  import { Button } from '@/components/ui/button';
  import { Label } from '@/components/ui/label';
  import { useForm } from '@inertiajs/vue3';
  import axios from 'axios';
  
  const props = defineProps<{
    image: {
      id: number;
      name: string | null;
      description: string | null;
      size: number;
    } | null;
    isOpen: boolean;
  }>();
  
  const updateSuccessMessage = ref('');
  const showConfirmDeleteDialog = ref(false);
  
  const emit = defineEmits(['update:isOpen', 'image-updated', 'image-deleted']);
  
  const updateOpen = (newVal: boolean) => {
    emit('update:isOpen', newVal);
  };
  
  const updateForm = useForm({
    name: '',
    description: '',
  });
  
  const handleSubmit = () => {
    if (!props.image) return;
    updateForm.put(route('images.update', { id: props.image.id }), {
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        updateSuccessMessage.value = 'image updated successfully!';
        emit('image-updated');
        setTimeout(() => {
          updateSuccessMessage.value = ''; // Clear the success message after 3 seconds
        }, 3000);
      },
      onError: () => {
        alert('Failed to update image.');
      },
    });
  };
  
  // Watch for changes in the image prop and update the form values
  watch(() => props.image, (newImage) => {
    if (newImage) {
      updateForm.name = newImage.name || '';
      updateForm.description = newImage.description || '';
    }
  });
  
  const handleDelete = () => {
    showConfirmDeleteDialog.value = true;
  };
  
  const cancelDelete = () => {
    showConfirmDeleteDialog.value = false;
  };
  
  const confirmDelete = () => {
    // showConfirmDeleteDialog.value = false;
    if (!props.image) {
      console.error('No image  to delete');
      alert('No image  to delete.');
      return;
    }
    axios.delete(route('images.destroy', { id: props.image.id }))
      .then(() => {
        if (props.image) {
          emit('image-deleted', props.image.id);
        }
        // Close both dialogs
        showConfirmDeleteDialog.value = false;
        emit('update:isOpen', false);
      })
      .catch((error) => {
        console.error('Failed to delete image:', error);
        alert('Failed to delete image.');
      });
  };
  </script>