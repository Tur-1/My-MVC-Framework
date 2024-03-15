<nav class="p-3 mb-3 ">
  <div class="border-bottom rounded-1 shadow-sm">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start  p-2 ">
      <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
        <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
          <use xlink:href="#bootstrap"></use>
        </svg>
      </a>

      <ul class="navbar-nav">

        <?php if (auth('admins')->check()) : ?>
          <li class="nav-item dropdown">
            <a role="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <?= auth('admins')->user()->name ?>
            </a>
            <ul class="dropdown-menu ">
              <li class="nav-item">
                <a class="dropdown-item" href="<?= route('admin.dashboard') ?>">Dashboard</a>
              </li>

              <li class="nav-item">
                <form action="<?= route('admin.logout') ?>" method="post">
                  <?= csrf_field() ?>
                  <button class="dropdown-item" type="submit">Logout</button>
                </form>
              </li>
            </ul>
          </li>
        <?php endif ?>
      </ul>
    </div>
  </div>
</nav>