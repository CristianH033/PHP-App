<?php
$title ??= 'News';
$news ??= [];

/** @var League\Plates\Template\Template $this */
$this->layout('layout', ['title' => $this->e($title)]);
?>

<h1><?= $this->e($title) ?></h1>
<?php foreach ($news as $article) : ?>
    <div class="article">
        <h2><?= $this->e($article['title']) ?></h2>
        <p><?= $this->e($article['content']) ?></p>
        <a href="<?= "/news/{$this->e($article['id'])}" ?>">Leer mas</a>
    </div>
<?php endforeach; ?>