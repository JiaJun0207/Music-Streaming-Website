document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/user')
        .then(response => response.json())
        .then(data => {
            const userList = document.getElementById('userList');
            userList.innerHTML = '';
            data.forEach(user => {
                userList.innerHTML += `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.username}</td>
                        <td><img src="${user.image}" alt="${user.username}" class="user-image"></td>
                        <td>${user.email}</td>
                        <td>${user.IkunCoin}</td>
                        <td>${user.phonenumber}</td>
                        <td class="action-buttons">
                            <button class="edit" onclick="editUser(${user.id})">✏️</button>
                            <button class="delete" onclick="deleteUser(${user.id})">🗑️</button>
                        </td>
                    </tr>
                `;
            });
        });
});

function editUser(id) {
    // Logic to edit a user
    alert(`Edit user with ID: ${id}`);
}

function deleteUser(id) {
    fetch(`/api/users/${id}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (response.ok) {
            alert('User deleted successfully!');
            window.location.reload();
        } else {
            alert('Failed to delete user.');
        }
    });
}

document.getElementById('addNewBtn').addEventListener('click', function() {
    window.location.href = 'upload_user.html';
});
