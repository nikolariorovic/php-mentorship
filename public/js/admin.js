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

// Edit Modal Functions
function openEditUserModal() {
    document.getElementById('editUserModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeEditUserModal() {
    document.getElementById('editUserModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
    // Reset form
    document.getElementById('editUserForm').reset();
    // Hide price field
    document.getElementById('editPricePerSessionGroup').style.display = 'none';
}

// Edit user function
function editUser(userId, firstName, lastName, email, role, biography, price) {
    // Populate the edit form with user data
    document.getElementById('edit_first_name').value = firstName;
    document.getElementById('edit_last_name').value = lastName;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_biography').value = biography;
    
    // Handle price field visibility and value
    const editPriceGroup = document.getElementById('editPricePerSessionGroup');
    const editPriceInput = document.getElementById('edit_price_per_session');
    
    if (role === 'mentor') {
        editPriceGroup.style.display = 'block';
        editPriceInput.required = true;
        editPriceInput.value = price;
    } else {
        editPriceGroup.style.display = 'none';
        editPriceInput.required = false;
        editPriceInput.value = '';
    }
    
    // Open the edit modal
    openEditUserModal();
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const createModal = document.getElementById('createUserModal');
    const editModal = document.getElementById('editUserModal');
    
    if (event.target === createModal) {
        closeCreateUserModal();
    }
    
    if (event.target === editModal) {
        closeEditUserModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCreateUserModal();
        closeEditUserModal();
    }
});

// Form submission and role handling
document.addEventListener('DOMContentLoaded', function() {
    const createUserForm = document.getElementById('createUserForm');
    const editUserForm = document.getElementById('editUserForm');
    const roleSelect = document.getElementById('role');
    const editRoleSelect = document.getElementById('edit_role');
    const priceGroup = document.getElementById('pricePerSessionGroup');
    const editPriceGroup = document.getElementById('editPricePerSessionGroup');
    const priceInput = document.getElementById('price_per_session');
    const editPriceInput = document.getElementById('edit_price_per_session');

    // Handle role changes for create form
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

    // Handle role changes for edit form
    if (editRoleSelect) {
        editRoleSelect.addEventListener('change', function() {
            if (this.value === 'mentor') {
                editPriceGroup.style.display = 'block';
                editPriceInput.required = true;
            } else {
                editPriceGroup.style.display = 'none';
                editPriceInput.required = false;
                editPriceInput.value = ''; // Clear the price when role is not mentor
            }
        });
    }

    // No need to prevent form submission - let it submit naturally
});

// Existing functions (if any)
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        // TODO: Implement delete user functionality
        console.log('Delete user:', userId);
    }
} 