<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
      <div class="container">
        <a class="navbar-brand fw-bold" href="/">{{ $page.props.app.name }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a :href="route('posts.index')" class="nav-link">Posts</a>
            </li>
            <li v-if="$page.props.auth.user" class="nav-item">
              <a :href="route('users.index')" class="nav-link">Users</a>
            </li>
          </ul>
          
          <ul class="navbar-nav">
            <template v-if="$page.props.auth.user">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                   data-bs-toggle="dropdown" aria-expanded="false">
                  {{ $page.props.auth.user.name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <li>
                    <form @submit.prevent="logout">
                      <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                  </li>
                </ul>
              </li>
            </template>
            <template v-else>
              <li class="nav-item">
                <a :href="route('login')" class="nav-link">Login</a>
              </li>
              <li class="nav-item">
                <a :href="route('register')" class="nav-link">Register</a>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </nav>

    <main>
      <slot />
    </main>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

function logout() {
  router.post(route('logout'));
}
</script>