<template>
  <div class="container py-4">
    <h1 class="fs-2 fw-bold mb-4">Edit Post</h1>
    
    <div class="card shadow">
      <div class="card-body p-4">
        <form @submit.prevent="submit">
          <!-- Title Field -->
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input 
              v-model="form.title" 
              type="text" 
              id="title"
              class="form-control"
              :class="{ 'is-invalid': errors.title }"
            />
            <div v-if="errors.title" class="invalid-feedback">{{ errors.title }}</div>
          </div>
          
          <!-- Content Field -->
          <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea 
              v-model="form.content" 
              id="content"
              rows="5" 
              class="form-control"
              :class="{ 'is-invalid': errors.content }"
            ></textarea>
            <div v-if="errors.content" class="invalid-feedback">{{ errors.content }}</div>
          </div>
          
          <!-- Status Field -->
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select 
              v-model="form.status" 
              id="status"
              class="form-select"
              :class="{ 'is-invalid': errors.status }"
            >
              <option v-for="status in POST_STATUSES" :key="status.value" :value="status.value">
                {{ status.label }}
              </option>
            </select>
            <div v-if="errors.status" class="invalid-feedback">{{ errors.status }}</div>
          </div>
          
          <!-- Actions -->
          <div class="d-flex justify-content-end">
            <a :href="route('posts.index')" class="btn btn-light me-2">Cancel</a>
            <button 
              type="submit" 
              class="btn btn-primary"
              :disabled="postStore.isLoading"
            >
              <span v-if="postStore.isLoading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              {{ postStore.isLoading ? 'Saving...' : 'Save Post' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePostStore } from '@/stores/postStore';
import { POST_STATUSES } from '@/utils/constants';

// Get props
const props = defineProps({
  post: Object,
});

// Initialize store
const postStore = usePostStore();
const errors = postStore.errors;

// Create form
const form = ref({
  title: props.post.title,
  content: props.post.content,
  status: props.post.status,
});

// Submit form
function submit() {
  postStore.updatePost(props.post.id, form.value);
}
</script>