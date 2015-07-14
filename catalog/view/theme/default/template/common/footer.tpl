            </div>
        </div> 
        <footer class="footer">
        	<div class="ovh">
        		<div class="row">
        			<div class="menu-holder">
                        <div class="container">
                            <div class="catMenu row">
                                <div class="col-xs-3">
                                    <div class="title-M">Информация</div>
                                    <?php print $modules[0]; ?>
                                </div>
                                <div class="col-xs-4">
                                <div class="title-M">Меню</div>
                                    <?php print $modules[1]; ?>
                                </div>
                                <div class="col-xs-5 cont-box">
                                    <div class="title-M">Контакты</div>
                                    <span class="f-tel">+38-057-733-34-33</span>
                                    <ul class="pull-left">
                                        <li><span>+38-050-888-11-55</span></li>
                                        <li><span>+38-067-555-83-15</span></li>
                                        <li><span>+38-093-000-10-95</span></li>
                                    </ul>
                                    <ul class="pull-right">
                                        <li class="mail"><span>adminwebsite@resurs.kh.ua</span></li>
                                        <li class="skype"><span>borisfen-market</span></li>
                                        <!--<li class="viber"><span>+38 (093) 704-35-36</span></li>-->
                                    </ul>
                                    <ul class="social">
                                        <li class="vk"><a href="http://vk.com/borisfen_market" title="Следите за нами вКонтакте">вКонтакте</a></li>
                                        <li class="fb"><a href="https://www.facebook.com/groups/borisfenmarket.com.ua/" title="Следите за нами на Facebook">Facebook</a></li>
                                        <li class="od"><a href="http://ok.ru/group/52651506466988" title="Следите за нами в Однокласниках">Однокласники</a></li>
                                        <li class="gplus"><a href="https://plus.google.com/u/0/communities/115450101250108772684" title="Следите за нами в G+">Google +</a></li>
                                        <li class="tw"><a href="https://twitter.com/BorisfeMarket" title="Следите за нами в twitter">twitter</a></li>
                                        <li class="inst"><a href="https://instagram.com/borisfenmarket/" title="Следите за нами в instagram">instagram</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
        			</div>
        			<div class="powered-holder">
                        <div class="container">
                            <?php echo $powered; ?>
                        </div>
        			</div>
        		</div>
        	</div> 
        </footer>
        <!-- additional data -->
        <input type="hidden" name="request-path" value="<?php if(isset($this->request->get['path'])) {print $this->request->get['path']; } else { print 0; }?>">
        <input type="hidden" name="request-filter_id" value="<?php if(isset($this->request->get['filter_id'])) {print $this->request->get['filter_id']; } else { print 0; }?>">
        <input type="hidden" name="button_cart" value="<?php print $this->language->get('button_cart'); ?>">
        <input type="hidden" name="button_in_cart" value="<?php print $this->language->get('button_in_cart'); ?>">
        <!-- additional data -->
        <!-- Scripts appended to footer -->
        <?php foreach ($scripts as $script) { ?>
        <script src="<?php echo $script; ?>" type="text/javascript"></script>
        <?php } ?>
        <?php foreach ($typeScripts as $typeScripts) { ?>
        <script type="text/typescript" src="<?php print $typeScripts; ?>"></script>
        <?php } ?>
        <!-- Scripts appended to footer -->
        <!-- php&&js Scripts -->
        <script type="text/javascript">
        	jQuery(document).ready(function($){
        		/* Change value on click add to cart button */
        		$(document).on('click', '.addToCart', function(){
        			if(!$(this).hasClass('disabled')) {
        				$(this).children('.add-text').text('<?php print $this->language->get('button_in_cart');?>').addClass('prepared-to-remove');
        			}
        		});
        		/* Change value on click add to cart button */
        	});
        </script> 
        <!-- php&&js Scripts -->
        <!-- Styles appended to footer -->
        <?php foreach ($styles as $style) { ?>
        <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
        <?php } ?>
        <!-- Styles appended to footer -->
        <p id="topon" style="display: block">
        	<a href="#top"><span></span></a>
        </p>
    </body>
    </html>
<!--<form action="#" id="newsSubscribeForm">
    <fieldset>
        <div class="input-group pull-left sendMail">
          <input type="text"  value="Подпишитесь!" class="form-control input-md " autocomplete="false" id="newsSubscribeInput">
          <span class="input-group-btn">
                <button type="button" class="btn  btn-md" id="newsSubscribeButton"></button>
          </span>
        </div>
    </fieldset>
</form>-->
