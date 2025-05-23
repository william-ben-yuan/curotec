import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import PostService from '@/services/PostService';

/**
 * Store for managing posts state
 * Implements the separation of concerns pattern:
 * - Store: manages state and coordinates actions
 * - Service: encapsulates API communication
 */
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
    page: 1
  });
  
  // ------ GETTERS (COMPUTED) ------
  const getPostById = computed(() => {
    return (id) => posts.value.find(post => post.id === id);
  });

  const hasErrors = computed(() => Object.keys(errors.value).length > 0);
  
  const postsByStatus = computed(() => {
    const grouped = {
      published: [],
      draft: [],
      archived: []
    };
    
    posts.value.forEach(post => {
      if (grouped[post.status]) {
        grouped[post.status].push(post);
      }
    });
    
    return grouped;
  });

  // ------ ACTIONS ------

  /**
   * Reset errors object
   */
  const resetErrors = () => {
    errors.value = {};
  };

  /**
   * Handle common error response
   */
  const handleErrors = (validationErrors) => {
    resetErrors();
    errors.value = validationErrors;
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
  const fetchPosts = async () => {
    try {
      setLoading(true);
      await PostService.getPosts(filters.value);
    } catch (error) {
      console.error('Error fetching posts:', error);
    } finally {
      setLoading(false);
    }
  };

  /**
   * Fetch a single post by ID
   */
  const fetchPost = async (id) => {
    try {
      setLoading(true);
      await PostService.getPost(id)
        .then(page => {
          if (page && page.props && page.props.post) {
            currentPost.value = page.props.post;
          }
        });
    } catch (error) {
      console.error(`Error fetching post ${id}:`, error);
    } finally {
      setLoading(false);
    }
  };

  /**
   * Create a new post with optimistic update
   */
  const createPost = async (postData) => {
    try {
      setLoading(true);
      resetErrors();
      
      await PostService.createPost(postData)
        .then(() => {
          router.visit(route('posts.index'), { 
            preserveScroll: true 
          });
        })
        .catch(error => {
          if (error.response && error.response.data && error.response.data.errors) {
            handleErrors(error.response.data.errors);
          } else {
            console.error('Error creating post:', error);
          }
        });
    } finally {
      setLoading(false);
    }
  };

  /**
   * Update an existing post
   */
  const updatePost = async (id, data) => {
    try {
      setLoading(true);
      resetErrors();
      
      await PostService.updatePost(id, data)
        .then(() => {
          router.visit(route('posts.show', id), { 
            preserveScroll: true 
          });
        })
        .catch(error => {
          if (error.response && error.response.data && error.response.data.errors) {
            handleErrors(error.response.data.errors);
          } else {
            console.error(`Error updating post ${id}:`, error);
          }
        });
    } finally {
      setLoading(false);
    }
  };

  /**
   * Delete a post with optimistic update
   */
  const deletePost = async (id) => {
    try {
      setLoading(true);
      
      // Backup for rollback if needed
      const postIndex = posts.value.findIndex(post => post.id === id);
      const postBackup = postIndex !== -1 ? {...posts.value[postIndex]} : null;
      
      // Optimistic update: remove from local array immediately
      if (postIndex !== -1) {
        posts.value.splice(postIndex, 1);
      }
      
      await PostService.deletePost(id)
        .then(() => {
          // If we're on the post detail page, redirect to index
          if (window.location.pathname.includes(`/posts/${id}`)) {
            router.visit(route('posts.index'));
          }
        })
        .catch(error => {
          // Rollback in case of error
          if (postBackup && postIndex !== -1) {
            posts.value.splice(postIndex, 0, postBackup);
          }
          
          console.error(`Error deleting post ${id}:`, error);
          alert('Error deleting post: ' + (error.message || 'Unknown error'));
        });
    } finally {
      setLoading(false);
    }
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
    
    // Getters
    getPostById,
    hasErrors,
    postsByStatus,
    
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