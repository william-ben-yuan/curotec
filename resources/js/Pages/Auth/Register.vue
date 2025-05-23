<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header bg-white py-3">
            <h1 class="fs-4 fw-bold text-center mb-0">Register</h1>
          </div>
          <div class="card-body p-4">
            <form @submit.prevent="submit">
              <!-- Name Field -->
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  id="name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.name }"
                  required
                  autocomplete="name"
                />
                <div v-if="form.errors.name" class="invalid-feedback">
                  {{ form.errors.name }}
                </div>
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
                <div v-if="form.errors.email" class="invalid-feedback">
                  {{ form.errors.email }}
                </div>
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
                  autocomplete="new-password"
                />
                <div v-if="form.errors.password" class="invalid-feedback">
                  {{ form.errors.password }}
                </div>
              </div>

              <!-- Password Confirmation Field -->
              <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  id="password_confirmation"
                  class="form-control"
                  required
                  autocomplete="new-password"
                />
              </div>

              <!-- Actions -->
              <div class="d-grid gap-2">
                <button
                  type="submit"
                  class="btn btn-primary"
                  :disabled="form.processing"
                >
                  <span v-if="form.processing" class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Register
                </button>
              </div>
            </form>

            <!-- Login Link -->
            <div class="mt-4 text-center">
              <p class="mb-0">
                Already have an account?
                <a :href="route('login')" class="text-decoration-none">Log in</a>
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
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

function submit() {
  form.post(route('register'));
}
</script>