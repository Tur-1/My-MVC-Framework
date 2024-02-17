<?php import('layouts.Header'); ?>


<?php import('components.success_message') ?>

<div class="row mb-3">
    <div class="d-flex align-items-center justify-content-between">
        <h4>users </h4>
        <a href="<?php echo route('users.create') ?>" class="btn btn-primary">
            new user
        </a>
    </div>
</div>
<div class="row">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">name</th>
                <th scope="col">email</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($users) > 0) : ?>
                <?php foreach ($users as $key => $user) : ?>
                    <tr>
                        <td scope="row"><?php echo $user->id ?></td>
                        <td><?php echo $user->name ?></td>
                        <td><?php echo $user->email ?></td>
                        <td>

                            <form action="<?php echo route('users.delete', ['id' => $user->id]) ?>" method="post">
                                <?php echo csrf_token() ?>

                                <a href="<?php echo route('users.edit', ['id' => $user->id]) ?>" class="btn btn-secondary btn-sm"> Edit</a>

                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this product?');">Delete</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else : ?>
                <tr>
                    <td class="text-center" colspan="4">no records found</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>


<?php import('layouts.Footer'); ?>