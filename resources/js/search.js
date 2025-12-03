// Debounced search functionality
export function initSearch() {
    const searchInputs = document.querySelectorAll('[data-search]');

    searchInputs.forEach(input => {
        let timeout;

        input.addEventListener('input', function () {
            clearTimeout(timeout);

            timeout = setTimeout(() => {
                const form = this.closest('form');
                if (form) {
                    form.submit();
                }
            }, 300); // 300ms debounce
        });
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initSearch);
