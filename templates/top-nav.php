<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories_list as $key): ?>
            <li class="nav__item">
                <a href="all-lots.php?category=<?=$key['id']; ?>"><?= $key['category_name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
