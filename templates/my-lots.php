<?php include_once('top-nav.php'); ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($bet_list as $key):
                if($key['winners_id'] == $user_id) {
                    $class_name = "rates__item--win";
                } else if (time_to_end($key['end_date']) <= 0) {
                    $class_name = "rates__item--end";
                } else {
                    $class_name = "";
                };?>
            <tr class="rates__item <?= $class_name; ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="img/<?=$key['image']; ?>" width="54" height="40" alt="<?=$key['lot_name']; ?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="/lot.php?lot=<?=$key['lot_id']; ?>"><?=$key['lot_name']; ?></a></h3>
                        <?php if ($key['winners_id'] == $user_id): ?>
                            <p><?=$key['contacts']; ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?=$key['category_name']; ?>
                </td>
                <td class="rates__timer">
                    <?php if ($key['winners_id'] == $user_id): ?>
                    <div class="timer timer--win">Ставка выиграла</div>
                    <?php else: ?>
                      <?php if (time_to_end($key['end_date']) > 0) : ?>
                    <div class="timer"><?=time_to_end($key['end_date']); ?></div>
                            <?php else : ?>
                     <div class="timer timer--end">Торги окончены</div>

                    <?php endif;
                    endif; ?>
                </td>
                <td class="rates__price">
                    <?=transform_format($key['amount']); ?>
                </td>
                <?php $class_name = ($key['winners_id'] == $user_id) ? "rates__item--win" : "";?>
                <td class="rates__time">
                    <?= time_left($key['bet_date']); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

