<?php

$exceptionTitle ??= 'Internal Server Error';
$exceptionName ??= 'Internal Server Error';
$errorMessage ??= 'An internal server error occurred.';
$trace ??= '';

/** @var League\Plates\Template\Template $this */
$this->layout('layout', ['title' => $this->e($exceptionTitle)]);
?>

<h1><?= $this->e($exceptionName) ?></h1>
<p><?= $this->e($errorMessage) ?></p>
<?php if (config('app.debug', false)) { ?>
    <pre><?= $this->e($trace) ?></pre>
<?php } ?>