<?php include('top-nav.php'); ?>
<?php $class_name = isset($errors) ? "form--invalid" :""; ?>
<form class="form container <?=$class_name;?>" action="" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <?php $class_name = isset($errors['email']) ? "form__item--invalid" :"";
        $value = isset($reg['email']) ? $reg['email'] : ""; ?>
    <div class="form__item <?=$class_name;?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="login[email]" placeholder="Введите e-mail" value="<?=$value;?>">
        <span class="form__error">Введите e-mail</span>
    </div>
    <?php $class_name = isset($errors['password']) ? "form__item--invalid" :"";
        $value = isset($reg['password']) ? $reg['password'] : ""; ?>
    <div class="form__item form__item--last <?=$class_name;?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="login[password]" placeholder="Введите пароль" value="<?=$value;?>">
        <span class="form__error">Введите пароль</span>
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
    <button type="submit" class="button">Войти</button>
</form>
