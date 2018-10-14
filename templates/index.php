<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
        снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories_list as $key): ?>
            <li class="promo__item  promo__item--<?= $key['class_name']; ?>">
                <a class="promo__link" href="all-lots.php?category=<?= $key['id']; ?>"><?= $key['category_name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <!--заполните этот список из массива с товарами-->
        <?php foreach ($lots_list as $key):
            $spend_time = strtotime($key['end_date']);
            if (time() < $spend_time): ?>

                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="img/<?= $key['image']; ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $key['category_name']; ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="lot.php?lot=<?= htmlspecialchars($key['id']); ?>"><?= htmlspecialchars($key['lot_name']); ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span
                                    class="lot__cost"><?= transform_format(htmlspecialchars($key['start_price'])); ?></span>
                            </div>
                            <div class="lot__timer timer"><?= time_to_end($key['end_date']); ?>

                            </div>
                        </div>
                    </div>
                </li>
            <?php endif;
        endforeach; ?>
    </ul>
</section>
