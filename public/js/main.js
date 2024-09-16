let uploadedImages = [];
let map;
let marker;
let userLat, userLon;

// Get the user's current location
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
        userLat = position.coords.latitude;
        userLon = position.coords.longitude;
        initializeMap(userLat, userLon);
    }, () => {
        // If location access is denied, default to Karachi's coordinates
        userLat = 24.8607;
        userLon = 67.0011;
        initializeMap(userLat, userLon);
    });
} else {
    // Default to Karachi if geolocation is not available
    userLat = 24.8607;
    userLon = 67.0011;
    initializeMap(userLat, userLon);
}

function initializeMap(lat, lon) {
    map = L.map('map').setView([lat, lon], 14);  // Default location (user's location or Karachi)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    marker = L.marker([lat, lon]).addTo(map);  // Initial marker at the user's location
}

const dropzone = document.getElementById('dropzone');

dropzone.addEventListener('dragover', (event) => {
    event.preventDefault();
    dropzone.style.borderColor = '#ffeb3b';
    dropzone.style.color = '#ffeb3b';
});

dropzone.addEventListener('dragleave', () => {
    dropzone.style.borderColor = 'rgba(255, 255, 255, 0.5)';
    dropzone.style.color = '#fff';
});

dropzone.addEventListener('drop', (event) => {
    event.preventDefault();
    dropzone.style.borderColor = 'rgba(255, 255, 255, 0.5)';
    dropzone.style.color = '#fff';
    handleFiles(event.dataTransfer.files);
});

dropzone.addEventListener('click', () => {
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.multiple = true; // Allow multiple file selection
    fileInput.onchange = () => handleFiles(fileInput.files);
    fileInput.click();
});

function handleFiles(files) {
    for (const file of files) {
        if (uploadedImages.length >= 2) {
            // Remove the first image and its data
            uploadedImages.shift();
            document.getElementById('imageContainer').firstChild.remove();
        }

        uploadedImages.push(file);

        const reader = new FileReader();
        reader.onload = function (e) {
            const imageContainer = document.getElementById('imageContainer');
            const imageDiv = document.createElement('div');
            imageDiv.classList.add('image-data-container');

            // Assign unique IDs to location details
            const locationId = `locationDetails-${uploadedImages.length}`;
            const cityId = `cityName-${uploadedImages.length}`;
            const countryId = `countryName-${uploadedImages.length}`;

            // Display the image
            imageDiv.innerHTML = `<img src="${e.target.result}" alt="Uploaded Image">`;

            // Create a container for the data below the image
            const dataDiv = document.createElement('div');
            dataDiv.classList.add('image-info');

            // Append image and data containers
            imageContainer.appendChild(imageDiv);
            imageDiv.appendChild(dataDiv);

            // Create a location details section for this image
            const locationDetailsHtml = `
                <div id="${locationId}" class="text-center text-white">
                    <h3>Location Details</h3>
                    <p id="${cityId}">City:</p>
                    <p id="${countryId}">Country:</p>
                </div>`;
            dataDiv.innerHTML += locationDetailsHtml;

            const image = new Image();
            image.onload = function () {
                // Display image properties
                const imageData = `
                    <table>
                        <tr><th>ImageWidth</th><td>${image.width}px</td></tr>
                        <tr><th>ImageHeight</th><td>${image.height}px</td></tr>
                        <tr><th>ImageSize</th><td>${(image.width * image.height / 1000000).toFixed(2)} Megapixels</td></tr>
                    </table>`;
                dataDiv.innerHTML += imageData;

                EXIF.getData(image, function () {
                    const exifData = EXIF.getAllTags(this);
                    console.log('EXIF Data:', exifData);

                    // Display EXIF data
                    if (Object.keys(exifData).length > 0) {
                        let exifHtml = '<table>';
                        for (const [key, value] of Object.entries(exifData)) {
                            exifHtml += `<tr><th>${key}</th><td>${value}</td></tr>`;
                        }
                        exifHtml += '</table>';
                        dataDiv.innerHTML += exifHtml;
                    }

                    // Display Latitude and Longitude
                    let lat, lon;
                    if (exifData.GPSLatitude && exifData.GPSLongitude && exifData.GPSLatitudeRef && exifData.GPSLongitudeRef) {
                        lat = convertDMSToDD(exifData.GPSLatitude, exifData.GPSLatitudeRef);
                        lon = convertDMSToDD(exifData.GPSLongitude, exifData.GPSLongitudeRef);
                    } else {
                        lat = userLat;
                        lon = userLon;
                    }
                    const gpsHtml = `
                        <h3 class="text-center pt-15">Latitude and Longitude</h3>
                        <p class="text-center">Latitude: ${lat}</p>
                        <p class="text-center">Longitude: ${lon}</p>`;
                    dataDiv.innerHTML += gpsHtml;

                    if (marker) {
                        map.removeLayer(marker);
                    }
                    const latLng = [lat, lon];
                    map.setView(latLng, 14);
                    marker = L.marker(latLng).addTo(map);

                    // Reverse geocoding to get city and country names
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById(cityId).innerText = `City: ${data.address.city || data.address.town || data.address.village || 'N/A'}`;
                            document.getElementById(countryId).innerText = `Country: ${data.address.country || 'N/A'}`;
                        })
                        .catch(error => {
                            console.error('Error fetching location details:', error);
                            document.getElementById(cityId).innerText = 'City: N/A';
                            document.getElementById(countryId).innerText = 'Country: N/A';
                        });
                });
            };
            image.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

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
