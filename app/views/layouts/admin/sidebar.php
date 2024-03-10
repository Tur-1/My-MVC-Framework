<div class="d-flex flex-column p-3 text-bg-dark w-100 h-100">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
      <span class="fs-4"><?= config('app.name')?></span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
  
      <li>
        <a href="<?= route('admin.dashboard')?>" class="nav-link text-white  <?= request()->is(route('admin.dashboard')) ? 'active ' : '' ?>">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
          Dashboard
        </a>
      </li>
      <li>
        <a href="<?= route('admin.login')?>" class="nav-link text-white  <?= request()->is(route('admin.login')) ? 'active ' : '' ?>">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
          login
        </a>
      </li>
    </ul> 
  </div>