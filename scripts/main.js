let uploadedImages = document.querySelector(".uploaded_images");
let form = document.querySelector("form.upload-image-form");
let nameInput = form.querySelector("input[name=name]");
let tagsInput = form.querySelector("input[name=tags]");
let descriptionInput = form.querySelector("input[name=description]");
var myWidget = cloudinary.createUploadWidget({
    cloudName: 'stevenm02',
    uploadPreset: 'my_cloud_preset',
}, (error, result) => {
    if (!error && result && result.event === "success") {
        console.log('Done! Here is the image info: ', result.info);

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

document.getElementById("upload_widget")?.addEventListener("click", function () {
    myWidget.open();
}, false);

/*
{
"id": "uw-file3",
"batchId": "uw-batch2",
"asset_id": "71982dfd76f1be4b61068e2eb7b5abb4",
"public_id": "photo-1633113214207-1568ec4b3298_xtc3cv",
"version": 1638412998,
"version_id": "7e01850dcf201490b150d98b30975150",
"signature": "d67915cbaf38ed099ea7904e3541903d860c90d0",
"width": 960,
"height": 640,
"format": "jpg",
"resource_type": "image",
"created_at": "2021-12-02T02:43:18Z",
"tags": [],
"bytes": 141474,
"type": "upload",
"etag": "fca32a20449ac4c616f44cab110efeb3",
"placeholder": false,
"url": "http://res.cloudinary.com/stevenm02/image/upload/v1638412998/photo-1633113214207-1568ec4b3298_xtc3cv.jpg",
"secure_url": "https://res.cloudinary.com/stevenm02/image/upload/v1638412998/photo-1633113214207-1568ec4b3298_xtc3cv.jpg",
"access_mode": "public",
"existing": false,
"original_filename": "photo-1633113214207-1568ec4b3298",
"path": "v1638412998/photo-1633113214207-1568ec4b3298_xtc3cv.jpg",
"thumbnail_url": "https://res.cloudinary.com/stevenm02/image/upload/c_limit,h_60,w_90/v1638412998/photo-1633113214207-1568ec4b3298_xtc3cv.jpg"
}
*/