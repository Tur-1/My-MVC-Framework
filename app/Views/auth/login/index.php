<?php import('layouts.frontend.Header', ['title' => 'Login']); ?>

<div class="row mb-3">
    <div class="d-flex align-items-center justify-content-between">
        <h1>Login</h1>
    </div>
</div>
<div class="row m-auto">
    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            <form class="row g-3" action="<?= route('login.store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-2">
                    <label for="formControlInput2" class="form-label">Email address</label>
                    <input value="<?= old('email') ?>" type="email" class="form-control <?= errors()->has('email') ? 'is-invalid' : '' ?>" name="email" id="formControlInput2" placeholder="email">
                    <div id="formControlInput2" class="invalid-feedback">
                        <?= errors()->first('email') ?>
                    </div>

                </div>
                <div class="mb-2">
                    <label for="formControlInputPassword2" class="form-label">Password</label>
                    <input type="password" class="form-control <?= errors()->has('password') ? 'is-invalid' : '' ?>" name="password" id="formControlInputPassword2" />
                    <div id="formControlInputPassword2" class="invalid-feedback">
                        <?= errors()->first('password') ?>
                    </div>
                </div>


                <div class="col-12">
                    <button class="btn btn-md btn-dark" type="submit">Login </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php import('layouts.frontend.Footer'); ?>