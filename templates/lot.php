<?= include_once('top-nav.php'); ?>
<section class="lot-item container">
    <h2><?= htmlspecialchars($lot_data['lot_name']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="img/<?= $lot_data['image']; ?>" width="730" height="548" alt="<?= htmlspecialchars($lot_data['lot_name']); ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot_data['category_name']; ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($lot_data['description']); ?></p>
        </div>
        <div class="lot-item__right">
            <?php if (isset($_SESSION['user'])):?>
              <?= $lot_aside_content; ?>
            <?php endif;?>
        </div>
    </div>
</section>
