<?php if (session()->has('success')) : ?>
    <div id="alert_success" class="alert alert-success" role="alert">
        <?= session('success') ?>
    </div>
    <script >
    setTimeout(() => {
        document.getElementById('alert_success').style.display = 'none';
    }, 1500);
</script>
<?php endif ?>

