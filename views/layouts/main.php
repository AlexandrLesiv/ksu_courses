<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use app\widgets\BreadcrumbsWidget;
use yii\bootstrap5\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Yii::$app->name ?> - <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body itemscope itemtype="http://schema.org/WebPage" class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header" class="header">
        <h1 class="brand" itemscope itemtype="http://schema.org/Organization" class="header__brand">
            <a href="<?= Yii::$app->urlManager->createUrl(['/']) ?>" itemprop="url" title="Логотип <?= Yii::$app->name ?>">
                <i class="bx bxs-graduation" itemprop="logo"></i> <span itemprop="name"><?= Yii::$app->name ?></span>
            </a>
        </h1>
    </header>

    <nav class="nav" id="nav">
        <div class="hamburger" title="Меню">
            <span class="hamburger__ham hamburger__ham--top"></span>
            <span class="hamburger__ham hamburger__ham--middle"></span>
            <span class="hamburger__ham hamburger__ham--bottom"></span>
        </div>

        <ul class="nav__list">
            <li class="nav__item">
                <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/site/index']) ?>">
                    <span>Головна</span>
                    <i class="bx bx-home"></i>
                </a>
            </li>
            <li class="nav__item">
                <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/site/contact']) ?>">
                    <span>Контакти</span>
                    <i class="bx bx-envelope"></i>
                </a>
            </li>
            <li class="nav__item">
                <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/site/policy']) ?>">
                    <span>Наша політика</span>
                    <i class="bx bx-check-shield"></i>
                </a>
            </li>
            <?php if (Yii::$app->user->isGuest) : ?>
                <li class="nav__header">Кабінет</li>
                <li class="nav__item">
                    <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/site/login']) ?>">
                        <span>Авторизація</span>
                        <i class="bx bx-log-in-circle"></i>
                    </a>
                </li>
                <li class="nav__item">
                    <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/site/sign-up']) ?>">
                        <span>Реєстрація</span>
                        <i class="bx bx-user-circle"></i>
                    </a>
                </li>
            <?php else : ?>
                <li class="nav__item">
                    <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/user/profile']) ?>">
                        <span>Мій профіль</span>
                        <i class="bx bx-user-circle"></i>
                    </a>
                </li>
                <?php if (Yii::$app->user->can('admin')) : ?>
                    <li class="nav__header">Користувачі</li>
                    <li class="nav__item">
                        <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/user/teachers']) ?>">
                            <span>Викладачі</span>
                            <i class="bx bx-user-voice"></i>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/user/students']) ?>">
                            <span>Здобувачі освіти</span>
                            <i class="bx bx-user-check"></i>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/user/banned']) ?>">
                            <span>Чорний список</span>
                            <i class="bx bx-lock"></i>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/content/index']) ?>">
                            <span>Контент</span>
                            <i class="bx bx-book-content"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('teacher')) : ?>
                    <li class="nav__header">Навчальні курси</li>
                    <li class="nav__item">
                        <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/courses/index']) ?>">
                            <span>Мої курси</span>
                            <i class="bx bx-food-menu"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('student')) : ?>
                    <li class="nav__header">Навчання</li>
                    <li class="nav__item">
                        <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/catalogue/index']) ?>">
                            <span>Всі курси</span>
                            <i class="bx bx-customize"></i>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/catalogue/favorites']) ?>">
                            <span>Обрані курси</span>
                            <i class="bx bxs-star-half"></i>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/catalogue/requests']) ?>">
                            <span>Мої запити</span>
                            <i class="bx bx-mail-send"></i>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/catalogue/my-courses']) ?>">
                            <span>Мої курси</span>
                            <i class="bx bx-bar-chart-alt-2"></i>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav__header">Інше</li>
                <li class="nav__item">
                    <a class="nav__link" href="<?= Yii::$app->urlManager->createUrl(['/site/logout']) ?>">
                        <span>Вийти</span>
                        <i class="bx bx-log-out-circle"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <main class="main flex-shrink-0" itemscope itemprop="mainContentOfPage" role="main">
        <div class="main__container">
            <div class="container-fluid">
                <?= BreadcrumbsWidget::widget([
                    'options' => [
                        'class' => 'breadcrumb',
                    ],
                    'homeLink' => [
                        'label' => 'Головна',
                        'url' => ['/'],
                        'class' => 'home',
                        'template' => '<li>{link}</li>',
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'itemTemplate' => '<li>{link}</li>',
                    'activeItemTemplate' => '<li class="active">{link}</li>',
                    'tag' => 'ul',
                    'encodeLabels' => false
                ]);
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container-fluid">
            <small class="footer__copy">&copy; <?= '<span itemprop="copyrightYear">' . date('Y') . '</span> <span itemprop="copyrightHolder" itemscope itemtype="http://schema.org/Person">' . Html::a(Yii::$app->name, Yii::$app->homeUrl, ['itemprop' => 'name']) ?></span> - Всі права захищено</small>
            <p class="footer__author"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>