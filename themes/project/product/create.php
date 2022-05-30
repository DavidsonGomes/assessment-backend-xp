<?php $this->layout("_theme"); ?>
<main class="content">
    <h1 class="title new-item">New Product</h1>

    <form enctype="multipart/form-data" action="<?=$router->route('product.store')?>" method="post">
        <div class="input-field">
            <label for="price" class="label">Product Image</label>
            <input name="image" type="file" id="image" class="input-text" />
        </div>
        <div class="input-field">
            <label for="sku" class="label">Product SKU</label>
            <input name="sku" type="text" id="sku" class="input-text" />
        </div>
        <div class="input-field">
            <label for="name" class="label">Product Name</label>
            <input name="name" type="text" id="name" class="input-text" />
        </div>
        <div class="input-field">
            <label for="price" class="label">Price</label>
            <input name="price" type="number" step="0.01" id="price" class="input-text" />
        </div>
        <div class="input-field">
            <label for="quantity" class="label">Quantity</label>
            <input name="quantity" type="number" id="quantity" class="input-text" />
        </div>
        <div class="input-field">
            <label for="category" class="label">Categories</label>
            <select name="categories[]" multiple id="category" class="input-text">
                <?php foreach ($categories as $item): ?>
                    <option value="<?=$item->name?>"><?=$item->name?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="input-field">
            <label for="description" class="label">Description</label>
            <textarea name="description" id="description" class="input-text"></textarea>
        </div>
        <div class="actions-form">
            <a href="<?= $router->route("product.index") ?>" class="action back">Back</a>
            <input class="btn-submit btn-action" type="submit" value="Save Product" />
        </div>

    </form>
</main>