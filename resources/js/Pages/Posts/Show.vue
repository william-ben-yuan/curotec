<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="fs-2 fw-bold">{{ post.title }}</h1>
      <div>
        <a :href="route('posts.edit', post.id)" class="btn btn-primary me-2">Edit</a>
        <button @click="confirmDelete" class="btn btn-danger">Delete</button>
      </div>
    </div>
    
    <div class="card shadow p-4">
      <!-- Post Header -->
      <div class="d-flex justify-content-between border-bottom pb-3 mb-4">
        <div>
          <span 
            class="badge rounded-pill"
            :class="getStatusBadgeClass(post.status)"
          >
            {{ post.status }}
          </span>
          <span class="ms-2 text-secondary">by {{ post.user?.name }}</span>
        </div>
        <div class="text-secondary small">
          {{ formatDate(post.created_at) }}
        </div>
      </div>
      
      <!-- Post Content -->
      <div class="mb-0">
        {{ post.content }}
      </div>
    </div>
    
    <div class="mt-3">
      <a :href="route('posts.index')" class="text-decoration-none">← Back to Posts</a>
    </div>
  </div>
</template>

<script setup>
import { usePostStore } from '@/stores/postStore';
import { router } from '@inertiajs/vue3';
import { formatDate } from '@/utils/formatters';
import { getStatusBadgeClass } from '@/utils/constants';

const props = defineProps({
  post: Object,
});

const postStore = usePostStore();

// Delete confirmation
function confirmDelete() {
  if (confirm('Are you sure you want to delete this post?')) {
    postStore.deletePost(props.post.id);
  }
}
</script>