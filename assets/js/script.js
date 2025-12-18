/**
 * JavaScript for CRUD Application
 * File: assets/js/script.js
 * Handles modal interactions and chart visualization
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== Modal Handlers ====================
    
    /**
     * Populate Edit Modal with product data
     */
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get data from button attributes
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            const category = this.getAttribute('data-category');
            
            // Populate form fields
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_category').value = category;
        });
    });
    
    /**
     * Populate Delete Modal with product information
     */
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get data from button attributes
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            // Populate modal fields
            document.getElementById('delete_id').value = id;
            document.getElementById('delete_name').textContent = name;
        });
    });
    
    
    // ==================== Form Validation ====================
    
    /**
     * Client-side form validation for create form
     */
    const createForm = document.querySelector('#createModal form');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const price = parseFloat(document.getElementById('price').value);
            const category = document.getElementById('category').value.trim();
            
            let errors = [];
            
            // Validate name
            if (name === '') {
                errors.push('Product name is required.');
            } else if (name.length > 100) {
                errors.push('Product name must be less than 100 characters.');
            }
            
            // Validate price
            if (isNaN(price) || price <= 0) {
                errors.push('Price must be a positive number.');
            }
            
            // Validate category
            if (category === '') {
                errors.push('Category is required.');
            } else if (category.length > 50) {
                errors.push('Category must be less than 50 characters.');
            }
            
            // Show errors if any
            if (errors.length > 0) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
            }
        });
    }
    
    /**
     * Client-side form validation for edit form
     */
    const editForm = document.querySelector('#editModal form');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const name = document.getElementById('edit_name').value.trim();
            const price = parseFloat(document.getElementById('edit_price').value);
            const category = document.getElementById('edit_category').value.trim();
            
            let errors = [];
            
            // Validate name
            if (name === '') {
                errors.push('Product name is required.');
            } else if (name.length > 100) {
                errors.push('Product name must be less than 100 characters.');
            }
            
            // Validate price
            if (isNaN(price) || price <= 0) {
                errors.push('Price must be a positive number.');
            }
            
            // Validate category
            if (category === '') {
                errors.push('Category is required.');
            } else if (category.length > 50) {
                errors.push('Category must be less than 50 characters.');
            }
            
            // Show errors if any
            if (errors.length > 0) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errors.join('\n'));
            }
        });
    }
    
    
    // ==================== Chart.js Visualization ====================
    
    /**
     * Initialize and render the category statistics chart
     */
    function initializeCategoryChart() {
        const chartCanvas = document.getElementById('categoryChart');
        
        if (!chartCanvas) {
            console.warn('Chart canvas not found');
            return;
        }
        
        // Get data from PHP (embedded in script tag)
        const categoryDataElement = document.getElementById('categoryData');
        let categoryData = [];
        
        if (categoryDataElement) {
            try {
                categoryData = JSON.parse(categoryDataElement.textContent);
            } catch (e) {
                console.error('Error parsing category data:', e);
                return;
            }
        }
        
        // Prepare chart data
        const labels = categoryData.map(item => item.category);
        const data = categoryData.map(item => parseInt(item.count));
        
        // Color palette
        const colors = [
            'rgba(102, 126, 234, 0.8)',
            'rgba(118, 75, 162, 0.8)',
            'rgba(237, 100, 166, 0.8)',
            'rgba(255, 154, 158, 0.8)',
            'rgba(250, 208, 196, 0.8)',
            'rgba(165, 177, 194, 0.8)',
            'rgba(52, 152, 219, 0.8)',
            'rgba(46, 204, 113, 0.8)'
        ];
        
        const borderColors = [
            'rgba(102, 126, 234, 1)',
            'rgba(118, 75, 162, 1)',
            'rgba(237, 100, 166, 1)',
            'rgba(255, 154, 158, 1)',
            'rgba(250, 208, 196, 1)',
            'rgba(165, 177, 194, 1)',
            'rgba(52, 152, 219, 1)',
            'rgba(46, 204, 113, 1)'
        ];
        
        // Create chart
        const ctx = chartCanvas.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Number of Products',
                    data: data,
                    backgroundColor: colors.slice(0, labels.length),
                    borderColor: borderColors.slice(0, labels.length),
                    borderWidth: 2,
                    borderRadius: 5,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Products: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
    
    // Initialize chart
    initializeCategoryChart();
    
    
    // ==================== Auto-dismiss Alerts ====================
    
    /**
     * Auto-dismiss alert messages after 5 seconds
     */
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    
    // ==================== Smooth Scroll ====================
    
    /**
     * Smooth scroll to top when page loads with hash
     */
    if (window.location.hash) {
        setTimeout(() => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }, 100);
    }
    
    
    // ==================== Format Price on Input ====================
    
    /**
     * Format price input fields to 2 decimal places
     */
    const priceInputs = document.querySelectorAll('input[name="price"]');
    priceInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
            }
        });
    });
    
    
    // ==================== Clear Form on Modal Close ====================
    
    /**
     * Clear create form when modal is closed
     */
    const createModal = document.getElementById('createModal');
    if (createModal) {
        createModal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
            }
        });
    }
    
    
    // ==================== Keyboard Shortcuts ====================
    
    /**
     * Add keyboard shortcuts for quick actions
     * Ctrl/Cmd + N: Open create modal
     */
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + N: Open create modal
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            const createModalBtn = document.querySelector('[data-bs-target="#createModal"]');
            if (createModalBtn) {
                createModalBtn.click();
            }
        }
    });
    
    
    // ==================== Console Log ====================
    
    console.log('%c CRUD Application Loaded Successfully! ', 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px; font-size: 14px; font-weight: bold;');
    console.log('Keyboard Shortcuts:');
    console.log('- Ctrl/Cmd + N: Add new product');
    
});

/**
 * Utility function to format currency
 */
function formatCurrency(amount) {
    return '$' + parseFloat(amount).toFixed(2);
}

/**
 * Utility function to validate email (if needed for future features)
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
