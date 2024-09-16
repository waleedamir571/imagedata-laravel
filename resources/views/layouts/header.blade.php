<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Default Title' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <!-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('css/corporate-ui-dashboard.mine209.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nucleo-svg.css') }}">

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Noto+Sans:300,400,500,600,700,800|PT+Mono:300,400,500,600,700"
      rel="stylesheet"
    />
    <!-- <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(0deg, #1f4037 0%, #99f2c8 100%);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 60px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .login-container h2 {
            font-weight: 700;
            margin-bottom: 30px;
            color: #ffeb3b;
            font-size: 2rem;
        }

        .form-control {
            background-color: rgba(0, 0, 0, 0.5);
            border: 1px solid #ffeb3b;
            border-radius: 5px;
            color: #fff;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-size: 1.1rem;
        }

        .btn-custom {
            background-color: #ffeb3b;
            border: none;
            color: #1f4037;
            padding: 15px 20px;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
            font-size: 1.2rem;
        }

        .btn-custom:hover {
            background-color: #e6c200;
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 13px;
            cursor: pointer;
        }
    </style> -->
</head>