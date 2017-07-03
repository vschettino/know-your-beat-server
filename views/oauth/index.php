<?php
/* @var $this yii\web\View */
?>
<h1>Welcome to KnowYourBeat</h1>

<p>
    Thanks for the access! Now you can go back to the application.
</p>

<a class='btn btn-success' href='<?= 'http://localhost/profile?code='. $user->access_token; ?>'>Let's browse</a>
