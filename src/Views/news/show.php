<?php
$new ??= [];

/** @var League\Plates\Template\Template $this */
$this->layout('layout', ['title' => $this->e($new['title'])]);
?>

<h1><?= $this->e($new['title']) ?></h1>

<p><?= $this->e($new['content']) ?></p>

<span>Author: <?= $this->e($new['author']) ?></span>