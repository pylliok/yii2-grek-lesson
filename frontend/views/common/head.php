<? use yii\bootstrap\Nav; ?>
<!-- Header Starts -->
<div class="navbar-wrapper">

    <div class="navbar-inverse" role="navigation">
        <div class="container">
            <div class="navbar-header">


                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>


            <!-- Nav Starts -->
            <div class="navbar-collapse  collapse">
                <?

                $menuItems = [
                    ['label' => 'Home' , 'url' => '#', 'options' => ['class' =>  'active']],
                    ['label' => 'Feedback' , 'url' => Yii::$app->urlManager->createUrl('main/main/contact')],
                    ['label' => 'Register' , 'url' =>  Yii::$app->urlManager->createUrl('main/main/register')],
                    ['label' => 'Login' , 'url' => Yii::$app->urlManager->createUrl('main/main/login')],
                ];
                if(!Yii::$app->user->isGuest)
                {
                    $menuItems = [
                    ['label' => 'Home' , 'url' => '#', 'options' => ['class' =>  'active']],
                        ['label' => 'Feedback' , 'url' => Yii::$app->urlManager->createUrl('main/main/contact')],
                    ['label' => 'Logout(' . Yii::$app->user->identity->name . ')', 'url' => Yii::$app->urlManager->createUrl('main/main/logout')],
                    ];
                   // $menuItems[] = ['label' => 'Logout(' . Yii::$app->user->identity->name . ')', 'url' => Yii::$app->urlManager->createUrl('main/main/logout')];
                }
                ?>

                <?=Nav::widget([
                    'options' => ['class' => 'nav navbar-nav navbar-right'],
                    'items' => $menuItems
                ]);
                ?>

            </div>
            <!-- #Nav Ends -->

        </div>
    </div>

</div>
<!-- #Header Starts -->





<div class="container">

    <!-- Header Starts -->
    <div class="header">
        <a href="<?=\Yii::$app->urlManager->createUrl('')?>" ><img src="/images/logo.png"  alt="Realestate"></a>

        <ul class="pull-right">
            <li><a href="buysalerent.html" >Buy</a></li>
            <li><a href="buysalerent.html" >Sale</a></li>
            <li><a href="buysalerent.html" >Rent</a></li>
        </ul>
    </div>
    <!-- #Header Starts -->
</div>