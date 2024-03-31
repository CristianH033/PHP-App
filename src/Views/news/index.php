<?php
$title ??= 'News';
$news ??= [];

/** @var NewsApp\Core\Template $this */
$this->layout('layout', ['title' => $this->escape($title)]);
?>

<h1><?= $this->escape($title) ?></h1>
<?php foreach ($news as $article) : ?>
    <div class="article">
        <h2><?= $this->escape($article['title']) ?></h2>
        <p><?= $this->escape($article['content']) ?></p>
        <a href="<?= "/news/{$this->escape($article['id'])}" ?>">Leer mas</a>
    </div>
<?php endforeach; ?>