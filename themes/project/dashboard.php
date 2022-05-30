<?php $this->layout("_theme"); ?>
<main class="content">
    <div class="header-list-page">
        <h1 class="title">Dashboard</h1>
    </div>
    <div class="infor">
        You have 4 products added on this store: <a href="<?= $router->route("product.create") ?>" class="btn-action">Add new Product</a>
    </div>
    <ul class="product-list">
        <?php foreach ($products as $item): ?>
        <li>
            <a href="<?= url('/produto/edit/'.$item->id) ?>">
                <div class="product-image">
                    <img src="<?=url("storage/" . $item->image())?>" layout="responsive" width="164" height="145"
                         alt="Tênis Runner Bolt"/>
                </div>
            </a>
            <div class="product-info">
                <div class="product-name"><span><?=$item->name?></span></div>
                <div class="product-price"><span class="special-price"><?=number_fmt($item->quantity, 0)?> available</span> <span>R$<?=str_price($item->price)?></span></div>
            </div>
        </li>
        <?php endforeach;?>
    </ul>
</main>