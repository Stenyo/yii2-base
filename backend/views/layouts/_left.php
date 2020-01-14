<?php

use cebe\gravatar\Gravatar;
use dmstr\widgets\Menu;

?>

<aside class="main-sidebar">
    <section class="sidebar">
        <?php if (false) : ?>
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                </div>
            </form>
        <?php endif; ?>

        <?= Menu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => [
                [
                    'label' => Yii::t('app','Usuários'),
                    'icon' => 'users',
                    'url' => ['user/index'],
                ],

                [
                    'label' => 'Menu Yii2',
                    'icon' => 'file-code-o',
                    'url' => 'javascript:;',
                    'visible' => YII_ENV_DEV,
                    'items' => [
                        [
                            'label' => 'Gii',
                            'icon' => 'file-code-o',
                            'url' => ['/gii'],

                        ],
                        [
                            'label' => 'Debug',
                            'icon' => 'dashboard',
                            'url' => ['/debug'],
                        ],
                    ],
                ],

            ],
        ]) ?>

    </section>

</aside>
