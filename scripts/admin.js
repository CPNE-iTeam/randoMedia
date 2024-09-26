
document.addEventListener('DOMContentLoaded', () => {
    const basePath = ".."
    const apiPath = `${basePath}/api/recive_all.php`;
    const mediaPath = `${basePath}/medias/`;
    

    function fetchMedias() {
        fetch(apiPath)
            .then(response => response.json())
            .then(data => {
                let result = "";
                if (data.success) {
                    console.log(data.files)
                    for (let i = 0; i < data.files.length; i++) {
                        const mediaUrl = mediaPath + data.files[i];
                        result += `
                            ${data.files[i].endsWith('.mp4') ? 
                                `<video width='100' height='100' controls><source src="${mediaUrl}" type="video/mp4">Your browser does not support the video tag.</video>` : 
                                `<img width='100' height='100' src="${mediaUrl}" alt="Random Media">`}
                            <button onclick="removeMedia('medias/${data.files[i]}')">Remove</button>
                        `;
                    }
                    console.log(result)
                    document.getElementById("content").innerHTML = result

                }
            })
            .catch(error => console.error('Stupid error :', error));
    }


    fetchMedias();
});
