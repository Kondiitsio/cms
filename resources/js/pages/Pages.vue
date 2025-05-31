<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Page } from '@/types';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Button from '@/components/ui/button/Button.vue';
import { Checkbox } from '@/components/ui/checkbox'
import { AlertCircle } from 'lucide-vue-next'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import {
    Dialog,
    DialogContent,
    DialogTitle,
    DialogDescription
  } from '@/components/ui/dialog';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pages',
        href: '/pages',
    },
];

const pages = ref<Page[]>([]);
const form = ref<Page>({ id: null, title: '', slug: '', seo: '', isFrontPage: false });
const isEditing = ref(false);
const errorMessage = ref<string | null>(null);
const showConfirmDeleteDialog = ref(false);
const pageToDelete = ref<Page | null>(null);

const fetchPages = async () => {
    try {
        const response = await axios.get('/api/pages');
        pages.value = response.data;
    } catch (error) {
        console.error('Error fetching pages:', error);
    }
};

const savePage = async () => {
    try {
        errorMessage.value = null; // Clear previous errors
        if (isEditing.value) {
            await axios.put(`/api/pages/${form.value.id}`, form.value);
        } else {
            await axios.post('/api/pages', form.value);
        }
        form.value = { id: null, title: '', slug: '', seo: '', isFrontPage: false };
        isEditing.value = false;
        fetchPages();
    } catch (error: any) {
        // Try to extract the backend error message
        errorMessage.value = error?.response?.data?.error || 'An error occurred while saving the page.';
        console.error('Error saving page:', error);
    }
};

const editPage = (page: Page) => {
    form.value = { ...page } as Page;
    form.value.isFrontPage = page.slug === null || page.slug === ''; // Set isFrontPage based on slug
    isEditing.value = true;
};

const deletePage = async (id: Page['id']) => {
    try {
        errorMessage.value = null;
        await axios.delete(`/api/pages/${id}`);
        fetchPages();
    } catch (error: any) {
        errorMessage.value = error?.response?.data?.error || 'An error occurred while deleting the page.';
        console.error('Error deleting page:', error);
    }
};

const cancel = () => {
    form.value = { id: null, title: '', slug: '', seo: '', isFrontPage: false }; // Reset form
    isEditing.value = false; // Switch back to create mode
};

const goToPageBuilder = (slug: string | null) => {
    const url = !slug || slug === '' ? '/pages/frontpage' : `/pages/${slug}`;
    window.location.href = url;
};

const openDeleteDialog = (page: Page) => {
    pageToDelete.value = page;
    showConfirmDeleteDialog.value = true;
};

const confirmDelete = async () => {
    if (pageToDelete.value?.id != null) {
        await deletePage(pageToDelete.value.id);
    }
    showConfirmDeleteDialog.value = false;
    pageToDelete.value = null;
};

const cancelDelete = () => {
    showConfirmDeleteDialog.value = false;
    pageToDelete.value = null;
};

// Automatically generate slug from title unless it's the front page
watch(
    () => form.value.title,
    (newTitle) => {
        if (!form.value.isFrontPage) {
            form.value.slug = newTitle
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-') // Replace non-alphanumeric characters with hyphens
                .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
        }
    }
);

// Watch for changes to the "isFrontPage" checkbox
watch(
    () => form.value.isFrontPage,
    (isFrontPage) => {
        if (isFrontPage) {
            // Set slug to empty for the front page
            form.value.slug = '';
        } else {
            // Regenerate slug from the title when unchecked
            form.value.slug = form.value.title
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-') // Replace non-alphanumeric characters with hyphens
                .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
        }
    }
);

onMounted(fetchPages);
</script>

<template>
    <Head title="Pages" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Alert v-if="errorMessage" variant="destructive">
                <AlertCircle class="w-4 h-4" />
                <AlertTitle>Error</AlertTitle>
                <AlertDescription>
                    {{ errorMessage }}
                </AlertDescription>
            </Alert>
            <!-- Form for creating/updating pages -->
            <form @submit.prevent="savePage" class="mb-4">
                <div class="mb-2">
                    <Label for="title" class="mb-2">Title</Label>
                    <Input
                        id="title"
                        v-model="form.title"
                        type="text"
                        required
                        placeholder="Enter page title"
                    />
                </div>
                <div class="mb-2">
                    <Label for="slug" class="mb-2">Slug</Label>
                    <Input
                        id="slug"
                        v-model="form.slug"
                        type="text"
                        readonly
                        placeholder="Slug will be generated from title if its not the front page"
                    />
                </div>
                <div class="mb-2">
                    <Label for="seo" class="mb-2">SEO</Label>
                    <Input
                        id="seo"
                        v-model="form.seo"
                        type="text"
                        placeholder="Enter SEO description"
                    />
                </div>
                <div class="mb-2">
                    <Label for="isFrontPage" class="flex items-center gap-2 my-4">
                        <Checkbox
                            id="isFrontPage"
                            type="checkbox"
                            v-model="form.isFrontPage"
                        />
                        Set as Front Page
                    </Label>
                </div>
                <div class="flex gap-2">
                    <Button
                        type="submit"
                    >
                        {{ isEditing ? 'Update' : 'Create' }} Page
                    </Button>
                    <Button
                        type="button"
                        @click="cancel"
                        variant="secondary"
                    >
                        Cancel
                    </Button>
                </div>
            </form>

            <!-- List of pages -->
            <div class="grid auto-rows-min gap-4">
                <div
                    v-for="page in pages"
                    :key="page.id ?? ''"
                    class="relative rounded-xl border border-sidebar-border/70 p-4"
                >
                    <h3 class="text-lg font-bold">{{ page.title }}</h3>
                    <p class="text-sm text-gray-600">Slug: {{ page.slug }}</p>
                    <p class="text-sm text-gray-600">SEO: {{ page.seo }}</p>
                    <div class="mt-2 flex gap-2">
                        <Button
                            @click="editPage(page)"
                            variant="secondary"
                        >
                            Edit
                        </Button>
                        <Button
                            @click="openDeleteDialog(page)"
                            variant="destructive"
                        >
                            Delete
                        </Button>
                        <Button
                            @click="goToPageBuilder(page.slug)"
                        >
                            Edit Content
                        </Button>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:open="showConfirmDeleteDialog">
        <DialogContent class="max-w-md p-6">
            <DialogTitle>Are you sure, you want to delete this page?</DialogTitle>
            <DialogDescription>Make sure there are no important data left at the page.</DialogDescription>
            <Button @click.prevent="confirmDelete" variant="destructive">Delete</Button>
            <Button @click="cancelDelete" variant="secondary">Cancel</Button>
        </DialogContent>
        </Dialog>
    </AppLayout>
</template>