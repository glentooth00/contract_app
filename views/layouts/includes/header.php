<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../public/images/logo.png" type="image/x-icon">
    <link rel="icon" href="../../../public/images/logo.png" type="image/x-icon">

    <title><?= $page_title; ?></title>

    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- BOOTSTRAP 5.3.3 CDN (Single Include) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Font Awesome (CSS only) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom Styles -->
    <style>
        html {
            /* overflow-x: hidden; */
        }

        body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #37AFE1;
        }

        .pageContent {
            display: flex;
            min-height: 100vh;
        }

        .mainContent {
            background-color: #FFF;
            width: 100%;
            padding: 20px;
        }

        .headerDiv {
            background-color: #FBFBFB;
            padding: 20px;
        }

        .main-layout {
            display: flex;
            min-height: 100vh;
        }

        .sideBar {
            width: 220px;
            background-color: #343a40;
            padding: 20px;
            color: white;
        }

        .content-area {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fa;
        }

        #statusFilter {
            width: 250px;
            display: inline-block;
            margin-left: 10px;
        }

        <style>.main-layout {
            display: flex;
            min-height: 100vh;
        }

        .sideBar {
            width: 220px;
            background-color: #343a40;
            padding: 20px;
            color: white;
        }

        .content-area {
            flex: 1;
            padding: 30px;
            background-color: #f8f9fa;
        }

        #statusFilter {
            width: 250px;
            display: inline-block;
            margin-left: 10px;
        }

        /* Add margin above the table */
        .dataTables_wrapper {
            margin-top: 20px;
        }

        label {
            display: inline-block;
            margin-bottom: 20px;
        }

        a.paginate_button.current {
            padding: 1px !important;
        }
    </style>
</head>

<body>