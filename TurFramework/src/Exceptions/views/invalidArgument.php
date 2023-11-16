<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<style>

* {
    margin: 0;
    padding: 0;
    border: 0;
    box-sizing: border-box;
    scroll-behavior: smooth;
    text-decoration: none !important;
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
    margin-top: 60px;
    border-radius: 10px;
    color: white;
    background-color: #18212F !important;

}

.header-section {
    display: flex;
    overflow: hidden;
    font-size: 18px;

}

.main-section {
    height: 65vh;
    overflow-x: hidden;
    overflow-y: auto; 
}


.header-section>* {

    padding: 32px 40px;
}

.greenbackground {
    background-color: #6EE7B7 !important;
    background-clip: padding-box;

}
.background-red-color{
    background-color: rgb(230 99 99 / 37%) !important;
    color: white;
    font-size: 16px !important;
    display: flex;
}
.redckground {
    background-color: #6EE7B7 !important;
    background-clip: padding-box;
    color: white;

}

.nav-link {
    border-bottom: 1px solid #cccccc38;
    padding: 27px;
}

.nav-link.active {
    background-color: #EF4444 !important;
}
.lineheight{
    line-height: 2;
}
.tab-pane>*{
    font-size: 14px ;
    line-height: 2;

}
main {
    min-height: 100%;
    display: flex;
    flex-direction: column;
}
</style>

<body>
    <nav class="navbar navbar-expand-lg border-bottom">
        <div class="container">
            <h2 class="navbar-brand text-light">Tur Framework</h2>
        </div>
    </nav>

    <main class="container">
        <section class="header-section ">
            <div class="w-50">
                <h5 class="ms-5 mb-3"> <?php echo $exceptionClass; ?> </h5>
                <h4> <?php echo $errorMessage; ?> </h4>
            </div>
            <div class="w-50  greenbackground text-dark">
                <span><?php echo $errorMessage; ?> </span>
                <h5 class="mt-4">Are you sure the view exists ?</h5>
            </div>
        </section>

        <section class="main-section">
            <div class="d-flex align-items-start">
                <div class="nav flex-column nav-pills me-3 background-secondary" id="v-pills-tab" role="tablist"
                    aria-orientation="vertical">
                    <?php
                foreach ($errorData as $key => $value) {?>
                    <button class="nav-link <?php echo $key == 0 ? 'active' : ''; ?> rounded-0 text-light"
                        id="v-pills-<?php echo 'class-'.$key; ?>-tab" data-bs-toggle="pill"
                        data-bs-target="#v-pills-<?php echo 'class-'.$key; ?>" type="button" role="tab"
                        aria-controls="v-pills-home" aria-selected="true">
                        <?php echo $value['error_file']; ?> : <?php echo $value['error_line']; ?>
                    </button>
                    <?php }?>
                </div>
                <div class="tab-content w-100" id="v-pills-tabContent">

                    <?php foreach ($errorData as $key => $value) {
                    $file = $value['error_file'];
                    $line = $value['error_line'];
                    if ($file !== 'Unknown file' && $line !== 'Unknown line') {
                        $fileContent = file($file);
                        $startLine = max(1, $line - 15); // Display 5 lines before the error line
                        $endLine = min(count($fileContent), $line + 15); // Display 5 lines after the error line

                    ?>

                    <div class="tab-pane  fade <?php echo $key == 0 ? 'show active' : ''; ?>"
                        style="font-size: 20px;" id="v-pills-<?php echo 'class-'.$key; ?>" role="tabpanel"
                        aria-labelledby="v-pills-home-tab" tabindex="0">



                        <?php
                           echo '<pre class="w-100"> <code class="w-100">';
                        for ($i = $startLine - 1; $i < $endLine; ++$i) {
                            $codeSnippet = htmlspecialchars($fileContent[$i]);

                            if ($i + 1 === $line) {
                                echo "<span class='background-red-color  '> {$i}: {$codeSnippet}</span>";
                            } else {
                                echo "<span class=' '></span> {$i}: {$codeSnippet}</span>";
                            }
                        }
                        echo '</pre> </code>'; ?>

                    </div>
                    <?php
                    }
                } ?>


                </div>

        </section>
    </main>
    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>