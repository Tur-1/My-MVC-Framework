<?php import('layouts.Header', ['title' => 'Register']); ?>

<div class="row mb-3">
    <div class="d-flex align-items-center justify-content-between">
        <h1>Register</h1>
    </div>
</div>
<div class="row m-auto">
    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            <form class="row g-3" action="<?= route('register.store') ?>" method="post">
                <?= csrf_token() ?>
                <div class="mb-2">
                    <label for="FormControlInput1" class="form-label">Name</label>
                    <input value="<?= old('name') ?>" type="text" class="form-control <?= errors()->has('name') ? 'is-invalid' : '' ?>" name="name" id="FormControlInput1" placeholder="name">
                    <div id="FormControlInput1" class="invalid-feedback">
                        <?= errors()->first('name') ?>
                    </div>
                </div>
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

                <div class="mb-2">
                    <label for="password_confirmation" class="form-label">password confirmation</label>
                    <input type="password" class="form-control <?= errors()->has('password_confirmation') ? 'is-invalid' : '' ?>" name="password_confirmation" id="password_confirmation">
                    <div id="password_confirmation" class="invalid-feedback">
                        <?= errors()->first('password_confirmation') ?>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-md btn-dark" type="submit">Register </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php import('layouts.Footer'); ?>