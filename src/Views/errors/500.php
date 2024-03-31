<?php

$exceptionTitle ??= 'Internal Server Error';
$exceptionName ??= 'Internal Server Error';
$errorMessage ??= 'An internal server error occurred.';
$trace ??= '';

/** @var NewsApp\Core\Template $this */
$this->layout('layout', ['title' => $this->escape($exceptionTitle)]);
?>

<h1><?= $this->escape($exceptionName) ?></h1>
<p><?= $this->escape($errorMessage) ?></p>
<?php if (config('app.debug', false)) { ?>
    <pre><?= $this->escape($trace) ?></pre>
<?php } ?>