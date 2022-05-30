<?php $this->layout("_theme"); ?>
    <main class="content">
        <div class="header-list-page">
            <h1 class="title">Categories</h1>
            <a href="<?= $router->route("category.create") ?>" class="btn-action">Add new Category</a>
        </div>
        <table class="data-grid">
            <tr class="data-row">
                <th class="data-grid-th">
                    <span class="data-grid-cell-content">Code</span>
                </th>
                <th class="data-grid-th">
                    <span class="data-grid-cell-content">Name</span>
                </th>
                <th class="data-grid-th">
                    <span class="data-grid-cell-content">Actions</span>
                </th>
            </tr>
            <?php foreach ($categories as $item): ?>
                <tr class="data-row">
                    <td class="data-grid-td">
                        <span class="data-grid-cell-content"><?=$item->id?></span>
                    </td>

                    <td class="data-grid-td">
                        <span class="data-grid-cell-content"><?=$item->name?></span>
                    </td>

                    <td class="data-grid-td">
                        <div class="actions">
                            <a href="<?= url('/categoria/edit/'.$item->id) ?>"><div class="action edit"><span>Edit</span></div></a>
                            <a onclick="return confirm('Tem certeza que deseja deletar?')" href="<?= url('/categoria/delete/'.$item->id) ?>"><div class="action delete"><span>Delete</span></div></a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>

<?= flash() ?>