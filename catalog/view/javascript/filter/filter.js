/* Functions */
var filterInit  = function(){
	if(typeof filter == 'undefined') {
		$('#easyPreloaderFilter').remove();
		return false;
	}
    filter.element = {
        button  : $('#filter-button'),
        fields  : $('#product-filter input, #product-filter select option'),
        target  : $('#product-filter input, #product-filter select'),
        labels  : $('#product-filter label'),
        price   : $('#filter-price'), // Price filter block
        options : $('.filter-option'),
        info    : $('#product-filter .option-name a'), // Option desription handler
        scale   : $('#scale'), // Slide scale element
        pFrom   : $('#p-from'),// Price from elemenet
        pTo     : $('#p-to'),  // Price to elemenet
        buttonBottom   : $('#button-bottom'),
        showHideButton : $('#show-hide-options'), // Show/hide options button
        otherOptions   : $('#other-options-hide') // Options block
    };

    if (filter.php.params) var params = [filter.php.params]; else var params = [];
    if (filter.php.price) var price = [filter.php.price]; else var price = [];
    /* Filter handlers base events */
    filter.element.target.on('change', function() {
    if (this.value) params = [this.value]; else params = [];

    var e = $(this);
    /* Input price */
    if((price) && ((e.attr('id') == 'p-from') || (e.attr('id') == 'p-to'))) {
        var start_price = $('#p-from').val();
        var end_price   = $('#p-to').val();
        price[0] = 'p:' + start_price + '-' + end_price;
    }
    /* Input price */
    /* Marked selected values */
    if (e.attr('type') == 'checkbox') {
      e.parent().toggleClass('selected');
    } else if (e.attr('type') == 'radio') {
      $('label', e.parents('.filter-option')).removeClass('selected');
      e.parent().addClass('selected');
    } else if (e.is('select')) {
      if ($('option:first', e).attr('selected')) {
        e.removeClass('selected');
      } else {
        e.addClass('selected');
      }
    }

    if (filter.php.showButton) {
      filter.scroll(e.parents('.filter-option'));
      filter.preload();
      filter.update();
    } else {
      var p = params.concat(price).join(';');
      
      url = filter.url.link;

      if (p) url += '&filter_params=' + p;
      
      $.ajax({
          url: url,
          beforeSend: function(){
              // Here mus be a preloading effect
          },
          success: function(response){
              $('#content').html($(response).find('#content').html());
              if(localStorage.getItem('display') == 'list') {
                  switchToList();
              } else {
                  switchToGrid();
              }
              var curentSelecteds = $('#selecteds').html();
              var responseColumn = $(response).find('#column-left > div.filter').html();

              if(responseColumn) {
                  $('#column-left > div.filter').html(responseColumn);
              }
              $('select').selectpicker({
                  'selectedText': 'cat'
              });
              if(history.pushState) {
                  history.pushState(url, url, url);
              } else {
                  console.log('This browser dosnt support History API.. :(');
              }
              filterInit();
              // Preloading effect must fadeout here
          }
      });
    }

    return false;
  });

  /* Description show/hide */
  filter.element.info.hover(function() {
    $('.description', $(this)).css('display', 'block').animate({'top': $(this).height(), opacity: 1}, {duration: 250, queue: false});
  }, function(){
    $('.description', $(this)).animate({'top': $(this).height()-5, opacity: 0}, {duration: 250, queue: false, complete: function(){ $(this).hide(); }});
  });

  /* Options show/hide */
  filter.element.showHideButton.click(function() {
    $(this).toggleClass('active');

    filter.element.otherOptions.slideToggle(0);
    $('span', $(this)).toggleText(filter.text.show, filter.text.hide);

    return false;
  });
  filter.update = function() {
	  
	  var p = params.concat(price).join(';');
      if(parseInt($('input[name=request-filter_id]').val()) != 0) {
          var additional_filter = '&filter_id=' + $('input[name=request-filter_id]').val(); 
      } else {
          var additional_filter = '';
      }

      $.get('index.php?route=module/filter/callback', 'path=' + filter.php.path + (p ? '&filter_params=' + p : '') + additional_filter, function(json){
        /* Start update */
        if (json['total'] == 0) {
          filter.element.button.removeAttr('href');
          $('span', filter.element.button).text(filter.text.select);
          $('#null', filter.element.price).fadeIn(400);
        } else {
          filter.element.button.attr('href', filter.url.link + (p ? '&filter_params=' + p : '')).removeClass('disabled');
          $('#null', filter.element.price).fadeOut(400);
          $('span', filter.element.button).text(json['text_total']);
        }

        $.each(json['values'], function(key, value) {
          var e = $(filter.tag.vals + key), t = value['products'];
          if (e.is('label')) {
            if (t != '0' || t != 0) e.removeClass('disabled').find('input').removeAttr('disabled');
            $('input', e).val(value['params']);
            if (filter.php.showCounter) $(filter.tag.count, e).text(t);
          } else {
            if (t != '0' || t != 0) e.removeAttr('disabled');
            e.val(value['params']);
          }
        });

        // HIDE filters with no active options
          var activeFlag;
          var activeFlagAll;
          $.each($('.filter-option'), function(key, value){
        	  $(this).css('display', 'block');
        	  var i = 0;
              activeFlag = false;
              activeFlagAll = false;
              if($('.option-values label', this).length) {
                  $.each($('.option-values label', this), function (k, v) {
                      if (!$(this).hasClass('disabled')) {
                          activeFlag = true;
                          if ($('input', this).attr('type') == 'radio'){
                        	  activeFlagAll = true;
                          }
                          i++;
                          
                      } else {
                          $(this).css('display', 'none');
                      }
                  });
                  // for selects
                  if($('.option-values ul.dropdown-menu li', this).length) {
                      activeFlag = false;
                      $.each($('.option-values ul.dropdown-menu li', this), function (k, v) {
                          if (!$(this).hasClass('disabled') && $(this).attr('rel') > 0) {
                              activeFlag = true;
                          }
                      });
                  }

                  if (!activeFlag || (activeFlagAll && i==1)) {
                      $(this).css('display', 'none');
                  }
              }
             i=0;
          });
          
          
        $('#easyPreloaderFilter').hide();
        if((json['disabled_options'] != undefined) || (json['disabled_options'].length == 0)) {
//    		$.each(json['disabled_options'], function(){
//    			var curent_dis_option_id = this.option_id;
//    			$('#other-options > .filter-option, #other-options-hide > .filter-option').each(function(){
//    				if($(this).attr('option-id') == curent_dis_option_id) {
//    					$(this).hide();
//    				}
//    			});
//    		});
    		$('#other-options > .filter-option, #other-options-hide > .filter-option').each(function(){
    			var curent_option_id = $(this).attr('option-id');
    			var option_disabled = false;
    			$.each(json['disabled_options'], function(){
    				if(curent_option_id == this.option_id) {
    					option_disabled = true;
    				}
    			});
    			if(!option_disabled)
    				$(this).show();
    		});
    	} else {
    		$('#other-options > .filter-option, #other-options-hide > .filter-option').each(function(){
				$(this).show();
			});
    	}
        if(price[0] != undefined) {
            var price_limits = price[0].split(':');
            var price_from = 0;
            var price_to   = 0;
            if(price_limits[1] != undefined) {
                price_limits = price_limits[1].split('-');
                if(price_limits[1] != undefined) {
                    price_from = price_limits[0];
                    price_to = price_limits[1];
                }
            }
            $('#p-from').val(price_from);
            $('#p-to').val(price_to);
            
            $('#p-from').removeAttr('disabled');
            $('#p-to').removeAttr('disabled');
            if((filter.slide.leftLimit > price_from) || (filter.slide.rightLimit < price_from)) {
                price_from = filter.slide.leftLimit;
                $('#p-from').val(price_from);
            }
            if((filter.slide.rightLimit < price_to) || (filter.slide.leftLimit > price_to)) {
                price_to = filter.slide.rightLimit;
                $('#p-to').val(price_to);
            }
            filter.slide.leftValue = price_from;
            filter.slide.rightValue = price_to;
            filter.element.scale.trackbar(filter.slide);
        }
        
        $('#disabled', filter.element.price).fadeTo(300, 0, function(){ $('#disabled', filter.element.price).hide(); });
//        $('.option-values select').selectpicker('deselectAll');
        $('.option-values select').selectpicker('update');
		$('.option-values select').selectpicker('refresh');
        /* End update */
      }, 'json');
    };

    filter.preload = function() {
      filter.element.fields.attr('disabled', true);
      filter.element.labels.addClass('disabled');
      filter.element.button.removeAttr('href').addClass('disabled');

      $('#disabled', filter.element.price).show().fadeTo(200, 0.8);
      $('span', filter.element.button).html(filter.text.load);
    };

    filter.scroll = function(option) {
      if (!option.hasClass('focus')) {
        filter.element.buttonBottom.animate({opacity:0}, 300, function(){
          filter.element.buttonBottom.css({'top':top,'display':'none'});

          $('.filter-option.focus .option-values').animate({height: '-=30px'}, 100);

          filter.element.options.removeClass('focus');

          option.addClass('focus');

          $('.option-values', option).animate({height: '+=30px'}, 100, 'linear', function(){
            var top = $('.option-values', option).offset().top+$('.option-values', option).height()-$('#product-filter').offset().top-20;

            filter.element.buttonBottom.css({'top':top,'display':'block'}).animate({opacity:1}, 300);
          });
		  
          $('.option-values.select', option).animate({height: '+=0px'}, 100, 'linear', function(){
            var top = $('.option-values', option).offset().top+$('.option-values', option).height()-$('#product-filter').offset().top-25;

            filter.element.buttonBottom.css({'top':top,'display':'block'}).animate({opacity:1}, 300);
          });
		  
          $('#price-handlers', option).animate({height: '+=0px'}, 100, 'linear', function(){
            var top = $('.option-values', option).offset().top+$('.option-values', option).height()-$('#product-filter').offset().top-40;

            filter.element.buttonBottom.css({'top':top,'display':'block'}).animate({opacity:1}, 300);
          });
        });
      }
    };
    filter.update();
    
    $.fn.toggleText = function(t1, t2){
        var t = this.text();
        if (t == t1) this.text(t2); else this.text(t1);
      };

      if (filter.php.showPrice) {
        filter.slide.onMove = function() {
          filter.element.pFrom.text(this.leftValue);
          filter.element.pTo.text(this.rightValue);
        }

        filter.slide.onSet = function() {
          price = ['p:' + this.leftValue + '-' + this.rightValue];
          filter.scroll($('#filter-price'));
          filter.preload();
          filter.update();
        }
        filter.element.scale.trackbar(filter.slide);
      }
      
}

$(document).ready(function(){
	$('.filter-option').css('display', 'none');
	//$('hideDisable').css('display', 'none');
});



/* Reload filter button event */
$(document).ready(function(){
    filterInit();
    $(document).on('click', '#filter-button', function(event){
        var uri = $(this).attr('href');
        $.ajax({
            url: uri,
            beforeSend: function(){
                // Here must be preloading effect
            },
            success: function(response){
                $('#content').html($(response).find('#content').html());
                if(localStorage.getItem('display') == 'list') {
                    switchToList();
                } else {
                    switchToGrid();
                }
                var curentSelecteds = $('#selecteds').html();
                var responseColumn = $(response).find('#column-left > div.filter').html();
                
                if(responseColumn) {
                    $('#column-left > div.filter').html(responseColumn);
                }
                $('select').selectpicker({
                    'selectedText': 'cat'
                });
                // Preloading effect must fadeout here
                filterInit();
            }
        });
        event.preventDefault();
        if(history.pushState) {
            history.pushState(uri, uri, uri);
        } else {
            console.log('This browser dosnt support History API.. :(');
        }
        
    });
});
/* Reload filter button event */