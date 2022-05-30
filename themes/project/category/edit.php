<?php $this->layout("_theme"); ?>
<main class="content">
    <h1 class="title new-item">New Category</h1>

    <form action="<?=$router->route('category.update')?>" method="post">
        <input type="hidden" name="id" value="<?=$category->id?>">
        <div class="input-field">
            <label for="category-name" class="label">Category Name</label>
            <input name="name" type="text" id="category-name" class="input-text" value="<?=$category->name?>" />

        </div>
        <div class="actions-form">
            <a href="<?= $router->route("category.index") ?>" class="action back">Back</a>
            <input class="btn-submit btn-action"  type="submit" value="Save" />
        </div>
    </form>
</main>

<?= flash() ?>