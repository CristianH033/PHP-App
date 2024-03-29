<?php

/** @var League\Plates\Template\Template $this */
$title ??= 'Internal Server Error';
$message ??= 'An internal server error occurred.';
$trace ??= '';
$this->layout('layout', ['title' => $this->e($title)]);
?>

<h1><?= $this->e($title) ?></h1>
<p><?= $this->e($message) ?></p>
<?php if (config('app.debug', false)) { ?>
    <pre><?= $this->e($trace) ?></pre>
<?php } ?>