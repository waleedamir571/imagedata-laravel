<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <title>Image Upload with Map</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <style>
        /* Center the flags and add spacing */
        #flags {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        #flags .flag {
            margin: 5px;
            cursor: pointer;
        }

        /* Centering the image gallery */
        #image-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .image-gallery-item {
            max-width: 100%;
        }

        .image-gallery-item img {
    width: 100%;
    height: 70%;
    border-radius: 5px;
}
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-white">Uploaded Images</h2>
    </div>

    <!-- Flags Section -->
    <div id="flags">
        @foreach ($countries as $country)
            <img src="https://flagsapi.com/{{ $country->country_code }}/shiny/64.png" class="flag"
                data-country="{{ $country->country_code }}" alt="{{ $country->country }}">
        @endforeach
    </div>

    <!-- Image Gallery Section -->
    <div id="image-gallery" class="row">
        <!-- Images will be loaded here -->
    </div>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <!-- Include the exif-js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function() {
            $('.flag').on('click', function() {
                const country = $(this).data('country');

                $.ajax({
                    url: "{{ route('images.show') }}",
                    type: 'GET',
                    data: { country: country },
                    success: function(response) {
                        const imageContainer = $('#image-gallery');
                        imageContainer.empty();
                        response.images.forEach(function(imagePath) {
                            imageContainer.append(

                                `
                                <div class="col-md-3 image-gallery-item">
                                    <img src="https://getmycertificationdoen.com/image-data/storage/app/public/${imagePath}" alt="Image">
                                </div>`
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
