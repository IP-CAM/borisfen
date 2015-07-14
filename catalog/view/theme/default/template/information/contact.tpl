<?php echo $header; ?>
<script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>
<script type="text/javascript" src="/catalog/view/javascript/jquery/jquery-migrate-1.2.1.min.js"></script>

<script src="/catalog/view/theme/default/template/information/groups.js" type="text/javascript"></script>
<script src="/catalog/view/theme/default/template/information/object_list.js" type="text/javascript"></script>
<style type="text/css">
        #map {
            height: 370px;
        }
</style>

    <div class="container contactsPage">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
        <div class="row"><?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?>
                <?php $cols = 6; ?>
            <?php } elseif ($column_left || $column_right) { ?>
                <?php $cols = 9; ?>
            <?php } else { ?>
                <?php $cols = 12; ?>
            <?php } ?>
            <div id="content" class="col-xs-<?php echo $cols; ?>"><?php echo $content_top; ?>
                <h1><?php echo $heading_title; ?></h1>
                <div class="row contact-box">
                    <div class="col-xs-6">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="c-title">Телефоны:</div>
                                <span>+38-057-733-34-33</span>
                                <span><?php echo $this->config->get('config_telephone'); ?></span>
                                <?php if (trim($this->config->get('config_telephone_2'))): ?><span><?php echo $this->config->get('config_telephone_2'); ?></span><?php endif; ?>
                                <?php if (trim($this->config->get('config_telephone_3'))): ?><span><?php echo $this->config->get('config_telephone_3'); ?></span><?php endif; ?>
                                <div id="callme-button">Перезвонить мне</div>
                                <br>
                                <br>
                                <div class="c-title">QR code:</div>
                                <img src="catalog/view/theme/default/image/qrcode.png" >
                            </div>
                            <div class="col-xs-6">
                                <div class="c-title">Время работы:</div>
                                <p>Ежедневно с 9.00 до 21.00<br />Прием заказов с сайта осуществляется круглосуточно. <b>Отправка заказов осуществляется только в будние дни</b></p>
                                <div class="c-title">E-mail:</div>
                                <p>adminwebsite@resurs.kh.ua</p>
                                <div class="c-title">Skype:</div>
                                <p>borisfen-market</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="c-title">Обратная связь:</div>
                        <p>Есть вопросы или пожелания? Заполните форму и мы приложим все усилия, чтобы ответить Вам максимально быстро!</p>
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="main-contact-form">
                            <fieldset>
                                <div class="col-xs-6">
                                    <div class="form-group required">
                                            <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control default" placeholder="<?php echo $entry_name; ?>" />
                                            <?php if ($error_name) { ?>
                                                <div class="text-danger"><?php echo $error_name; ?></div>
                                            <?php } ?>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group required">
                                            <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control default" placeholder="<?php echo $entry_email; ?>" />
                                            <?php if ($error_email) { ?>
                                                <div class="text-danger"><?php echo $error_email; ?></div>
                                            <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <div class="col-xs-12">
                                        <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control default" placeholder="<?php echo $entry_enquiry; ?>"><?php echo $enquiry; ?></textarea>
                                        <?php if ($error_enquiry) { ?>
                                            <div class="text-danger"><?php echo $error_enquiry; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="row">
                                <div class="buttons col-xs-12">
                                    <div class="pull-right">
                                        <input class="btn btn-primary" type="submit" value="Отправить"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row map-box">
                    <div class="col-xs-6">
                        <div class="c-title">Наши магазины:</div>
                        <div class="list-address">
                        </div>
                        <span>г.Харьков магазин "Борисфен". Ежедневно с 9.00 до 21.00</span>
                    </div>
                    <div class="col-xs-6">
                    <script>
                        ymaps.ready(init);
                    </script>
                        <div id="map"></div>
                    </div>
                </div>
                <script>
                    var MCForm = {
                        errors_view: {
                            text: '',
                            styling: 'bootstrap3',
                            addclass: 'float-errors',
                            type: 'error',
                            icon: 'picon picon-32 picon-fill-color',
                            opacity: .8,
                            nonblock: {
                                nonblock: true
                            }
                        },
                        fields: [
                            'textarea[name=enquiry]',
                            'input[name=email]',
                            'input[name=name]'
                        ],
                        init: function(){

                        },
                        validate: function(){

                        },
                        submit: function(){

                        },
                        log: function(){

                        }
                    }
                    $(document).ready(function(){

                        $('#main-contact-form').submit(function(e){

                            e.preventDefault();
                        });
                    });
                </script>
            </div>
            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
<?php echo $footer; ?>