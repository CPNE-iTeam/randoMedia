function removeMedia() {
    const mediaPath = localStorage.getItem("mediaPath");
    const adminPassword = prompt("Enter the admin password to remove this media:");
    fetch("api/remove.php", {
        method: 'POST',
        body: objectToFormData({ mediaPath: mediaPath, adminPassword: adminPassword }),
    }).catch(error => console.error('Error :', error));
}

function objectToFormData(obj) {
    const formData = new FormData();
    for (const key in obj) {
      if (obj.hasOwnProperty(key)) {
        formData.append(key, obj[key]);
      }
    }
    return formData;
  }