<?php if (session()->has('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session('success') ?>
    </div>
<?php endif ?>

<script>
    setTimeout(() => {
        document.querySelector('.alert-success').style.display = 'none';
    }, 1500);
</script>