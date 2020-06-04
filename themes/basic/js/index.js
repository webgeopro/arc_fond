$(document).ready(function () {
	/**
	 * Оформление полей всплывающей формы
	 * Привязка события на Enter
	 */
	$('#divLogIn input[title!=""]')
		.hint()
		.keyup(function (e) {
			if(e.keyCode == 13) {
				$("#bnLogIn").click();
			}
		});
    /**
     * Обработка кликов стартовой страницы 
     */
    $('body.startpage').click(function (e) {
    	var target = $(e.target);
    	// Клик в любом месте, кроме окна авторизации и элементов его формы, прячет данное окно
    	if(! target.is('#divLogIn, #linkLogIn, #divLogIn input, #divLogIn button')) {
    		$('#divLogIn').hide();
    	}
		// Отображение окна для авторизации
    	if(target.is('#linkLogIn')) {
	    	$('#divLogIn').slideToggle(100);
	        return false;
    	}
    	// AJAX-авторизация пользователя 
    	if(target.is("#bnLogIn")) {
//@todo Нет обработки AJAX-ответов со статусом, отличным от 200 (500, 404 и так далее) 
			$.post(
				"/site/loginAjax", 
				{
					'UserLogin[username]' : $("#inpLogin").val(),
					'UserLogin[password]' : $("#inpPass").val()
				},  
				function (data) {
					if (data['result'] == 'success' && data['address']) {
//	                    $("div#li").html('<a href="'+data['address']+'">&nbsp;- Кабинет</a>&nbsp;&nbsp;&nbsp;<a href="/" id="aLogOut" onClick="return fLogOut()">- Выход</a>');
						$("#divLogIn").hide();//slideUp("fast");
						$('#linkLogIn')
						.text('Выйти').attr('id', 'linkLogOut')
						.parent('.b-navi__item').before('<li class="b-navi__item"><a href="' + data['address'] + '">Личный кабинет</a></li>');
					} else if (data['result'] == 'errorAuth') {
						alert('Неверный пользователь или пароль');
					} else if (data['result'] == 'errorPOST') {
						alert('Ошибка передачи данных. \nПопробуйте еще раз.');
					} else {
						alert('При авторизации произошла ошибка.');
					}
				}, 
				"json");
            return false;
        }
    	// AJAX-выход пользователя
    	if(target.is('#linkLogOut')) {
//@todo Нет обработки AJAX-ответов со статусом, отличным от 200 (500, 404 и так далее)    		
    		$.post(
    			"/site/logoutAjax", 
    			{},  
    			function (data) {
	                if (data['result'] == 'success') {
	                	$('#linkLogOut')
						.text('Вход').attr('id', 'linkLogIn')
						.parent('.b-navi__item').prev('.b-navi__item').remove();
	                } else {
	                    alert('Произошла ошибка. Попробуйте еще раз.');
	                }
	            },
    			"json");
            return false;
    	}
    });
});