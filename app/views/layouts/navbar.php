<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="#"><?php echo config('app.name'); ?> </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo request()->is(route('homePage')) ? 'active': ''?>" aria-current="page" href="<?php echo route('homePage') ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo request()->is(route('aboutPage')) ? 'active': ''?>" aria-current="page" href="<?php echo route('aboutPage') ?>">About</a>
                </li>
            </ul>
        </div>
    </div>
</nav>