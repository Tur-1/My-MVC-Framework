<?php import('layouts.Header'); ?>
<main>
    <h1>Home</h1>

    <div class="container">
        <div class="row">
            <main class="form-signin w-100 m-auto">
                <div class="row  mb-2">

                    <?php if (session()->has('errors')) :
                        foreach (session('errors') as $key => $value) : ?>
                            <span class="is-invalid">
                                <?php echo  $value[0] ?>
                            </span>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
                <form action="<?php echo route('store') ?>" method="post">
                    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
                    <?php echo csrf_token() ?>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="email" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="password" id="floatingPassword" placeholder="Password">
                        <label for="floatingPassword">Password</label>
                    </div>


                    <button class="btn btn-primary w-100 py-2 mt-3" type="submit">Sign in</button>

                </form>
            </main>
        </div>

    </div>

</main>

<?php import('layouts.Footer'); ?>