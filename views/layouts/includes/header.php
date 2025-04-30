<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/images/logo.png" type="image/x-icon">

    <title><?= $page_title; ?></title>

    <!----- BOOTSTRAP STYLE ----->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <!----Login CSS ---->
    <!-- <link rel="stylesheet" type="text/css" href="public/css/login.css"> -->

    <!----- BOOTSTRAP JS ----->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.bundle.js"></script> -->
    <!-- <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="vendor/twbs/bootstrap/dist/js/popper.min.js"></script> -->
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        /* Flex container for the layout */
        .pageContent {
            display: flex;
            min-height: 100vh;
            /* Ensure it takes full viewport height */
        }

        /* Main content styles */
        .mainContent {
            background-color: #FFF;
            width: 100%;
            /* Main content takes up remaining space */
            padding: 20px;
        }

        /* Header styles */
        .headerDiv {
            background-color: #FBFBFB;
            padding: 20px;
        }
    </style>

</head>

<body>