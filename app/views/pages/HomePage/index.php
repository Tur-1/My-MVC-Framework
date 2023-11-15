<?php

import('layouts.Header', [
    'title' => 'Home',
]);

?>
<main>
    <h1>Hello, <?php echo $name; ?> </h1>
</main>
<?php import('layouts.Footer', [
    'name' => 'MVC',
]); ?>