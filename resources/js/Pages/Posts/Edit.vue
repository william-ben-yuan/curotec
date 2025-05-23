<template>
  <div class="container py-4">
    <h1 class="fs-2 fw-bold mb-4">Edit Post</h1>
    <!-- Include the PostForm component and pass the post prop -->
    <PostForm :post="post" :submitLabel="'Update Post'" :onSubmitForm="submit" />
  </div>
</template>

<script setup>
import PostForm from '@/components/PostForm.vue';

const props = defineProps({
  post: Object
});

function submit(form) {
  form.put(route('posts.update', props.post.id), {
    preserveScroll: true
  });
}
</script>
