Navbar User Example
=======================

## Default Value

```
<?php use igor162\nav\NavbarUser; ?>

<!-- NavbarUser user panel -->
<?= NavbarUser::widget() ?>
```

## Custom Value

```
<?php use igor162\nav\NavbarUser; ?>

<!-- NavbarUser user panel -->
<?= NavbarUser::widget([
            'userName' => \Yii::$app->user->identity->fullName,
            'userGender' => \Yii::$app->user->identity->gender,
    //        'toggleUserName' => false,
    //        'toggleUsedImg' => true,
    //        'userPatchImg' => false,
    //        'smallShow' => 'new',
    /*        'smallShow' => [
                'label' => 44,
                'color' => ColorCSS::BG_FUCHSIA,
                //'class' => 'label pull-right',
            ],*/
    //        'dataHeaderName' => \Yii::$app->user->identity->fullName,
            'dataHeaderSmall' => 'Создан: ' . date("d-m-Y H:m",\Yii::$app->user->identity->created_at),
            'panelBody' => [
                ['label' => 'Подписчики', 'link' => '/user/followers', 'class' => "col-xs-4 text-center"],
                ['label' => 'Продажи', 'link' => '/user/sales', 'class' => 'col-xs-4 text-center'],
                ['label' => 'Друзья', 'link' => '/user/friends', 'class' => 'col-xs-4 text-center']
            ],
            'panelFooter' =>
                [
                    'labelProfile' => \Yii::t('app', 'Profile'),
                    'linkProfile' => Url::to('/user/profile'),
                    'classProfile' => "btn btn-default btn-flat",
    
                    'labelSignOut' => \Yii::t('app', 'Log out'),
                    'linkSignOut' => Url::to('/site/logout'),
                    'classSignOut' => 'btn btn-default btn-flat'
                ]
        ]) ?>
```
