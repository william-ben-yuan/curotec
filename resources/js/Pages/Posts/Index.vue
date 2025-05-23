<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="fs-2 fw-bold">Posts</h1>
      <a :href="route('posts.create')" class="btn btn-primary">New Post</a>
    </div>
    
    <div class="mb-4 d-flex flex-wrap gap-2">
      <!-- Search input -->
      <input
        v-model="search"
        type="text"
        placeholder="Search posts..."
        class="form-control me-2"
        style="max-width: 250px;"
        @input="handleSearch"
      />

      <!-- Status filter -->
      <select v-model="status" class="form-select" style="max-width: 150px;" @change="handleSearch">
        <option value="">All Status</option>
        <option value="draft">Draft</option>
        <option value="published">Published</option>
        <option value="archived">Archived</option>
      </select>

      <!-- Author filter -->
      <select v-model="author" class="form-select" style="max-width: 200px;" @change="handleSearch">
        <option value="">All Authors</option>
        <option 
          v-for="user in users" 
          :key="user.id" 
          :value="user.id"
        >
          {{ user.name }}
        </option>
      </select>

      <!-- Date range filter -->
      <input
        v-model="dateFrom"
        type="date"
        class="form-control"
        style="max-width: 150px;"
        @change="handleSearch"
        placeholder="From"
      />
      <input
        v-model="dateTo"
        type="date"
        class="form-control"
        style="max-width: 150px;"
        @change="handleSearch"
        placeholder="To"
      />
    </div>
    
    <div class="card shadow">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <!-- Table headers with sorting -->
          <thead>
            <tr>
              <th class="p-3" @click="setSort('title')" style="cursor:pointer">
                Title
                <span v-if="sort === 'title'">
                  <i :class="direction === 'asc' ? 'bi bi-caret-up-fill' : 'bi bi-caret-down-fill'"></i>
                </span>
              </th>
              <th class="p-3" @click="setSort('user_id')" style="cursor:pointer">
                Author
                <span v-if="sort === 'user_id'">
                  <i :class="direction === 'asc' ? 'bi bi-caret-up-fill' : 'bi bi-caret-down-fill'"></i>
                </span>
              </th>
              <th class="p-3" @click="setSort('status')" style="cursor:pointer">
                Status
                <span v-if="sort === 'status'">
                  <i :class="direction === 'asc' ? 'bi bi-caret-up-fill' : 'bi bi-caret-down-fill'"></i>
                </span>
              </th>
              <th class="p-3" @click="setSort('created_at')" style="cursor:pointer">
                Date
                <span v-if="sort === 'created_at'">
                  <i :class="direction === 'asc' ? 'bi bi-caret-up-fill' : 'bi bi-caret-down-fill'"></i>
                </span>
              </th>
              <th class="p-3 text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="posts.data.length === 0">
              <td colspan="5" class="p-3 text-center text-muted">No posts found</td>
            </tr>
            <tr v-for="post in posts.data" :key="post.id">
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
    
    <!-- Pagination -->
    <div class="mt-3 d-flex justify-content-between align-items-center">
      <div>
        Showing {{ posts.from }} to {{ posts.to }} 
        of {{ posts.total }} results
      </div>
      <div>
        <a 
          v-if="posts.prev_page_url" 
          href="#"
          @click.prevent="handlePageChange(posts.prev_page_url)"
          class="btn btn-outline-secondary btn-sm me-2"
        >
          Previous
        </a>
        <a 
          v-if="posts.next_page_url" 
          href="#"
          @click.prevent="handlePageChange(posts.next_page_url)"
          class="btn btn-outline-secondary btn-sm"
        >
          Next
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { usePostStore } from '@/stores/postStore';
import { formatDate } from '@/utils/formatters';
import { getStatusBadgeClass } from '@/utils/constants';

const page = usePage();
const postStore = usePostStore();

// Props
const posts = computed(() => page.props.posts);
const users = page.props.users || [];  

// Filters
const search = ref(page.props.filters?.search || '');
const status = ref(page.props.filters?.status || '');
const author = ref(page.props.filters?.author || '');
const dateFrom = ref(page.props.filters?.dateFrom || '');
const dateTo = ref(page.props.filters?.dateTo || '');

// Sorting
const sort = ref(page.props.filters?.sort || 'created_at');
const direction = ref(page.props.filters?.direction || 'desc');

// Computed properties for sorting
function handleSearch() {
  router.get(route('posts.index'), {
    search: search.value,
    status: status.value,
    user_id: author.value,
    dateFrom: dateFrom.value,
    dateTo: dateTo.value,
    sort: sort.value,
    direction: direction.value,
  }, {
    preserveState: true, // Preserve the current state of the page
    replace: true, // Replace the current URL without adding a new entry to the history stack
    only: ['posts', 'filters'],
  });
}

function setSort(column) {
  if (sort.value === column) {
    direction.value = direction.value === 'asc' ? 'desc' : 'asc';
  } else {
    sort.value = column;
    direction.value = 'asc';
  }
  handleSearch();
}

function confirmDelete(id) {
  if (confirm('Are you sure you want to delete this post?')) {
    postStore.deletePost(id);
  }
}

// It is important to handle the page change correctly
function handlePageChange(url) {
  router.get(url, {
    search: search.value,
    status: status.value,
    author: author.value,
    dateFrom: dateFrom.value,
    dateTo: dateTo.value,
    sort: sort.value,
    direction: direction.value,
  }, {
    preserveState: true,
    replace: true,
    only: ['posts', 'filters'],
  });
}
</script>
