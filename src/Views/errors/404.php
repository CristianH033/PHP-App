<?php

$exceptionTitle ??= 'Not Found';
$exceptionName ??= 'Not Found';
$errorMessage ??= 'The requested page could not be found.';
$trace ??= '';

/** @var NewsApp\Core\Template $this */
$this->layout('layout', ['title' => $this->escape($exceptionTitle)]);
?>

<h1><?= config('app.debug', false) ? $this->escape($exceptionName) : 'Not Found' ?></h1>
<p><?= $this->escape($errorMessage) ?></p>
<?php if (config('app.debug', false)) { ?>
    <pre><?= $this->escape($trace) ?></pre>
<?php } ?>