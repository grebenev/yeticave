<div class="lot-item__right">
    <div class="lot-item__state">
        <div class="lot-item__timer timer">
            10:54:12
        </div>
        <div class="lot-item__cost-state">
            <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?= $lot_data['start_price']; ?></span>
            </div>
            <div class="lot-item__min-cost">
                Мин. ставка <span><?= $lot_data['lot_step']; ?></span>
            </div>
        </div>
        <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
            <p class="lot-item__form-item">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="number" name="cost" placeholder="<?= $lot_data['lot_step']; ?>">
            </p>
            <button type="submit" class="button">Сделать ставку</button>
        </form>
    </div>
    <div class="history">
        <h3>История ставок (<span>10</span>)</h3>
        <table class="history__list">
            <?php foreach ($bet_list as $key): ?>
                <tr class="history__item">
                    <td class="history__name"><?= $key['user_name']; ?></td>
                    <td class="history__price"><?= $key['amount']; ?></td>
                    <td class="history__time"><?= $key['bet_date']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
