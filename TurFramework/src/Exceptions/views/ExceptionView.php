<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $className; ?> </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            border: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
            text-decoration: none !important;
        }

        .hljs {
            background: transparent;

        }

        .hljs-number,
        .hljs-built_in {
            color: white !important;
        }

        span.hljs-keyword {
            color: rgba(248, 113, 113, 1) !important;

        }

        .hljs-title {
            color: rgb(58 195 216) !important;
        }

        .hljs-variable,
        code.language-php.hljs {
            color: #E5E7EB !important;
        }

        .hljs-string,
        .hljs-subst {
            color: rgb(253 255 0 / 51%) !important;
        }

        html {
            background-color: #111827 !important;
        }

        body {
            display: flex;
            flex-direction: column;
            background-color: #111827 !important;
        }

        nav {
            border-color: white;
            border-bottom: 1px solid #d5d5d53b !important;
            display: flex;
            align-items: center;
            padding: 6px;
            font-size: 10px !important;
            color: #dfdfdf;
        }

        footer {
            margin-top: auto;
        }

        .background-primary {
            background-color: #18212F !important;
        }

        .background-secondary {
            background-color: #1F2937 !important;
        }


        section {
            margin-top: 40px;
            border-radius: 10px;
            color: white;
            background-color: #18212F !important;

        }

        .header-section {
            display: flex;
            overflow: hidden;
            font-size: 14px;

        }

        .main-section {
            height: 65vh;
            overflow-y: auto !important;
        }


        .header-section>* {
            padding: 32px 40px;
        }

        .greenbackground {
            background-color: #6EE7B7 !important;
            background-clip: padding-box;

        }

        .background-red-color {
            background-color: rgb(230 99 99 / 37%) !important;
            color: white;
            display: flex;
        }

        .redckground {
            background-color: #6EE7B7 !important;
            background-clip: padding-box;
            color: white;

        }

        .nav-link {
            border-bottom: 1px solid #cccccc38;
            padding: 18px;
            font-size: 14px;
        }

        .nav-link.active {
            background-color: #8a3333 !important;
        }

        .tab-pane>* {
            font-size: 16px;
            line-height: 2;

        }

        main {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .exceptionClassDiv {
            background-color: #161E2A;
            padding: 7px 20px;
            display: flex;
            align-items: center;
            width: fit-content;

            margin-bottom: 1rem !important;

            font-size: 15px;
        }

        .errorMessage {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .filename {
            color: #a5a5a5;
            display: flex;
            justify-content: end;
            margin-right: 30px;
            margin-top: 10px;
            font-size: 13px;
        }

        .Tur-Framework {
            font-size: small;
            color: #bbb;
        }

        .nav-logo {
            font-size: 16px;
            font-weight: 600;
            color: #f0f0f0;
        }

        .tab-content {
            overflow-x: auto;

        }

        .sidebar {
            overflow-y: auto !important;
            height: 100%;
        }

        section::-webkit-scrollbar,
        code::-webkit-scrollbar,
        .sidebar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            opacity: 0 !important;
        }

        code {
            white-space: inherit !important;
        }
    </style>
</head>


<body>
    <nav class="navbar navbar-expand-lg ">
        <div class="container">
            <span class="nav-logo">Tur Framework</span>
        </div>
    </nav>

    <main class="container">
        <section class="header-section ">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <div class="exceptionClassDiv">
                        <span></span> <?php echo $className; ?> </span>
                    </div>
                    <div class="errorMessage">
                        <span> <?php echo $primary_message; ?> </span>
                    </div>
                    <div class="errorMessage mt-4 " style="font-size:15px">
                        <span> <?php echo $secondary_message; ?> </span>
                    </div>
                </div>
                <div class="w-25 text-end">
                    <span class="Tur-Framework"> Tur Framework</span>
                </div>
            </div>
            <?php if ($multipleMessages) { ?>
                <div class="w-50 greenbackground text-dark">
                    <h6 class="mb-4"><?php echo $multipleMessages[0]; ?></h6>
                    <span><?php echo $multipleMessages[1]; ?> </span>
                </div>
            <?php } ?>
        </section>
        <section class="main-section">
            <div class="d-flex align-items-start h-100">


                <div class="nav flex-column nav-pills me-3 background-secondary " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <?php
                    foreach ($errorData as $key => $value) { ?>
                        <button class="nav-link <?php echo $key == 0 ? 'active' : ''; ?> rounded-0 text-light" id="v-pills-<?php echo 'class-' . $key; ?>-tab" data-bs-toggle="pill" data-bs-target="#v-pills-<?php echo 'class-' . $key; ?>" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">
                            <?php echo $value['file']; ?> : <?php echo $value['line']; ?>
                        </button>
                    <?php } ?>
                </div>


                <div class="tab-content w-100" id="v-pills-tabContent">

                    <?php foreach ($errorData as $key => $value) { ?>

                        <div class="tab-pane  fade <?php echo $key == 0 ? 'show active' : ''; ?>" style="font-size: 20px;" id="v-pills-<?php echo 'class-' . $key; ?>" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">

                            <span class="filename"> <?php echo $value['file']; ?></span>

                            <?php echo '<pre class="w-100"> <code class="language-php">';
                            for ($i = $value['start_line'] - 1; $i < $value['end_line']; ++$i) {
                                $codeSnippet = htmlspecialchars($value['file_content'][$i]);

                                echo '<span>' . $i . ':' . $codeSnippet . '</span>';
                            }
                            echo '</pre> </code>'; ?>

                        </div>
                    <?php
                    } ?>


                </div>

            </div>
        </section>
    </main>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <script>
        hljs.highlightAll();
    </script>

</body>

</html>