<?php include_once('top-nav.php'); ?>
<section class="lot-item container">
    <h2><?= htmlspecialchars($lot_data['lot_name']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="img/<?= $lot_data['image']; ?>" width="730" height="548"
                     alt="<?= htmlspecialchars($lot_data['lot_name']); ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= $lot_data['category_name']; ?></span></p>
            <p class="lot-item__description"><?= htmlspecialchars($lot_data['description']); ?></p>
        </div>
        <div class="lot-item__right">
            <?php if (isset($_SESSION['user'])):
                $spend_time = strtotime($lot_data['end_date']);
                if ($lot_data['users_id'] !== $_SESSION['user']['id'] && !$total_count && time() < $spend_time):?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer">
                            <?= time_to_end($lot_data['end_date']) ?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= htmlspecialchars($current_price); ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= htmlspecialchars($lot_data['lot_step']); ?></span>
                            </div>
                        </div>
                        <form class="lot-item__form" action="" method="post">
                            <p class="lot-item__form-item">
                                <label for="cost">Ваша ставка</label>
                                <?php $placeholder = isset($error_bet) ? $error_bet : htmlspecialchars($lot_data['lot_step'] + $current_price); ?>
                                <input id="cost" type="text" name="cost" placeholder="<?= $placeholder; ?>">
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    </div>
                <?php endif; ?>
                <div class="history">
                    <?php $bet_count = count($bet_list); ?>
                    <h3>История ставок (<span><?= $bet_count; ?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bet_list as $key): ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($key['user_name']); ?></td>
                                <td class="history__price"><?= htmlspecialchars(transform_format($key['amount'])); ?></td>
                                <td class="history__time"><?= time_left($key['bet_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
