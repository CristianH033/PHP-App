<?php
$new ??= [];

/** @var NewsApp\Core\Template $this */
$this->layout('layout', ['title' => $this->escape($new['title'])]);
?>

<h1><?= $this->escape($new['title']) ?></h1>

<p><?= $this->escape($new['content']) ?></p>

<span>Author: <?= $this->escape($new['author']) ?></span>