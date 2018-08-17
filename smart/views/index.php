<?php
/**
 * @var $this \smart\Application
 */
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Smart - панель управления сайтом</title>
        <link rel="stylesheet" href="<?= $this->getBaseUrl() ?>/smart/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= $this->getBaseUrl() ?>/smart/css/fontawesome-all.min.css">
        <link rel="stylesheet" href="<?= $this->getBaseUrl() ?>/smart/css/alert-notify.min.css">
        <link rel="stylesheet" href="<?= $this->getBaseUrl() ?>/smart/css/main.css">
    </head>
    <body>
        <div class="lightbox"><div class="loader_img"></div></div>
        <header>
            <div class="pull-left">Smart - панель управления сайтом</div>
            <div class="pull-right">
                <a href="<?= $this->getBaseUrl() . '/smart/logout' ?>">Выход</a>
            </div>
        </header>
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="cl-tab-container">
                        <div class="col-md-3">
                            <ul class="cl-tab-nav">
                                <li class="active"><a data-toggle="tab" href="#panel1"><i class="fal fa-home"></i><span>Основные</span></a></li>
                                <li><a href="#panel2"><i class="fal fa-info"></i><span>Мета-теги</span></a></li>
                                <li><a href="#panel3"><i class="fal fa-share-square"></i><span>Open Graph</span></a></li>
                                <li><a href="#panel4"><i class="fal fa-chart-bar"></i><span>Метрики</span></a></li>
                                <li><a href="#panel5"><i class="fal fa-file-image"></i><span>Favicon</span></a></li>
                                <li><a href="#panel6"><i class="fal fa-robot"></i><span>Robots.txt</span></a></li>
                                <li><a href="#panel7"><i class="fal fa-code"></i><span>Код</span></a></li>
                                <li><a href="#panel8"><i class="fal fa-file-code"></i><span>Шаблоны</span></a></li>
                            </ul>
                        </div>
                        <div class="col-md-9 cl-tab-content">
                            <div class="tab-content">
                                <div id="panel1" class="tab-pane fade in active">
                                    <form id="main-form" action="<?= $this->getBaseUrl().'/smart/handler' ?>" method="post">
                                        <div class="cl-form-group">
                                            <label class="cl-label" for="title">Название сайта</label>
                                            <input type="text" placeholder="Пример: My Awesome Site" class="cl-input" name="title" id="title" value="<?= $this->getParam('title') ?>">
                                        </div>
                                        <div class="cl-form-group">
                                            <label class="cl-label" for="charset">Кодировка сайта</label>
                                            <input type="text" placeholder="Пример: UTF-8" class="cl-input" name="charset" id="charset" value="<?= $this->getParam('charset') ?>">
                                        </div>
                                        <div class="cl-form-group">
                                            <label class="cl-label" for="phone">Номер телефона</label>
                                            <input type="text" placeholder="Пример: 8(888)888-88-88" class="cl-input" name="phone" id="phone" value="<?= $this->getParam('phone') ?>">
                                        </div>
                                        <div class="cl-form-group">
                                            <label class="cl-label" for="email">E-mail адрес для получения уведомлений с сайта</label>
                                            <input type="text" placeholder="Пример: my_email@mail.ru" class="cl-input" name="email" id="email" value="<?= $this->getParam('email') ?>">
                                        </div>
                                        <button type="button" class="btn btn-main" onclick="saveMainTab();">Сохранить</button>
                                    </form>
                                </div>
                                <div id="panel2" class="tab-pane fade">
                                    <form id="meta-tag-form" action="<?= $this->getBaseUrl().'/smart/handler' ?>" method="post">
                                        <div class="row">
                                            <div class="col-md-5"><label class="cl-label" for="">Название мета-тега</label></div>
                                            <div class="col-md-5"><label class="cl-label" for="">Значение мета-тега</label></div>
                                        </div>
                                        <div class="block-meta">
                                            <?php if (empty($this->getParam('meta-tags'))) { ?>
                                                <div class="block-meta-item">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="cl-form-group">
                                                                <input type="text" placeholder="Пример: description" class="cl-input" name="name">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="cl-form-group">
                                                                <input type="text" placeholder="Пример: smart-описание" class="cl-input" name="value">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 text-center">
                                                            <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <?php foreach ($this->getParam('meta-tags') as $name => $value) { ?>
                                                    <div class="block-meta-item">
                                                        <div class="row">
                                                            <input type="hidden" name="type" value="meta_tags">
                                                            <div class="col-md-5">
                                                                <div class="cl-form-group">
                                                                    <input type="text" placeholder="Пример: description" class="cl-input" name="name" value="<?= $name ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="cl-form-group">
                                                                    <input type="text" placeholder="Пример: smart-описание" class="cl-input" name="value" value="<?= $value ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 text-center">
                                                                <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-10 text-center">
                                                <a href="#" class="meta-plus" onclick="addMetaBlock(this);"><i class="fal fa-plus-circle"></i></a>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-main" onclick="saveMetaTagTab();">Сохранить</button>
                                        <button type="button" class="btn btn-default" onclick="tagsPreview()">Просмотр</button>
                                        <button type="button" class="btn btn-default" onclick="cancel('meta-tags')">Отмена</button>
                                    </form>
                                </div>
                                <div id="panel3" class="tab-pane fade">
                                    <form id="og-tag-form" action="<?= $this->getBaseUrl().'/smart/handler' ?>" method="post">
                                        <div class="row">
                                            <div class="col-md-5"><label class="cl-label" for="">Название мета-тега</label></div>
                                            <div class="col-md-5"><label class="cl-label" for="">Значение мета-тега</label></div>
                                        </div>
                                        <div class="block-meta">
                                            <?php if (empty($this->getParam('og-tags'))) { ?>
                                                <div class="block-meta-item">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="cl-form-group">
                                                                <input type="text" placeholder="Пример: og:title" class="cl-input" name="name">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="cl-form-group">
                                                                <input type="text" placeholder="Пример: smart" class="cl-input" name="value">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 text-center">
                                                            <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <?php foreach ($this->getParam('og-tags') as $name => $value) { ?>
                                                    <div class="block-meta-item">
                                                        <div class="row">
                                                            <input type="hidden" name="type" value="meta_tags">
                                                            <div class="col-md-5">
                                                                <div class="cl-form-group">
                                                                    <input type="text" placeholder="Пример: og:title" class="cl-input" name="name" value="<?= $name ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="cl-form-group">
                                                                    <input type="text" placeholder="Пример: smart" class="cl-input" name="value" value="<?= $value ?>">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 text-center">
                                                                <a href="#" class="meta-times" onclick="removeMetaBlock(this);"><i class="fal fa-times-circle"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-10 text-center">
                                                <a href="#" class="meta-plus" onclick="addMetaBlock(this, 'og-tags');"><i class="fal fa-plus-circle"></i></a>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-main" onclick="saveOGTagTab();">Сохранить</button>
                                        <button type="button" class="btn btn-default" onclick="tagsPreview()">Просмотр</button>
                                        <button type="button" class="btn btn-default" onclick="cancel('og-tags')">Отмена</button>
                                    </form>
                                </div>
                                <div id="panel4" class="tab-pane fade">
                                    <form id="metrics-form" action="<?= $this->getBaseUrl().'/smart/handler' ?>" method="post">
                                        <div class="cl-form-group">
                                            <label class="cl-label" for="metrics">Код метрик для вставки</label>
                                            <textarea class="cl-input" name="metrics" id="metrics"><?= $this->getParam('metrics') ?></textarea>
                                        </div>
                                        <button type="button" class="btn btn-main" onclick="saveMetricsTab();">Сохранить</button>
                                    </form>
                                </div>
                                <div id="panel5" class="tab-pane fade">
                                    <form id="favicon-form" action="<?= $this->getBaseUrl().'/smart/handler' ?>" method="post">
                                        <div class="cl-form-group">
                                            <label class="cl-label">Favicon для вашего сайта</label>
                                            <div class="cl-file-input">
                                                <div class="btn btn-primary btn-sm pull-left">
                                                    <span>Выберите файл</span>
                                                    <input name="favicon" id="favicon" type="file" accept="image/vnd.microsoft.icon"/>
                                                </div>
                                                <div class="file-text"><?= $this->getParam('favicon') ?></div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <button type="button" class="btn btn-main" onclick="saveFaviconTab();">Сохранить</button>
                                    </form>
                                </div>
                                <div id="panel6" class="tab-pane fade">
                                    <form id="robots-form" action="<?= $this->getBaseUrl().'/smart/handler' ?>" method="post">
                                        <div class="cl-form-group">
                                            <label class="cl-label" for="robots">Содержимое файла robots.txt</label>
                                            <textarea class="cl-input" name="robots" id="robots"><?= $this->getRobots() ?></textarea>
                                        </div>
                                        <button type="button" class="btn btn-main" onclick="saveRobotsTab();">Сохранить</button>
                                    </form>
                                </div>
                                <div id="panel7" class="tab-pane fade">
                                    <form id="code-form" action="<?= $this->getBaseUrl().'/smart/handler' ?>" method="post">
                                        <div class="cl-form-group">
                                            <label class="cl-label" for="head-code">Код для вставки в шапку</label>
                                            <textarea class="cl-input" name="head-code" id="head-code"><?= $this->getParam('head-code') ?></textarea>
                                        </div>
                                        <div class="cl-form-group">
                                            <label class="cl-label" for="footer-code">Код для вставки в подвал</label>
                                            <textarea class="cl-input" name="footer-code" id="footer-code"><?= $this->getParam('footer-code') ?></textarea>
                                        </div>
                                        <button type="button" class="btn btn-main" onclick="saveCodeTab();">Сохранить</button>
                                    </form>
                                </div>
                                <div id="panel8" class="tab-pane fade">
                                    <form id="template-form" action="<?= $this->getBaseUrl().'/smart/handler' ?>" method="post">
                                        <h2>Шаблоны</h2>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <ul class="list-group cl-template-list">
                                                    <?php $this->renderApplicationCssList() ?>
                                                </ul>
                                            </div>
                                            <div class="col-md-9">
                                                <textarea class="cl-template"></textarea>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <button type="button" class="btn btn-main" onclick="saveTemplateTab();">Сохранить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-preview-tags" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Предварительный просмотр мета-тегов</h4>
                    </div>
                    <div class="modal-body">
                        <pre><code></code></pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="<?= $this->getBaseUrl() ?>/smart/js/jquery.min.js"></script>
        <script src="<?= $this->getBaseUrl() ?>/smart/js/bootstrap.min.js"></script>
        <script src="<?= $this->getBaseUrl() ?>/smart/js/alert-notify.min.js"></script>
        <script src="<?= $this->getBaseUrl() ?>/smart/js/main.js"></script>
    </body>
</html>
