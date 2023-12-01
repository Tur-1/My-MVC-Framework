<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> <?php echo isset($title) ? config('app.name') .' | ' . $title : config('app.name')  ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<style>
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;

    }

    footer {
        margin-top: auto;
    }

    main {
        min-height: 100%;
    }
</style>

<body>
    <?php import('layouts.navbar'); ?>