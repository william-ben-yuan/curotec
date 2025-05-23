<template>
    <div class="container py-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fs-2 fw-bold">User Profile</h1>
      </div>
      
      <div class="row">
        <!-- User Details Card -->
        <div class="col-md-4 mb-4">
          <div class="card shadow">
            <div class="card-body p-4">
              <div class="text-center mb-4">
                <!-- Avatar Placeholder -->
                <div class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center mb-3" 
                     style="width: 100px; height: 100px;">
                  <span class="fs-1 text-secondary">{{ user.name.charAt(0) }}</span>
                </div>
                <h2 class="fs-4 fw-bold mb-0">{{ user.name }}</h2>
                <p class="text-muted">{{ user.email }}</p>
              </div>
              
              <hr>
              
              <div class="d-flex justify-content-between mb-2">
                <span>Member since:</span>
                <span class="fw-bold">{{ formatDate(user.created_at, false) }}</span>
              </div>
              
              <div class="d-flex justify-content-between mb-2">
                <span>Total Posts:</span>
                <span class="fw-bold">{{ user.posts_count || 0 }}</span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- User Posts Card -->
        <div class="col-md-8">
          <div class="card shadow">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
              <h3 class="fs-5 fw-bold mb-0">Recent Posts</h3>
            </div>
            <div class="list-group list-group-flush">
              <div v-if="user.posts && user.posts.length === 0" class="p-4 text-center text-muted">
                This user hasn't created any posts yet.
              </div>
              
              <a v-for="post in user.posts" :key="post.id" 
                 :href="route('posts.show', post.id)"
                 class="list-group-item list-group-item-action">
                <div class="d-flex justify-content-between align-items-center">
                  <h4 class="fs-6 fw-bold mb-1">{{ post.title }}</h4>
                  <span class="badge rounded-pill"
                        :class="{
                          'text-bg-success': post.status === 'published',
                          'text-bg-warning': post.status === 'draft',
                          'text-bg-secondary': post.status === 'archived'
                        }">
                    {{ post.status }}
                  </span>
                </div>
                <p class="text-muted small mb-0">{{ formatDate(post.created_at) }}</p>
              </a>
            </div>
            
            <div v-if="user.posts && user.posts.length > 0" class="card-footer bg-white py-3">
              <a :href="`/posts?user_id=${user.id}`" class="text-decoration-none">View all posts by this user →</a>
            </div>
          </div>
        </div>
      </div>
      
      <div class="mt-3">
        <a :href="route('users.index')" class="text-decoration-none">← Back to Users</a>
      </div>
    </div>
</template>

<script setup>
import { formatDate } from '@/utils/formatters';

const props = defineProps({
  user: Object,
});
</script>