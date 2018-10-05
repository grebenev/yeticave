<?= include_once('top-nav.php'); ?>
<section class="lot-item container">
    <h2><?= $lot_data['lot_name']; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="img/<?= $lot_data['image']; ?>" width="730" height="548" alt="<?= $lot_data['lot_name']; ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot_data['category_name']; ?></span></p>
            <p class="lot-item__description"><?= $lot_data['description']; ?></p>
        </div>
<!--        --><?//= $lot_aside_content; ?>
    </div>
</section>
