<?php

includeComponent('layouts.Header', [
    'title' => 'Home'
]);

?>
<main>
    <h1>Hello, <?php echo $name ?> </h1>
</main>
<?php includeComponent('layouts.Footer', [
    'name' => 'MVC'
]); ?>