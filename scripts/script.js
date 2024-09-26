// assets/js/script.js

document.addEventListener('DOMContentLoaded', () => {
    const mediaDisplay = document.getElementById('media-display');
    const uploadBtn = document.getElementById('upload-btn');
    const fileInput = document.getElementById('file-input');

    const basePath = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/'); //idk i just asked chatgpt
    const apiPath = `${basePath}/api/recive.php`;
    const mediaPath = `${basePath}/medias/`;

    function fetchRandomMedia() {
        fetch(apiPath)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const mediaUrl = mediaPath + data.file;
                    mediaDisplay.innerHTML = `
                        ${data.file.endsWith('.mp4') ? 
                            `<video controls><source src="${mediaUrl}" type="video/mp4">Your browser does not support the video tag.</video>` : 
                            `<img src="${mediaUrl}" alt="Random Media">`}
                    `;
                }
            })
            .catch(error => console.error('Stupid error :', error));
    }

    // CLICKCLICKCLICKCLICK
    uploadBtn.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file) {
            const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.mp4|\.webm)$/i;

            if (!allowedExtensions.exec(file.name)) {
                alert('Please select a valid file.');
                fileInput.value = '';
                return;
            }

            const formData = new FormData();
            formData.append('fileToUpload', file);

            fetch(`${basePath}/api/upload.php`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                fetchRandomMedia();
            })
            .catch(error => console.error('Error (we don\'t care abt it) :', error));
        }
    });

    fetchRandomMedia();
});
