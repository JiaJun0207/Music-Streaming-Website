document.getElementById('uploadForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const songTitle = document.getElementById('songTitle').value;
    const artist = document.getElementById('artist').value;
    const language = document.getElementById('language').value;
    const categories = document.getElementById('categories').value;
    const releaseDate = document.getElementById('releaseDate').value;
    const mp3Upload = document.getElementById('mp3Upload').files[0];

    if (songTitle && artist && categories && releaseDate && mp3Upload) {
        alert('Song uploaded successfully!');
        // Add your form submission logic here
    } else {
        alert('Please fill all the required fields.');
    }
});
