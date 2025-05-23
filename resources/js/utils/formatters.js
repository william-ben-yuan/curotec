/**
 * Format a date string with localized formatting
 * 
 * @param {String} dateString - ISO date string to format
 * @param {Boolean} includeTime - Whether to include time in the format
 * @return {String} Formatted date string
 */
export function formatDate(dateString, includeTime = true) {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  const options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  };
  
  if (includeTime) {
    options.hour = '2-digit';
    options.minute = '2-digit';
  }
  
  return new Intl.DateTimeFormat('en-US', options).format(date);
}

/**
 * Format currency values
 * 
 * @param {Number} value - The value to format as currency
 * @param {String} currency - Currency code (default: USD)
 * @return {String} Formatted currency string
 */
export function formatCurrency(value, currency = 'USD') {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: currency
  }).format(value);
}

/**
 * Truncate text to a specific length
 * 
 * @param {String} text - Text to truncate
 * @param {Number} length - Maximum length
 * @return {String} Truncated text with ellipsis if needed
 */
export function truncateText(text, length = 100) {
  if (!text) return '';
  if (text.length <= length) return text;
  
  return text.substring(0, length) + '...';
}