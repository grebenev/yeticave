<?php include('top-nav.php'); ?>
<?php $class_name = isset($errors) ? "form--invalid" :""; ?>
<form class="form form--add-lot container <?=$class_name;?>" action="" method="post" enctype="multipart/form-data"> <!-- form--invalid
 -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $class_name = isset($errors['name']) ? "form__item--invalid" :"";
            $value = isset($lot['name']) ? $lot['name'] : ""; ?>
        <div class="form__item <?=$class_name;?>">
            <label for="lot-name">Наименование</label>
            <input id="lot-name " type="text" name="lot[name]" placeholder="Введите наименование лота" value="<?=$value;?>"required>
            <span class="form__error">Введите наименование лота</span>
        </div>
        <?php $class_name = isset($errors['category']) ? "form__item--invalid" :"";
            $value = isset($lot['category']) ? $lot['category'] : ""; ?>
        <div class="form__item <?=$class_name;?>">
            <label for="category">Категория</label>
            <select id="category" name="lot[category]" required>
                <option value=""> Выбирете категорию</option>
              <?php foreach ($categories_list as $key): ?>
                  <option value="<?= $key['id'] ?>" <?= (isset($lot) and isset($lot['category'])
                      and $lot['category'] == $key['id']) ? 'selected' : '' ?>><?= $key['category_name']; ?></option>
              <?php endforeach; ?>
            </select>
            <span class="form__error">Выберите категорию</span>
        </div>
    </div>
    <?php $class_name = isset($errors['message']) ? "form__item--invalid" :"";
        $value = isset($lot['message']) ? $lot['message'] : ""; ?>
    <div class="form__item form__item--wide <?=$class_name;?>">
        <label for="message">Описание</label>
        <textarea id="message" name="lot[message]" placeholder="Напишите описание лота" required><?=$value;?></textarea>
        <span class="form__error">Напишите описание лота</span>
    </div>
    <div class="form__item form__item--file"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="jpg_image" id="photo2" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <?php $class_name = isset($errors['price']) ? "form__item--invalid" :"";
            $value = isset($lot['price']) ? $lot['price'] : ""; ?>
        <div class="form__item form__item--small <?=$class_name;?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot[price]" placeholder="0" value="<?=$value;?>" required>
            <span class="form__error">Введите начальную цену</span>
        </div>
        <?php $class_name = isset($errors['step']) ? "form__item--invalid" :"";
            $value = isset($lot['step']) ? $lot['step'] : ""; ?>
        <div class="form__item form__item--small <?=$class_name;?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot[step]" placeholder="0" value="<?=$value;?>" required>
            <span class="form__error">Введите шаг ставки</span>
        </div>
        <?php $class_name = isset($errors['date']) ? "form__item--invalid" :"";
            $value = isset($lot['date']) ? $lot['date'] : ""; ?>
        <div class="form__item <?=$class_name;?>">
            <label for="lot-date">Дата окончания торгов</label>
            <input class="form__input-date" id="lot-date" type="date" name="lot[date]"value="<?=$value;?>" required>
            <span class="form__error">Введите дату завершения торгов</span>
        </div>
    </div>
<!--    начало вставки-->
    <?php if (isset($errors)): ?>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <ul>
            <?php foreach($errors as $err => $val): ?>
                <li><strong><?=$dict[$err];?>:</strong> <?=$val;?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<!--    конец-->
    <button type="submit" class="button">Добавить лот</button>
</form>
