/**
 * Post status options
 */
export const POST_STATUSES = [
  { value: 'draft', label: 'Draft', badgeClass: 'text-bg-warning' },
  { value: 'published', label: 'Published', badgeClass: 'text-bg-success' },
  { value: 'archived', label: 'Archived', badgeClass: 'text-bg-secondary' }
];

/**
 * Get all post statuses
 */
export function getPostStatuses() {
  return POST_STATUSES;
}

/**
 * Get post status object by value
 * @param {string} value - Status value to find
 * @returns {Object|undefined} The status object or undefined if not found
 */
export function getStatusByValue(value) {
  return POST_STATUSES.find(status => status.value === value);
}

/**
 * Get badge class for a status
 * @param {string} value - Status value
 * @returns {string} CSS class for the badge
 */
export function getStatusBadgeClass(value) {
  const status = getStatusByValue(value);
  return status ? status.badgeClass : 'text-bg-secondary';
}