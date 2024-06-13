document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/songs')
        .then(response => response.json())
        .then(data => {
            const songList = document.getElementById('songList');
            songList.innerHTML = '';
            data.forEach(song => {
                songList.innerHTML += `
                    <tr>
                        <td>${song.id}</td>
                        <td>${song.title}</td>
                        <td><img src="${song.image}" alt="${song.title}" class="song-image"></td>
                        <td>${song.category}</td>
                        <td>${song.artist}</td>
                        <td>${song.comment}</td>
                        <td class="action-buttons">
                            <button class="edit" onclick="editSong(${song.id})">✏️</button>
                            <button class="delete" onclick="deleteSong(${song.id})">🗑️</button>
                        </td>
                    </tr>
                `;
            });
        });
});

function editSong(id) {
    // Logic to edit a song
    alert(`Edit song with ID: ${id}`);
}

function deleteSong(id) {
    fetch(`/api/songs/${id}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (response.ok) {
            alert('Song deleted successfully!');
            window.location.reload();
        } else {
            alert('Failed to delete song.');
        }
    });
}

document.getElementById('addNewBtn').addEventListener('click', function() {
    window.location.href = 'upload.html';
});
