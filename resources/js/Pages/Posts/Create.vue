<template>
  <div class="container py-4">
    <h1 class="fs-2 fw-bold mb-4">Create Post</h1>
    <PostForm :submitLabel="'Create Post'" :onSubmitForm="submit" />
  </div>
</template>

<script setup>
import PostForm from '@/components/PostForm.vue';
import { router, useForm } from '@inertiajs/vue3';

function submit(form) {
  form.post(route('posts.store'), {
    preserveScroll: true
  });
}
</script>
