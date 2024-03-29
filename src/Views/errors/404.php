<?php

/** @var League\Plates\Template\Template $this */
$title ??= 'Not Found';
$message ??= 'The requested page could not be found.';
$trace ??= '';
$this->layout('layout', ['title' => $this->e($title)]);
?>

<h1><?= $this->e($title) ?></h1>
<p><?= $this->e($message) ?></p>
<?php if (config('app.debug', false)) { ?>
    <pre><?= $this->e($trace) ?></pre>
<?php } ?>