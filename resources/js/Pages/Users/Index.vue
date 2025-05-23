<template>
    <div class="container py-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fs-2 fw-bold">Users</h1>
      </div>
      
      <!-- Simple Search -->
      <div class="mb-4 d-flex">
        <input
          v-model="search"
          type="text"
          placeholder="Search users..."
          class="form-control me-2"
          style="max-width: 250px;"
          @input="handleSearch"
        />
      </div>
      
      <!-- Users Table -->
      <div class="card shadow">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th class="p-3">Name</th>
                <th class="p-3">Email</th>
                <th class="p-3">Posts</th>
                <th class="p-3">Joined</th>
                <th class="p-3 text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="users.data.length === 0">
                <td colspan="5" class="p-3 text-center text-muted">No users found</td>
              </tr>
              
              <tr v-for="user in users.data" :key="user.id">
                <td class="p-3">{{ user.name }}</td>
                <td class="p-3">{{ user.email }}</td>
                <td class="p-3">
                  <span class="badge bg-secondary rounded-pill">
                    {{ user.posts_count || 0 }}
                  </span>
                </td>
                <td class="p-3">{{ formatDate(user.created_at) }}</td>
                <td class="p-3 text-end">
                  <a :href="route('users.show', user.id)" class="btn btn-sm btn-outline-info">View</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Simple Pagination -->
      <div class="mt-3 d-flex justify-content-between align-items-center">
        <div>
          Showing {{ users.from }} to {{ users.to }} 
          of {{ users.total }} results
        </div>
        <div>
          <a 
            v-if="users.prev_page_url" 
            :href="users.prev_page_url"
            class="btn btn-outline-secondary btn-sm me-2"
          >
            Previous
          </a>
          <a 
            v-if="users.next_page_url" 
            :href="users.next_page_url"
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
import { formatDate } from '@/utils/formatters';

const props = defineProps({
  users: Object,
});

const search = ref('');

function handleSearch() {
  router.get(route('users.index'), {
    search: search.value
  }, {
    preserveState: true,
    replace: true
  });
}
</script>