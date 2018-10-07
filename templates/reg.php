<?= include('top-nav.php'); ?>
<?php $class_name = isset($is_register) ? "form--invalid" :""; ?>
<form class="form container <?=$class_name;?>" action="" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <?php $class_name = isset($is_register['email']) ? "form__item--invalid" :"";
    $value = isset($reg['email']) ? $reg['email'] : ""; ?>
    <div class="form__item <?=$class_name;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="reg[email]" placeholder="Введите e-mail" value="<?=$value;?>">
        <span class="form__error">Введите e-mail</span>
    </div>
    <?php $class_name = isset($is_register['password']) ? "form__item--invalid" :"";
    $value = isset($reg['password']) ? $reg['password'] : ""; ?>
    <div class="form__item <?=$class_name;?>">
        <label for="password">Пароль*</label>
        <input id="password" type="password" name="reg[password]" placeholder="Введите пароль" value="<?=$value;?>">
        <span class="form__error">Введите пароль</span>
    </div>
    <?php $class_name = isset($is_register['name']) ? "form__item--invalid" :"";
    $value = isset($reg['name']) ? $reg['name'] : ""; ?>
    <div class="form__item <?=$class_name;?>">
        <label for="name">Имя*</label>
        <input id="name" type="text" name="reg[name]" placeholder="Введите имя" value="<?=$value;?>">
        <span class="form__error">Введите имя</span>
    </div>
    <?php $class_name = isset($is_register['contacts']) ? "form__item--invalid" :"";
    $value = isset($reg['contacts']) ? $reg['contacts'] : ""; ?>
    <div class="form__item <?=$class_name;?>">
        <label for="message">Контактные данные*</label>
        <textarea id="message" name="reg[contacts]" placeholder="Напишите как с вами связаться"><?=$value;?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>
    <div class="form__item form__item--file form__item--last">
        <label>Аватар</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="jpg_image" id="photo2" value="">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <!--    начало вставки-->
    <?php if (isset($is_register)): ?>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <ul>
            <?php foreach($is_register as $err => $val): ?>
                <li><strong><?=$dict[$err];?>:</strong> <?=$val;?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <!--    конец-->
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
