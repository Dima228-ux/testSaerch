<?php
/**
 * @var $search Search
 * @var $pg ArrayDataProvider
 */

use app\models\ArrayPaginator;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use app\models\Search;
use yii\bootstrap4\LinkPager;
use yii\bootstrap4\Modal;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

//foreach ($search['json_result']['items'] as $result){
//    var_dump($result);exit;
//
//}


?>
<div class="row">
    <div class="col-xs-12 col-md-10">

        <div class="card border-success">
            <!-- card-header -->
            <div class="card-header bg-success text-white">
                Search Project
            </div>
            <!-- card-body -->
            <div class="card-body">
                <!-- HTML форма -->
                <div class="form-group">
                    <?= Html::beginForm('search-project', 'post', ['role' => 'search']) ?>
                    <div class="input-group">
                        <?= Html::textInput('search_string', '', ['class' => 'form-control', 'placeholder' => 'Find a project?']) ?>
                        <span class="input-group-btn">
 <?= Html::submitButton('Search', ['class' => 'btn btn-mini btn-success']) ?>
                </span>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-10">
        <div class="box box-body box-success">
            <div class="box-header">
                <h3 class="box-title">Search projects</h3>
            </div>
            <div class="box-body no-padding">
                <?php if (empty($search)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No rooms was found</td>
                    </tr>
                <?php else: ?>

                        <?php foreach ($search['json_result']['items'] as $result): ?>
                            <div class="card border-primary" onclick="location.href='<?php echo $result["clone_url"] ?>'" style="width: 18rem;" >
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $result['name'] ?></h5>
                                    <p class="card-text">
                                        <br>Auth: <?php echo $result["owner"]['login'] ?> </br>
                                        <br> Count Stars: <?php echo $result["stargazers_count"] ?> </br>
                                        <br> Count watchers:<?php echo $result['watchers_count'] ?> </br>
                                    </p>

                                </div>
                            </div>
                        <?php endforeach; ?>


                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" >
 $("div").click(function(){
window.location=$(this).find("a").attr("href"); return false;
});
</script>