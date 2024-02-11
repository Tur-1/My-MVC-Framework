<?php import('layouts.Header'); ?>
<main>

    <div class="container">
        <div class="row">
            <h1>Home</h1>

            <form action="<?php echo route('store') ?>" method="post">
                <?php echo csrf_token() ?>
                <span class=""><?php echo session('message') ?></span>
                <div class="row mb-3">
                    <input type="text" name="name" value="<?php echo old('name') ?>">
                </div>
                <div class="row  mb-3">

                    <?php if (session()->has('errors')) :
                        foreach (session('errors') as $key => $value) : ?>
                            <span class="is-invalid">
                                <?php echo  $value ?>
                            </span>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
                <div class="row">
                    <button type="submit">submit</button>
                </div>
            </form>
        </div>
    </div>

</main>

<?php import('layouts.Footer'); ?>