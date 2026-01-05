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
    html, body {
        width: 100%;
        margin: 0;
        padding: 0;
        height: 100%;
        background-color: #1D546D;
    }

    .pageContent {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
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
        flex-wrap: wrap;
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
        width: 100%;
        max-width: 250px;
        display: inline-block;
        margin-left: 10px;
    }

    /* DataTable spacing */
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

    /* ✅ RESPONSIVENESS */
    @media (max-width: 992px) {
        .main-layout {
            flex-direction: column;
        }

        .sideBar {
            width: 100%;
            text-align: center;
        }

        .content-area {
            padding: 15px;
        }

        #statusFilter {
            margin-left: 0;
            margin-top: 10px;
        }
    }

    @media (max-width: 576px) {
        .headerDiv, .mainContent {
            padding: 10px;
        }

        .content-area {
            padding: 10px;
        }
    }
</style>

</head>

<body>