import { router } from '@inertiajs/vue3';

/**
 * Service for post-related operations
 * Isolates the backend communication logic
 */
export default class PostService {
  /**
   * Get list of posts with filters
   * @param {Object} filters - Filters to be applied
   * @returns {Promise} - Promise with response
   */
  static getPosts(filters = {}) {
    return router.get(route('posts.index'), filters, {
      preserveState: true,
      preserveScroll: true,
      only: ['posts']
    });
  }

  /**
   * Get a specific post by ID
   * @param {number|string} id - Post ID
   * @returns {Promise} - Promise with response
   */
  static getPost(id) {
    return router.get(route('posts.show', id), {}, {
      preserveState: true,
      only: ['post']
    });
  }

  /**
   * Create a new post
   * @param {Object} postData - Post data
   * @returns {Promise} - Promise with response
   */
  static createPost(postData) {
    return router.post(route('posts.store'), postData);
  }

  /**
   * Update an existing post
   * @param {number|string} id - Post ID
   * @param {Object} postData - Updated post data
   * @returns {Promise} - Promise with response
   */
  static updatePost(id, postData) {
    return router.put(route('posts.update', id), postData);
  }

  /**
   * Delete a post
   * @param {number|string} id - ID of the post to be removed
   * @returns {Promise} - Promise with response
   */
  static deletePost(id) {
    return router.delete(route('posts.destroy', id));
  }
}