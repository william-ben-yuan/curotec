<template>
  <div class="container py-4">
    <div class="card shadow">
      <div class="card-body p-4">
        <form @submit.prevent="onSubmit">
          <!-- Title -->
          <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input 
              v-model="form.title"
              type="text"
              id="title"
              class="form-control"
              :class="{ 'is-invalid': form.errors.title }"
            />
            <div v-if="form.errors.title" class="invalid-feedback">{{ form.errors.title }}</div>
          </div>

          <!-- Content -->
          <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea 
              v-model="form.content"
              id="content"
              rows="5"
              class="form-control"
              :class="{ 'is-invalid': form.errors.content }"
            ></textarea>
            <div v-if="form.errors.content" class="invalid-feedback">{{ form.errors.content }}</div>
          </div>

          <!-- Status -->
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select 
              v-model="form.status"
              id="status"
              class="form-select"
              :class="{ 'is-invalid': form.errors.status }"
            >
              <option v-for="status in POST_STATUSES" :key="status.value" :value="status.value">
                {{ status.label }}
              </option>
            </select>
            <div v-if="form.errors.status" class="invalid-feedback">{{ form.errors.status }}</div>
          </div>

          <!-- Actions -->
          <div class="d-flex justify-content-end">
            <a :href="route('posts.index')" class="btn btn-light me-2">Cancel</a>
            <button type="submit" class="btn btn-primary" :disabled="form.processing">
              <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
              {{ submitLabel }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { POST_STATUSES } from '@/utils/constants';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  post: {
    type: Object,
    default: () => ({
      title: '',
      content: '',
      status: 'draft'
    })
  },
  submitLabel: {
    type: String,
    default: 'Save'
  },
  onSubmitForm: {
    type: Function,
    required: true
  }
});

const form = useForm({
  title: props.post.title,
  content: props.post.content,
  status: props.post.status
});

function onSubmit() {
  form.clearErrors();

  // Fields validation
  if (!form.title) {
    form.setError('title', 'The title field is required.');
  }

  if (!form.content) {
    form.setError('content', 'The content field is required.');
  }

  if (Object.keys(form.errors).length > 0) {
    return;
  }

  props.onSubmitForm(form);
}
</script>
