<?php

$this->layout("_theme"); ?>
    <main class="content">
        <div class="header-list-page">
            <h1 class="title">Products</h1>
            <a href="<?= $router->route("product.create") ?>" class="btn-action">Add new Product</a>
        </div>
        <table class="data-grid">
            <tr class="data-row">
                <th class="data-grid-th">
                    <span class="data-grid-cell-content">#</span>
                </th>
                <th class="data-grid-th">
                    <span class="data-grid-cell-content">Name</span>
                </th>
                <th class="data-grid-th">
                    <span class="data-grid-cell-content">SKU</span>
                </th>
                <th class="data-grid-th">
                    <span class="data-grid-cell-content">Price</span>
                </th>
                <th class="data-grid-th">
                    <span class="data-grid-cell-content">Quantity</span>
                </th>
                <th class="data-grid-th">
                    <span class="data-grid-cell-content">Categories</span>
                </th>

                <th class="data-grid-th">
                    <span class="data-grid-cell-content">Actions</span>
                </th>
            </tr>
            <?php
            foreach ($products as $item): ?>
                <tr class="data-row">
                    <td class="data-grid-td">
                        <span class="data-grid-cell-content"><img src="<?=url("storage/" . $item->image())?>" width="100px" alt=""></span>
                    </td>
                    <td class="data-grid-td">
                        <span class="data-grid-cell-content"><?=$item->name?></span>
                    </td>

                    <td class="data-grid-td">
                        <span class="data-grid-cell-content"><?=$item->sku?></span>
                    </td>

                    <td class="data-grid-td">
                        <span class="data-grid-cell-content">R$<?=str_price($item->price)?></span>
                    </td>

                    <td class="data-grid-td">
                        <span class="data-grid-cell-content"><?=number_fmt($item->quantity, 0)?></span>
                    </td>

                    <td class="data-grid-td">
                        <span class="data-grid-cell-content"><?=str_replace('|','<Br/>',$item->categories)?></span>
                    </td>

                    <td class="data-grid-td">
                        <div class="actions">
                            <a href="<?= url('/produto/edit/'.$item->id) ?>"><div class="action edit"><span>Edit</span></div></a>
                            <a onclick="return confirm('Tem certeza que deseja deletar?')" href="<?= url('/produto/delete/'.$item->id) ?>"><div class="action delete"><span>Delete</span></div></a>
                        </div>
                    </td>
                </tr>
            <?php
            endforeach; ?>
        </table>
    </main>

<?= flash() ?>