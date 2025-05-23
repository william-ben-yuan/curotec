<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="fs-2 fw-bold">Posts</h1>
      <a 
        :href="route('posts.create')" 
        class="btn btn-primary"
      >
        New Post
      </a>
    </div>
    
    <!-- Simple Search -->
    <div class="mb-4 d-flex">
      <input
        v-model="search"
        type="text"
        placeholder="Search posts..."
        class="form-control me-2"
        style="max-width: 250px;"
        @input="handleSearch"
      />
      
      <select v-model="status" class="form-select" style="max-width: 150px;" @change="handleSearch">
        <option value="">All Status</option>
        <option value="draft">Draft</option>
        <option value="published">Published</option>
        <option value="archived">Archived</option>
      </select>
    </div>
    
    <!-- Posts Table -->
    <div class="card shadow">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <!-- ... table headers ... -->
          <tbody>
            <tr>
              <th class="p-3">Title</th>
              <th class="p-3">Author</th>
              <th class="p-3">Status</th>
              <th class="p-3">Date</th>
              <th class="p-3 text-end">Actions</th>
            </tr>
            <tr v-if="$page.props.posts.data.length === 0">
              <td colspan="5" class="p-3 text-center text-muted">No posts found</td>
            </tr>
            
            <tr v-for="post in $page.props.posts.data" :key="post.id">
              <td class="p-3">{{ post.title }}</td>
              <td class="p-3">{{ post.user?.name }}</td>
              <td class="p-3">
                <span 
                  class="badge rounded-pill"
                  :class="getStatusBadgeClass(post.status)"
                >
                  {{ post.status }}
                </span>
              </td>
              <td class="p-3">{{ formatDate(post.created_at) }}</td>
              <td class="p-3 text-end">
                <a :href="route('posts.show', post.id)" class="btn btn-sm btn-outline-info me-2">View</a>
                <a :href="route('posts.edit', post.id)" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                <button @click="confirmDelete(post.id)" class="btn btn-sm btn-outline-danger">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <!-- Simple Pagination -->
    <div class="mt-3 d-flex justify-content-between align-items-center">
      <div>
        Showing {{ $page.props.posts.from }} to {{ $page.props.posts.to }} 
        of {{ $page.props.posts.total }} results
      </div>
      <div>
        <a 
          v-if="$page.props.posts.prev_page_url" 
          :href="$page.props.posts.prev_page_url"
          class="btn btn-outline-secondary btn-sm me-2"
        >
          Previous
        </a>
        <a 
          v-if="$page.props.posts.next_page_url" 
          :href="$page.props.posts.next_page_url"
          class="btn btn-outline-secondary btn-sm"
        >
          Next
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { usePostStore } from '@/stores/postStore';
import { formatDate } from '@/utils/formatters';
import { getStatusBadgeClass } from '@/utils/constants';

const postStore = usePostStore();
const search = ref('');
const status = ref('');

// Simple search function
function handleSearch() {
  router.get(route('posts.index'), {
    search: search.value,
    status: status.value
  }, {
    preserveState: true,
    replace: true
  });
}

// Delete confirmation
function confirmDelete(id) {
  if (confirm('Are you sure you want to delete this post?')) {
    router.delete(route('posts.destroy', id));
  }
}
</script>