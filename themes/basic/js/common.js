/**
 * Файл общих настроек JS/Jquery для всех разделов сайта
 * @author Andrey Matveev <andrey.g.matveev@gmail.com>
 */

$(document).ready(function () {
	$.ajaxSetup({
		error: function (xhr, status, errorThrown) {
			if(status == 'error') {
				alert('Извините, произошла ошибка обработки запроса.');
			}
			else if(status == 'timeout') {
				alert('Превышено время ожидания ответа на запрос.');
			}
	//		else if(status == 'abort') {
	//			alert('Запрос был прерван пользователем.');
	//		}
			else if(status == 'parsererror') {
				alert('Извините, произошла обшибка разбора запроса.');
			}
		}
	});
	$('#global-ajax-loader')
		.ajaxStart(function() {$(this).stop().fadeIn(80)})
		.ajaxStop(function() {$(this).stop().fadeOut(80)});
});

/**
 * Класс для работы с деревом городов 
 */
//var Towns = {
//	init: function () {
//		var fields = $('.townsSelectField');
//		alert(fields.length);
//		if(fields.length > 0) {
//			var selectBlock = $('#townsAddNew');
//			alert(selectBlock.length );
//			if(selectBlock.length == 0) {
//				alert('build select');
//				$('body').append('<div id="townsAddNew"><select class="townsSelect"></div></div>');
//				selectBlock = $('#townsAddNew');
//				alert(selectBlock.length);
//				$.get(
//					'/townsOptions',
//					{'root_id':-1},
//					function (data) {
//						if(data) {
//							selectBlock.children('.townsSelect').append(data);
//						}
//						else {
//							alert('Извините, произошла ошибка');
//						}
//					} 
//				)
//				
//			}
//			
//			selectBlock
//				.bind('change', function(e) {
//				var self = $(e.target);
//				if(self.is('select')) {
//					var	val = self.val();
//					if(val > 0) {
//						$.get(
//							'/townsOptions', 
//							{'root_id': val},
//							function(data) {
//								if(data) {
//									self.nextAll().remove();
//									$('<select id="townsAddNew-' + val + '" class="townsSelect"></select>')
//										.insertAfter(self)
//										.html('<option selected="selected">—</option>' + data);
//								}
//								else {
//									self
//										.nextAll().remove().end()
//										.after('<input type="button" class="button" value="Добавить" />')
//										.next('input:button')
//										.click(function() {
//											var key = self.val(),
//												title = self.children(':selected').text();
//											Towns.add(key, title)
//										});
//								}					
//							});
//					}
//				}
//			});
//			$('#townsSelected').bind('change', function(e) {
//				var self = $(e.target);
//				if(self.is('input:checkbox')) {
//					if(! self.attr('checked')) {
//						Towns.remove(self.val());
//					}
//					else {
//						Towns.add(self.val(), '');
//					}
//				}
//			});
//			this.render();
//		}
//	}
//	,checked:[]
//	,add: function (key, title) {
//		if(title && ! this.checked.hasOwnProperty(key)) {
//			// Город выбрать можно только один
//			this.checked = {};
//			this.checked[key] = {
//				'title': title,
//				'checked': true
//			};
//		}
//		else if(this.checked.hasOwnProperty(key)) {
//			this.checked[key]['checked'] = true;
//		}
//		this.render(); 		
//	}
//	,remove: function (key) {
//		if(this.checked.hasOwnProperty(key)) {
//			this.checked[key]['checked'] = false;
//		}
////		$('#townsSelected #acivitySelected-' + key).remove();
////		this.render();
//	}
//	,render: function () {
//		var str = '';
//		for(key in this.checked) {
//			str += 
//				'<input type="checkbox" class="input_chbx" name="towns[]" value="' + 
//				key + 
//				'" id="townsSelected-' + 
//				key + 
//				'"' + 
//				(this.checked[key]['checked'] ? ' checked="checked"' : '') 
//				+ 
//				' /><label for="townsSelected-' + 
//				key + 
//				'">' + 
//				this.checked[key]['title'] + 
//				'</label><br/>'
//		}
//		if(! str) {
//			str = 'Ничего не добавлено';
//		}
//		$('#townsSelected .data').empty().append(str);
//	}
//};

/**
 * Плагин для работы с деревом 
 */
(function ($) {
	var currentObj = null;
	
	$.fn.selectableTree = function ( options ) {
		options = $.extend({}, $.fn.selectableTree.defaults, options);
		
		this
			.each(function () {
				var obj = $(this);
				var newName = (obj.attr('name').charAt(obj.attr('name').length - 1) == ']') ? obj.attr('name').substr(0, obj.attr('name').length - 1) + '_title]' : obj.attr('name') + '_title';
				if(obj.next('input:hidden').length == 0) {
					obj.after('<input type="hidden" name="' + obj.attr('name') + '" value="" />').attr('name', newName);
				}
			})
			.attr('readonly', 'readonly');
			
		selectInit( this, options );
		
		return this.bind('click', function () {
			selectShow(this, options);			
		});;
	};
	
	$.fn.selectableTree.defaults = {
		'url': '',
		'selectId': 'selectableTreeSelect'
	};
	
	var selectShow = function ( obj, options ) {
		$('#' + options.selectId).css('top',$(obj).position().top + 20).insertAfter(obj).show();
		currentObj = obj;
	}
	
	var selectInit = function ( obj, options ) {
		selectBlock = $('#' + options.selectId);
		if(selectBlock.length == 0) {
			$('body')
				.append('<div id="' + options.selectId + '" class="popupBox"><div class="selectArea"><select class="popupBox__select"></select></div><div class="popupBox__close">×</div></div>');
			selectBlock = $('#' + options.selectId);
			$.get(
				options.url,
				{'root_id': -1},
				function (data) {
					if(data) {
						selectBlock.children('.selectArea select:first').append('<option selected="selected">—</option>' + data);
					}
					else {
						alert('Извините, произошла ошибка');
					}
				} 
			)
		}
		
		selectBlock
			.bind('change', function(e) {
				var self = $(e.target);
				if(self.is('select')) {
					var	val = self.val();
					if(val > 0) {
						$.get(
							options.url, 
							{'root_id': val},
							function(data) {
								if(data) {
									self.nextAll().remove();
									$('<select id="' + options.selectId + '-' + val + '" class="popupBox__select"></select>')
										.insertAfter(self)
										.html('<option selected="selected">—</option>' + data);
								}
								else {
									self
										.nextAll().remove().end()
										.after('<input type="button" class="buttonSelect" value="Выбрать" />')
										.end()
								}					
							});
					}
				}
			})
			.bind('click', function (e) {
				var self = $(e.target);
				if(self.is('.popupBox__close')) {
					self.parent().hide();				
				}
				else if(self.is('.buttonSelect')) {
					var key = self.prev('select').val(),
						title = self.prev('select').children(':selected').text();
					$(currentObj).val(title).siblings('input:hidden').val(key);
					$('#' + options.selectId).hide();
				}
	 		});
	}
	
})(jQuery);