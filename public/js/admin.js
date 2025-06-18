function editUser(id) {
    window.location.href = `/admin/users/${id}/edit`;
}

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        window.location.href = `/admin/users/${id}/delete`;
    }
} 