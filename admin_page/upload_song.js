document.getElementById('uploadForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch('your_backend_endpoint', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert('Song uploaded successfully!');
        window.location.href = 'admin.html';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to upload song.');
    });
});
