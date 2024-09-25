function removeMedia() {
    const mediaPath = localStorage.getItem("mediaPath");
    const adminPassword = prompt("Enter the admin password to remove this media:");
    fetch("api/remove.php", {
        method: 'POST',
        body: JSON.stringify({ mediaPath: mediaPath, adminPassword: adminPassword }),
    }).catch(error => console.error('Error :', error));
}