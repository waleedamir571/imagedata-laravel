<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/corporate-ui-dashboard.mine209.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nucleo-svg.css') }}">
    <title>Image Upload with Map</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <style>
        /* Additional styles for the dropzone and image container */
        .dropzone {
            border: 2px dashed rgba(255, 255, 255, 0.5);
            padding: 50px;
            text-align: center;
            color: white;
            background-color: #333;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .image-container {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .image-container img {
            max-width: 150px;
            margin: 5px;
        }

        #map {
            height: 400px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="d-flex justify-content-end">
        <button id="logout-button" class="btn btn-success btn-dark text-end">Logout</button>
        

    </div>

    <div class="container">
        <h2 class="">Upload Images to See Location on Map</h2>
        <div id="dropzone" class="dropzone">Drag & Drop Images Here or Click to Select</div>
    </div>

    <!-- 2 columns inside the container -->
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div id="locationDetails" class="text-center ">
                    <h3>Location Details</h3>
                    <p id="cityName">City:</p>
                    <p id="countryName">Country:</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Image preview container -->
    <div id="imageContainer" class="image-container"></div>

    <div class="pb-20">
        <!-- Form with submit button -->
        <form id="imageUploadForm" class="text-center">
            @csrf
            <button class="btn btn-success btn-dark" type="submit" id="submitImages">Submit Images</button>
        </form>

    </div>
    <!-- Map container -->
    <div id="map"></div>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    {{-- <script src="{{ asset('js/main.js') }}"></script> --}}
    <!-- Include the exif-js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>


</body>

</html>

<script>
    document.getElementById('logout-button').addEventListener('click', function() {
        document.getElementById('logout-form').submit();
    });


    let uploadedImages = [];
    let imageLocationData = [];
    let map;
    let marker;
    let userLat, userLon;
    let imageCounter = 0;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLat = position.coords.latitude;
                userLon = position.coords.longitude;
                initializeMap(userLat, userLon);
            },
            () => {
                userLat = 24.8607; // Default to Karachi
                userLon = 67.0011;
                initializeMap(userLat, userLon);
            }
        );
    } else {
        userLat = 24.8607; // Default to Karachi
        userLon = 67.0011;
        initializeMap(userLat, userLon);
    }

    function initializeMap(lat, lon) {
        map = L.map("map").setView([lat, lon], 14);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
        }).addTo(map);
        marker = L.marker([lat, lon]).addTo(map);
    }

    const dropzone = document.getElementById("dropzone");

    dropzone.addEventListener("dragover", (event) => {
        event.preventDefault();
        dropzone.style.borderColor = "#ffeb3b";
        dropzone.style.color = "#ffeb3b";
    });

    dropzone.addEventListener("dragleave", () => {
        dropzone.style.borderColor = "rgba(255, 255, 255, 0.5)";
        dropzone.style.color = "#fff";
    });

    dropzone.addEventListener("drop", (event) => {
        event.preventDefault();
        dropzone.style.borderColor = "rgba(255, 255, 255, 0.5)";
        dropzone.style.color = "#fff";
        handleFiles(event.dataTransfer.files);
    });

    dropzone.addEventListener("click", () => {
        const fileInput = document.createElement("input");
        fileInput.type = "file";
        fileInput.accept = "image/*";
        fileInput.multiple = true;
        fileInput.onchange = () => handleFiles(fileInput.files);
        fileInput.click();
    });


    function handleFiles(files) {
        for (const file of files) {
            if (uploadedImages.length >= 2) {
                uploadedImages.shift();
                document.getElementById("imageContainer").firstChild.remove();
            }

            uploadedImages.push(file);

            const reader = new FileReader();
            reader.onload = async function(e) {
                const imageContainer = document.getElementById("imageContainer");
                const imageDiv = document.createElement("div");
                imageDiv.classList.add("image-data-container");

                // Generate a unique data ID using a counter
                const uniqueId = `image-${++imageCounter}`;

                imageDiv.innerHTML = `<img src="${e.target.result}" alt="Uploaded Image">`;

                const dataDiv = document.createElement("div");
                dataDiv.classList.add("image-info");

                imageContainer.appendChild(imageDiv);
                imageDiv.appendChild(dataDiv);

                // Use the unique ID in data attributes for this image
                const locationDetailsHtml = `
            <div class="text-center " data-id="${uniqueId}">
                <h3>Location Details</h3>
                <p>City: <span class="cityName" data-id="${uniqueId}"></span></p>
                <p>Country: <span class="countryName" data-id="${uniqueId}"></span></p>
                <input type="hidden" class="countryCode" data-id="${uniqueId}">
            </div>`;
                dataDiv.innerHTML += locationDetailsHtml;

                const image = new Image();
                image.onload = async function() {
                    const imageData = `
            <table>
                <tr><th>ImageWidth</th><td>${image.width}px</td></tr>
                <tr><th>ImageHeight</th><td>${image.height}px</td></tr>
                <tr><th>ImageSize</th><td>${((image.width * image.height) / 1000000).toFixed(2)} Megapixels</td></tr>
            </table>`;
                    dataDiv.innerHTML += imageData;

                    EXIF.getData(image, async function() {
                        const exifData = EXIF.getAllTags(this);
                        console.log("EXIF Data:", exifData);

                        let lat, lon;
                        if (exifData.GPSLatitude && exifData.GPSLongitude) {
                            lat = convertDMSToDD(exifData.GPSLatitude, exifData
                                .GPSLatitudeRef);
                            lon = convertDMSToDD(exifData.GPSLongitude, exifData
                                .GPSLongitudeRef);
                        } else {
                            lat = userLat;
                            lon = userLon;
                        }

                        if (marker) {
                            map.removeLayer(marker);
                        }
                        const latLng = [lat, lon];
                        map.setView(latLng, 14);
                        marker = L.marker(latLng).addTo(map);

                        try {
                            const data = await fetch(
                                `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`
                            ).then((response) => response.json());

                            // Use the unique ID in the querySelector to select the correct elements
                            const cityElement = document.querySelector(
                                `.cityName[data-id="${uniqueId}"]`);
                            const countryElement = document.querySelector(
                                `.countryName[data-id="${uniqueId}"]`);
                            const countryCodeElement = document.querySelector(
                                `.countryCode[data-id="${uniqueId}"]`);

                            const countryName = data.address.country || "N/A";

                            // Fetch country code dynamically
                            const countryCodeResponse = await fetch(
                                `https://restcountries.com/v3.1/name/${countryName}`
                            );
                            const countryCodeData = await countryCodeResponse.json();
                            const countryCode = countryCodeData[0]?.cca2 ||
                                "N/A"; // cca2 is the country code

                            cityElement.innerText = data.address.city || data.address
                                .town || data.address.village || "N/A";
                            countryElement.innerText = countryName;
                            countryCodeElement.value =
                                countryCode; // Use value for hidden input

                        } catch (error) {
                            console.error("Error fetching location details:", error);
                        }
                    });
                };
                image.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Function to submit images using $.ajax
    function submitImages() {
        const formData = new FormData();
        uploadedImages.forEach((image, index) => {
            formData.append(`images[${index}]`, image);

        });

        // Get the last country code from the hidden inputs
        const lastCountryCodeElement = document.querySelector('.countryCode:last-child');

        formData.append('country_code', lastCountryCodeElement ? lastCountryCodeElement.value : '');


        const cityElement = document.getElementsByClassName('cityName')[0];
        const cityValue = cityElement.innerText;

        const countryElement = document.getElementsByClassName('countryName')[0];
        const countryValue = countryElement.innerText;

        formData.append(`city`, cityValue);
        formData.append(`country`, countryValue);

        $.ajax({
            url: "{{ route('upload.images') }}",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            contentType: false,
            processData: false,
            success: function(data) {
                window.location.href = "{{ route('images.show') }}";
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            },
        });
    }

    // Event listener for the submit button
    $(document).ready(function() {
        $('#submitImages').on('click', function(e) {
            e.preventDefault();
            if (uploadedImages.length > 0) {
                submitImages();
            } else {
                alert('No images selected for upload.');
            }
        });
    });

    // Prevent form default submission
    document.getElementById("imageUploadForm").addEventListener("submit", function(event) {
        event.preventDefault();
        if (uploadedImages.length > 0) {
            submitImages();
        } else {
            alert("No images selected for upload.");
        }
    });

    // Helper function to convert DMS (Degrees, Minutes, Seconds) to Decimal Degrees
    function convertDMSToDD(dmsArray, ref) {
        const degrees = dmsArray[0].numerator;
        const minutes = dmsArray[1].numerator;
        const seconds = dmsArray[2].numerator / dmsArray[2].denominator;
        let dd = degrees + minutes / 60 + seconds / 3600;
        if (ref === "S" || ref === "W") {
            dd = dd * -1;
        }
        return dd.toFixed(6);
    }
</script>
