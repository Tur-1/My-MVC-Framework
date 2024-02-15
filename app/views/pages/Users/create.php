<?php import('layouts.Header'); ?>
<main class=" mt-4 mb-2">
    <div class="container">

        <div class="row mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <h1>Create</h1>
            </div>
        </div>
        <form class="row g-3" action="<?php echo route('usersStore') ?>" method="post">
            <?php echo csrf_token() ?>
            <div class="mb-2">
                <label for="FormControlInput1" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="FormControlInput1" placeholder="name">
                <div id="FormControlInput1" class="invalid-feedback">

                </div>

            </div>
            <div class="mb-2">
                <label for="formControlInput2" class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" id="formControlInput2" placeholder="name@example.com">
                <div id="formControlInput2" class="invalid-feedback">

                </div>

            </div>
            <div class="mb-2">
                <label for="formControlInputPassword2" class="form-label">Password</label>
                <input type="password" class="form-control  " name="password" id="formControlInputPassword2" />
                <div id="formControlInputPassword2" class="invalid-feedback">

                </div>
            </div>

            <div class="mb-2">
                <label for="password_confirmation" class="form-label">password confirmation</label>
                <input type="password" class="form-control " name="password_confirmation" id="password_confirmation">
                <div id="password_confirmation" class="invalid-feedback">

                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Save </button>
            </div>
        </form>
    </div>
</main>

<?php import('layouts.Footer'); ?>