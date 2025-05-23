import { defineStore } from 'pinia';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export const usePostStore = defineStore('posts', () => {
  // ------ STATE ------
  const posts = ref([]);
  const currentPost = ref(null);
  const isLoading = ref(false);
  const errors = ref({});
  const filters = ref({
    search: '',
    status: '',
    per_page: 10,
  });
  
  // ------ GETTERS ------
  const getPostById = (id) => {
    return posts.value.find(post => post.id === id);
  };

  const hasErrors = () => Object.keys(errors).length > 0;

  // ------ ACTIONS ------

  /**
   * Reset errors object
   */
  const resetErrors = () => {
    Object.keys(errors).forEach(key => delete errors[key]);
  };

  /**
   * Handle common error response
   */
  const handleErrors = (validationErrors) => {
    resetErrors();
    Object.assign(errors, validationErrors);
  };

  /**
   * Set loading state
   */
  const setLoading = (state) => {
    isLoading.value = state;
  };

  /**
   * Fetch all posts with filters
   */
  const fetchPosts = () => {
    setLoading(true);
    
    router.get(route('posts.index'), filters.value, {
      preserveState: true,
      preserveScroll: true,
      onFinish: () => setLoading(false)
    });
  };

  /**
   * Fetch a single post by ID
   */
  const fetchPost = (id) => {
    setLoading(true);
    
    router.get(route('posts.show', id), {}, {
      preserveState: true,
      onSuccess: (page) => {
        currentPost.value = page.props.post;
      },
      onFinish: () => setLoading(false)
    });
  };

  /**
   * Create a new post
   */
  const createPost = (postData) => {
    setLoading(true);
    resetErrors();
    
    router.post(route('posts.store'), postData, {
      onSuccess: () => {
        setLoading(false);
        router.visit(route('posts.index'), {
          preserveScroll: true
        });
      },
      onError: handleErrors,
      onFinish: () => setLoading(false)
    });
  };

  /**
   * Update an existing post
   */
  const updatePost = (id, data) => {
    setLoading(true);
    resetErrors();
    
    router.put(route('posts.update', id), data, {
      onSuccess: () => {
        router.visit(route('posts.show', id), {
          preserveScroll: true
        });
      },
      onError: handleErrors,
      onFinish: () => setLoading(false)
    });
  };

  /**
   * Delete a post
   */
  const deletePost = (id) => {
    setLoading(true);
    
    router.delete(route('posts.destroy', id), {
      onSuccess: () => {
        // Se estamos na página de detalhes do post, redirecione para o índice
        if (window.location.pathname.includes(`/posts/${id}`)) {
          router.visit(route('posts.index'));
        }
      },
      onError: (e) => {
        alert('Error deleting post: ' + e.message);
      },
      onFinish: () => setLoading(false)
    });
  };

  /**
   * Update filters and fetch posts
   */
  const applyFilters = (newFilters) => {
    filters.value = { ...filters.value, ...newFilters };
    fetchPosts();
  };

  /**
   * Reset filters to default values
   */
  const resetFilters = () => {
    filters.value = {
      search: '',
      status: '',
      per_page: 10,
      page: 1
    };
    fetchPosts();
  };

  return {
    // State
    posts,
    currentPost,
    isLoading,
    errors,
    filters,
    
    // Getter functions
    getPostById,
    hasErrors,
    
    // Actions
    fetchPosts,
    fetchPost,
    createPost,
    updatePost,
    deletePost,
    applyFilters,
    resetFilters,
    resetErrors
  };
});