  <main>
    <nav class="nav">
      <ul class="nav__list container">
        <?php foreach ($categories_list as $key): ?>
            <li class="nav__item">
              <a href="all-lots.html"><?=$key['category_name']; ?></a>
            </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <section class="lot-item container">
      <h2><?=$lot_data['lot_name']; ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="img/<?=$lot_data['image']; ?>" width="730" height="548" alt="<?=$lot_data['lot_name']; ?>">
          </div>
          <p class="lot-item__category">Категория: <span><?=$lot_data['category_name']; ?></span></p>
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
        <div class="lot-item__right">
          <div class="lot-item__state">
            <div class="lot-item__timer timer">
10:54:12
</div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=$lot_data['start_price']; ?></span>
              </div>
              <div class="lot-item__min-cost">
Мин. ставка <span><?=$lot_data['lot_step']; ?></span>
              </div>
            </div>
            <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
              <p class="lot-item__form-item">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="number" name="cost" placeholder="<?=$lot_data['lot_step']; ?>">
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <div class="history">
            <h3>История ставок (<span>10</span>)</h3>
            <table class="history__list">
              <?php foreach ($bet_list as $key): ?>
                  <tr class="history__item">
                    <td class="history__name"><?=$key['user_name']; ?></td>
                    <td class="history__price"><?=$key['amount']; ?></td>
                    <td class="history__time"><?=$key['bet_date']; ?></td>
                  </tr>
              <?php endforeach; ?>
            </table>
          </div>
        </div>
      </div>
    </section>
  </main>
