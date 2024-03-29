<?php

/** @var League\Plates\Template\Template $this */
$title ??= 'Simple Page';
$content ??= 'Hello World!';
$this->layout('layout', ['title' => $this->e($title)]);
?>

<h1><?= $this->e($title) ?></h1>
<p><?= $this->e($content) ?></p>