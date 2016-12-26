(function () {
    $.fn.duxform = function (callback) {
        var hashspan = new Object();
        function validateField(field, temp) {
            var error = false;
            var msg = $(field).attr('msg');
            var val = $(field).val();
			var repeat = $(field).attr('repeat');
			var reg = new RegExp(temp);
			$(field).next('.warning').remove();
			$(field).next('.success').remove();
			var repeat_status = true;
			if(repeat){
				if($(repeat).val()!==val){
					repeat_status=false;
				}
			}
			if(!reg.test(val)||!repeat_status){
				$(field).addClass('msg');
				$(field).after('<span class="warning">'+msg+'</span>'); 
				return false;
	        }else{
				$(field).removeClass('msg');
				$(field).after('<span class="success">&nbsp;</span>'); 
			}
			
            return !error;
        }
        function getCheck(obj) {
            var template = $(obj).attr('reg');
			if (template == undefined){
					return null;
				}
            return template;
        }
        var formstate123268 = false;
        function validateForm(obj) {
            $(obj).submit(function () {
                if (formstate123268) {//如果是重复提交则返回
                    return false;
                }
                formstate123268 = true;
                var validationError = false;
                $('input,select,textarea', this).each(function () {
                    var temp = getCheck(this);
                    if (temp != null) {
                        if (!validateField(this, temp)) {
                            validationError = true;
                        }
                    }
                });
                formstate123268 = false;
                if (validationError) {
                    return false;
                }
                if (callback != undefined && typeof (callback) == 'function') {
                    var result = callback();
                    if (typeof (result) == 'boolean') {
                        return result;
                    }
                }
                return true;
            });

            $('select', obj).each(function () {
                var temp = getCheck(this);
                if (temp != null) {
                    var val = temp;
                    $(this).children('option', this).each(function () {
                        if ($(this).attr('value') == val) {
                            $(this).attr('selected', 'selected');
                        }
                    });
                    $(this).change(function () {
                        validateField(this, temp)
                    });
                }
            });

            $('optgroup', obj).each(function () {
                var temp = getCheck(this);
                    var val = temp;
                    $(this).children('option', this).each(function () {
                        if ($(this).val() == val) {
                            $(this).attr('selected', 'selected');
                        }
                    });
            });

            $('input,textarea', obj).each(function () {
                var temp = getCheck(this);
                if (temp != null) {
                    $(this).blur(function () {
                        validateField(this, temp);
                    });
                }
            });
        }
        this.each(function (i, elem) {
            validateForm(elem);
        });
    }
})(jQuery);
$.fn.duxform_callback = function(msg){
	$(this).next('.warning').remove();
	$(this).next('.success').remove();
	$(this).addClass('msg');
	$(this).after('<span class="warning">'+msg+'</span>'); 
	return false;
};
