<?php

/** @var string $content */

use app\widgets\Alert;
use yii\widgets\Breadcrumbs;

?>
<main role="main" class="d-flex flex-nowrap" >
    <div class="container">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>

        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left" style="margin-left: 400px">&copy; My Company <?= date('Y') ?></p>

    </div>
</footer>