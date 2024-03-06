<?php import('layouts.Header', [
    'title' => 'Dashboard'
]); ?>

<div>
    <?php import('components.success_message') ?>

    <h4>welcome , <?= auth()->user()->name ?> </h4>
</div>



<?php import('layouts.Footer'); ?>