// Admin JavaScript Functions

// Modal Functions
function openCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
    // Reset form
    document.getElementById('createUserForm').reset();
    // Hide price field
    document.getElementById('pricePerSessionGroup').style.display = 'none';
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('createUserModal');
    if (event.target === modal) {
        closeCreateUserModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCreateUserModal();
    }
});

// Form submission and role handling
document.addEventListener('DOMContentLoaded', function() {
    const createUserForm = document.getElementById('createUserForm');
    const roleSelect = document.getElementById('role');
    const priceGroup = document.getElementById('pricePerSessionGroup');
    const priceInput = document.getElementById('price_per_session');

    // Handle role changes
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            if (this.value === 'mentor') {
                priceGroup.style.display = 'block';
                priceInput.required = true;
            } else {
                priceGroup.style.display = 'none';
                priceInput.required = false;
                priceInput.value = ''; // Clear the price when role is not mentor
            }
        });
    }

    // No need to prevent form submission - let it submit naturally
});

// Existing functions (if any)
function editUser(userId) {
    // TODO: Implement edit user functionality
    console.log('Edit user:', userId);
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        // TODO: Implement delete user functionality
        console.log('Delete user:', userId);
    }
} 