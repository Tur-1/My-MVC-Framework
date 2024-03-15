<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> <?= isset($title) ? config('app.name') . ' | ' . $title : config('app.name')  ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
    main {
        display: flex;
        flex-direction: column; 
    }

    footer {
        margin-top: auto;
    }
 
    section{
      height: 100%; 
    }
    .content,.col-md-2{ 
      min-height: 100vh; 
      display: flex;
      flex-direction: column;
    }
</style>

</style>

</head>

<body>

<main class="container-fluid">
  <div class="row">
    <div class="col-md-2">
    <?php import('layouts.admin.sidebar'); ?>
    </div>
    <div class="col-md-10 content">
      
      <?php import('layouts.admin.navbar'); ?>
        <section class="container">
   