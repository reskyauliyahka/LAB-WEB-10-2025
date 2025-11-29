// Project Management System JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Status update for team members
    initializeStatusUpdates();
    
    // Auto-hide alerts after 5 seconds
    autoHideAlerts();
    
    // Initialize charts if on dashboard
    if (document.getElementById('dashboardStats')) {
        initializeDashboardCharts();
    }
});

// Filter tasks by status
function filterTasks() {
    const statusFilter = document.getElementById('statusFilter').value;
    const taskRows = document.querySelectorAll('.task-row');
    
    taskRows.forEach(row => {
        if (statusFilter === '' || row.dataset.status === statusFilter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Initialize status update functionality
function initializeStatusUpdates() {
    const statusSelects = document.querySelectorAll('.status-select');
    
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const taskId = this.dataset.taskId;
            const newStatus = this.value;
            
            updateTaskStatus(taskId, newStatus);
        });
    });
}

// Update task status via AJAX
function updateTaskStatus(taskId, newStatus) {
    // Show loading state
    const originalText = event.target.options[event.target.selectedIndex].text;
    event.target.disabled = true;
    
    fetch(`update_status.php?id=${taskId}&status=${newStatus}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Status berhasil diupdate!', 'success');
                
                // Update badge color
                const badge = event.target.closest('tr').querySelector('.badge');
                badge.textContent = originalText;
                badge.className = 'badge ' + getStatusBadgeClass(newStatus);
                
                // Update data attribute
                event.target.closest('tr').dataset.status = newStatus;
            } else {
                showNotification('Gagal mengupdate status!', 'error');
                // Revert selection
                event.target.value = event.target.dataset.originalValue;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan!', 'error');
            event.target.value = event.target.dataset.originalValue;
        })
        .finally(() => {
            event.target.disabled = false;
        });
}

// Get badge class based on status
function getStatusBadgeClass(status) {
    switch (status) {
        case 'selesai': return 'badge-success';
        case 'proses': return 'badge-warning';
        case 'belum': return 'badge-danger';
        default: return 'badge-secondary';
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notif => notif.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `custom-notification alert alert-${type} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Auto hide alerts
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.custom-notification)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

// Form validation enhancement
function enhanceFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Add error message if not exists
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        errorDiv.textContent = 'Field ini wajib diisi.';
                        field.parentNode.appendChild(errorDiv);
                    }
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Harap isi semua field yang wajib!', 'warning');
            }
        });
    });
}

// Initialize dashboard charts
function initializeDashboardCharts() {
    // Simple progress bars for task status
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.width = width;
            bar.style.transition = 'width 1s ease-in-out';
        }, 100);
    });
}

// Search functionality for tables
function initializeTableSearch() {
    const searchInputs = document.querySelectorAll('.table-search');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.card').querySelector('table');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
}

// Datepicker enhancement
function initializeDatePickers() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    
    dateInputs.forEach(input => {
        // Set min date to today for start dates
        if (input.name.includes('mulai')) {
            input.min = new Date().toISOString().split('T')[0];
        }
        
        // Set min date for end dates based on start date
        if (input.name.includes('selesai')) {
            const startDateInput = input.closest('form').querySelector('input[name*="mulai"]');
            if (startDateInput) {
                startDateInput.addEventListener('change', function() {
                    input.min = this.value;
                });
            }
        }
    });
}

// Export functionality
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    const rows = table.querySelectorAll('tr');
    const csv = [];
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            // Clean text and add to row
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
            data = data.replace(/"/g, '""');
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(','));
    }
    
    // Download CSV file
    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Initialize all functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    enhanceFormValidation();
    initializeTableSearch();
    initializeDatePickers();
    
    // Add search box to tables if not exists
    const tables = document.querySelectorAll('.table-responsive');
    tables.forEach(tableContainer => {
        if (!tableContainer.previousElementSibling || !tableContainer.previousElementSibling.classList.contains('table-search-container')) {
            const searchContainer = document.createElement('div');
            searchContainer.className = 'table-search-container mb-3';
            searchContainer.innerHTML = `
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control table-search" placeholder="Cari...">
                </div>
            `;
            tableContainer.parentNode.insertBefore(searchContainer, tableContainer);
        }
    });
});

// Responsive sidebar toggle for mobile
function initializeMobileMenu() {
    const sidebarToggles = document.querySelectorAll('[data-bs-toggle="sidebar"]');
    
    sidebarToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        });
    });
}

// Real-time updates (simulated)
function initializeRealTimeUpdates() {
    // Simulate real-time updates every 30 seconds
    setInterval(() => {
        if (document.querySelector('.auto-update')) {
            // You can implement actual real-time updates here
            console.log('Checking for updates...');
        }
    }, 30000);
}