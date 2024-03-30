<?php

$exceptionTitle ??= 'Not Found';
$exceptionName ??= 'Not Found';
$errorMessage ??= 'The requested page could not be found.';
$trace ??= '';

/** @var League\Plates\Template\Template $this */
$this->layout('layout', ['title' => $this->e($exceptionTitle)]);
?>

<h1><?= config('app.debug', false) ? $this->e($exceptionName) : 'Not Found' ?></h1>
<p><?= $this->e($errorMessage) ?></p>
<?php if (config('app.debug', false)) { ?>
    <pre><?= $this->e($trace) ?></pre>
<?php } ?>