<?= include_once('top-nav.php'); ?>
<section class="lot-item container">
    <h2><?= $lot_data['lot_name']; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="img/<?= $lot_data['image']; ?>" width="730" height="548" alt="<?= $lot_data['lot_name']; ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot_data['category_name']; ?></span></p>
            <p class="lot-item__description">Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив
                снег
                мощным щелчкоми четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот
                снаряд
                отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом
                кэмбер
                позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется,
                просто
                посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла
                равнодушным.</p>
        </div>
<!--        --><?//= $lot_aside_content; ?>
    </div>
</section>
