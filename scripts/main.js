let uploadedImages = document.querySelector(".uploaded_images");
let form = document.querySelector("form.upload-image-form");
let nameInput = form.querySelector("input[name=name]");
let tagsInput = form.querySelector("input[name=tags]");
let descriptionInput = form.querySelector("input[name=description]");

// Create upload widget
var myWidget = cloudinary.createUploadWidget({
    cloudName: 'stevenm02',
    uploadPreset: 'my_cloud_preset',
}, (error, result) => {
    if (!error && result && result.event === "success") {
        console.log('Done! Here is the image info: ', result.info);

        // Upload image data to the SQL database
        let formData = new FormData(form);
        formData.append("description", descriptionInput.value);
        formData.append("name", nameInput.value);
        formData.append("tags", tagsInput.value);
        formData.append("likes", 0);
        formData.append("url", result.info.secure_url);

        let img = new Image();
        img.src = result.info.secure_url;
        img.alt = descriptionInput.value;
        uploadedImages.appendChild(img);

        fetch("./upload_image", {
            method: "POST",
            body: formData
        }).then((response) => {
            console.log(response)
        })
    }
});

// Open upload widget when widget button is clicked
document.getElementById("upload_widget")?.addEventListener("click", function () {
    myWidget.open();
}, false);