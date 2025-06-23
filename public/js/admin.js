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
    // Hide specializations field
    document.getElementById('specializationsGroup').style.display = 'none';
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
    // Hide specializations field
    const editSpecializationsGroup = document.getElementById('editSpecializationsGroup');
    if (editSpecializationsGroup) {
        editSpecializationsGroup.style.display = 'none';
    }
}

// Edit user function
function editUser(userId, firstName, lastName, email, role, biography, price, specializations = []) {
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
    
    // Handle specializations field visibility for mentor role
    const editSpecializationsGroup = document.getElementById('editSpecializationsGroup');
    const editSpecializationsSelect = document.getElementById('edit_specializations');
    if (editSpecializationsGroup) {
        if (role === 'mentor') {
            editSpecializationsGroup.style.display = 'block';
            // Set selected specializations
            if (editSpecializationsSelect && specializations.length > 0) {
                Array.from(editSpecializationsSelect.options).forEach(option => {
                    option.selected = specializations.includes(parseInt(option.value));
                });
            }
        } else {
            editSpecializationsGroup.style.display = 'none';
            // Clear selections
            if (editSpecializationsSelect) {
                Array.from(editSpecializationsSelect.options).forEach(option => {
                    option.selected = false;
                });
            }
        }
    }
    
    // Open the edit modal
    openEditUserModal();
}

// Edit user from data attributes
function editUserFromData(button) {
    const userId = parseInt(button.getAttribute('data-user-id'));
    const firstName = button.getAttribute('data-first-name');
    const lastName = button.getAttribute('data-last-name');
    const email = button.getAttribute('data-email');
    const role = button.getAttribute('data-role');
    const biography = button.getAttribute('data-biography');
    const price = button.getAttribute('data-price');
    const specializationsJson = button.getAttribute('data-specializations');
    
    let specializations = [];
    try {
        specializations = JSON.parse(specializationsJson);
    } catch (e) {
        console.error('Error parsing specializations:', e);
        specializations = [];
    }
    
    editUser(userId, firstName, lastName, email, role, biography, price, specializations);
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
    const specializationsGroup = document.getElementById('specializationsGroup');
    const editSpecializationsGroup = document.getElementById('editSpecializationsGroup');

    // Handle role changes for create form
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            if (this.value === 'mentor') {
                priceGroup.style.display = 'block';
                priceInput.required = true;
                specializationsGroup.style.display = 'block';
            } else {
                priceGroup.style.display = 'none';
                priceInput.required = false;
                priceInput.value = ''; // Clear the price when role is not mentor
                specializationsGroup.style.display = 'none';
            }
        });
    }

    // Handle role changes for edit form
    if (editRoleSelect) {
        editRoleSelect.addEventListener('change', function() {
            if (this.value === 'mentor') {
                editPriceGroup.style.display = 'block';
                editPriceInput.required = true;
                if (editSpecializationsGroup) {
                    editSpecializationsGroup.style.display = 'block';
                }
            } else {
                editPriceGroup.style.display = 'none';
                editPriceInput.required = false;
                editPriceInput.value = ''; // Clear the price when role is not mentor
                if (editSpecializationsGroup) {
                    editSpecializationsGroup.style.display = 'none';
                }
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