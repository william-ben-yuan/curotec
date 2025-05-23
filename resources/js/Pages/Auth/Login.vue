<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header bg-white py-3">
            <h1 class="fs-4 fw-bold text-center mb-0">Login</h1>
          </div>
          <div class="card-body p-4">
            <form @submit.prevent="submit">
              <!-- Alert for errors -->
              <div v-if="form.errors.email" class="alert alert-danger mb-4" role="alert">
                {{ form.errors.email }}
              </div>

              <!-- Email Field -->
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                  id="email"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.email }"
                  required
                  autocomplete="email"
                />
              </div>

              <!-- Password Field -->
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  v-model="form.password"
                  type="password"
                  id="password"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.password }"
                  required
                  autocomplete="current-password"
                />
                <div v-if="form.errors.password" class="invalid-feedback">
                  {{ form.errors.password }}
                </div>
              </div>

              <!-- Remember Me -->
              <div class="mb-3 form-check">
                <input
                  v-model="form.remember"
                  type="checkbox"
                  id="remember"
                  class="form-check-input"
                />
                <label for="remember" class="form-check-label">Remember me</label>
              </div>

              <!-- Actions -->
              <div class="d-grid gap-2">
                <button
                  type="submit"
                  class="btn btn-primary"
                  :disabled="form.processing"
                >
                  <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Log In
                </button>
              </div>
            </form>

            <!-- Register Link -->
            <div class="mt-4 text-center">
              <p class="mb-0">
                Don't have an account?
                <a :href="route('register')" class="text-decoration-none">Register</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

function submit() {
  form.post(route('login'), {
    onFinish: () => {
      form.reset('password');
    },
  });
}
</script>