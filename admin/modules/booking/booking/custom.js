/* =====================================================================
 * DOCUMENT READY
 * =====================================================================
 */
$(document).ready(function(){
   
        function sendAjaxForm(form, action, targetCont, refresh, clear, extraTarget, onload){
            var posQuery = action.indexOf('?');
            var extraData = '';
            if(posQuery != -1){
                extraData = action.substr(posQuery+1);
                if(extraData != '') extraData = '&'+extraData;
                action = action.substr(0, posQuery);
            }
            $.ajax({
                url: action,
                type: form.attr('method'),
                data: form.serialize()+extraData,
                success: function(response){
					if(onload != 1){
						$('.field-notice',form).html('').hide().parent().removeClass('alert alert-danger');
						$('.alert.alert-danger').html('').hide();
						$('.alert.alert-success').html('').hide();
                    }
                    var response = $.parseJSON(response);
                    
                    if(targetCont != '') $(targetCont).removeClass('loading-ajax');
                    
                    if(response.error != '') $('.alert.alert-danger', form).html(response.error).slideDown();
                    else if(response.redirect != '' && response.redirect != undefined) window.location.href = response.redirect;
                    else if(refresh === true){
                        var href = window.location.href;
                        window.location = href.substr(0, href.lastIndexOf('#'));
                     }
                    if(response.success != ''){
                        $('.alert.alert-success', form).html(response.success).slideDown();
                        if(clear && response.error == '' && response.notices.length == 0)
                            form.clear();
                    }
                    
                    if(!$.isEmptyObject(response.notices)){
                        if(targetCont != "") $(targetCont).hide();
                        $.each(response.notices, function(field,notice){
                            var elm = $('.field-notice[rel="'+field+'"]', form);
                            if(elm.get(0) !== undefined) elm.html(notice).fadeIn('slow').parent().addClass('alert alert-danger');
                        });
                        $('.captcha_refresh', form).trigger('click');
                    }else{
                        if(targetCont != ''){
                            $(targetCont).html(response.html);
                            $('.open-popup-link').magnificPopup({
                                type:'inline',
                                midClick: true
                            });
                            
                        }
                        if(extraTarget != '')
                            $(extraTarget).html(response.extraHtml);
                    }
                    
                    if($('.alert:visible', form).length){
                        
                        var scroll_1 = $('html, body').scrollTop();
                        var scroll_2 = $('body').scrollTop();
                        var scrolltop = scroll_1;
                        if(scroll_1 == 0) scrolltop = scroll_2;
                        
                        var scrolltop2 = $('.alert:visible:first', form).offset().top - 80;
                        if(scrolltop2 < scrolltop) $('html, body').animate({scrollTop: scrolltop2+'px'});
                    }
                } 
            });
        }
        
        $('form.ajax-form').on('click change', '.sendAjaxForm', function(e){
            e.defaultPrevented;
            var elm = $(this);
            var onload = elm.attr('data-sendOnload');
            var tagName = elm.prop('tagName');
            if((e.type == 'click' && ((tagName == 'INPUT' && (elm.attr('type') == 'submit' || elm.attr('type') == 'image')) || tagName == 'A' || tagName == 'BUTTON')) || e.type == 'change'){
                var targetCont = elm.data('target');
                var refresh = elm.data('refresh');
                var clear = elm.data('clear');
                if(targetCont != "") $(targetCont).html('').addClass('loading-ajax').show();
                sendAjaxForm(elm.parents('form.ajax-form'), elm.data('action'), targetCont, refresh, clear, elm.data('extratarget'), onload);
                //if(tagName == 'A') return false;
            }else{
                //if(tagName == 'A') return false;
            }
        });
        
        $('.submitOnClick').on('click', function(e){
            e.defaultPrevented;
            $(this).parents('form').submit();
            return false;
        });
        $('.sendAjaxForm[data-sendOnload="1"]').trigger('change');
    });
  
    
 
     
    /* =================================================================
     * MAGNIFIC POPUP (MODAL)
     * =================================================================
     */

	if($('.popup-modal').length || $('.ajax-popup-link').length){
        if($('.popup-modal').length){
            $('.popup-modal').magnificPopup({
                type: 'inline',
                preloader: false,
                closeBtnInside: false,
                callbacks: {
                    open: function(){
                        init_carousel($(this.content));
                    }
                }
            });
            $('.popup-modal').click(function(e){
                e.defaultPrevented;
            });
        }
        
        if($('.ajax-popup-link').length){
            $('.ajax-popup-link').each(function(){
                $(this).magnificPopup({
                    type: 'ajax',
                    ajax: {
                        settings: {
                            method: 'POST',
                            data: $(this).data('params')
                        }
                    },
                    callbacks: {
                        open: function(){
                            
                        },
                        ajaxContentAdded: function(){
                            init_carousel($(this.content));
                        }
                    }
                });
            });
        }
        $(document).on('click', '.popup-modal-dismiss', function(){
            //e.defaultPrevented;
            $.magnificPopup.close();
        });
    }
    
    /* =================================================================
     * DATEPICKER
     * =================================================================
     */
    if($('#from_picker').length && $('#to_picker').length){
        $('#from_picker').datepicker({
            dateFormat: 'dd/mm/yy',
            minDate: 0,
            onClose: function(selectedDate){
                var a = selectedDate.split('/');
                var d = new Date(a[2]+'/'+a[1]+'/'+a[0]);
                var t = new Date(d.getTime()+86400000);
                var date = t.getDate()+'/'+(t.getMonth()+1)+'/'+t.getFullYear();
                $('#to_picker').datepicker('option', 'minDate', date);
            }
        });
        $('#to_picker').datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: '+1w'
        });
    }

    /* =================================================================
     * CALENDAR
     * =================================================================
     */
    if($('.hb-calendar').length > 0){
        $('.hb-calendar').each(function(){
            var obj = $(this);
            obj.eCalendar({
                ajaxDayLoader : obj.data('day_loader'),
                customVar : obj.data('custom_var'),
                currentMonth : obj.data('cur_month'),
                currentYear : obj.data('cur_year')
            });
        });
    }

    /* =================================================================
     * BOOTSTRAP MINUS AND PLUS
     * =================================================================
     */
    $('.btn-number').on('click', function(e){
        e.defaultPrevented;
        fieldName = $(this).attr('data-field');
        type = $(this).attr('data-type');
        var input = $('input[name="'+fieldName+'"]');
        var currentVal = parseInt(input.val());
        if(!isNaN(currentVal)){
            if(type == 'minus'){
                if(currentVal > input.attr('min'))
                    input.val(currentVal - 1).change();
                if(parseInt(input.val()) == input.attr('min'))
                    $(this).attr('disabled', true);
            }else if(type == 'plus'){
                if(currentVal < input.attr('max'))
                    input.val(currentVal + 1).change();
                if(parseInt(input.val()) == input.attr('max'))
                    $(this).attr('disabled', true);
            }
        }else
            input.val(0);
    });
    $('.input-number').focusin(function(){
       $(this).data('oldValue', $(this).val());
    });
    $('.input-number').change(function(){
        minValue =  parseInt($(this).attr('min'));
        maxValue =  parseInt($(this).attr('max'));
        valueCurrent = parseInt($(this).val());
        name = $(this).attr('name');
        if(valueCurrent >= minValue)
            $('.btn-number[data-type="minus"][data-field="'+name+'"]').removeAttr('disabled');
        else{
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if(valueCurrent <= maxValue)
            $('.btn-number[data-type="plus"][data-field="'+name+'"]').removeAttr('disabled');
        else{
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        } 
    });
    $('.input-number').keydown(function(e){
        if($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true) || 
            (e.keyCode >= 35 && e.keyCode <= 39))
                 return;
                 
        if((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105))
            e.defaultPrevented;
    });
