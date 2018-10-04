<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories_list as $key): ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= $key['category_name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
