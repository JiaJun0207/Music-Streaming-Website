document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/artists')
        .then(response => response.json())
        .then(data => {
            const artistList = document.getElementById('artistList');
            artistList.innerHTML = '';
            data.forEach(artist => {
                artistList.innerHTML += `
                    <tr>
                        <td>${artist.id}</td>
                        <td>${artist.title}</td>
                        <td><img src="${artist.image}" alt="${artist.title}" class="artist-image"></td>
                        <td>${artist.category}</td>
                        <td>${artist.artist}</td>
                        <td>${artist.comment}</td>
                        <td class="action-buttons">
                            <button class="edit" onclick="editArtist(${artist.id})">✏️</button>
                            <button class="delete" onclick="deleteArtist(${artist.id})">🗑️</button>
                        </td>
                    </tr>
                `;
            });
        });
});

function editArtist(id) {
    // Logic to edit a artist
    alert(`Edit artist with ID: ${id}`);
}

function deleteArtist(id) {
    fetch(`/api/artists/${id}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (response.ok) {
            alert('Artist deleted successfully!');
            window.location.reload();
        } else {
            alert('Failed to delete artist.');
        }
    });
}

document.getElementById('addNewBtn').addEventListener('click', function() {
    window.location.href = 'upload_artist.html';
});
