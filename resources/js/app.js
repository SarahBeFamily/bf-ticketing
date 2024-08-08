import './bootstrap';
// import Alpine from 'alpinejs';
import $ from 'jquery';

import.meta.glob([
	'../images/**',
	'../fonts/**',
]);

// window.Alpine = Alpine;
// Alpine.start();

$(function() {

	let body = $('body'),
		ww = $(window).width(),
		isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

	if (isSafari == false) {
		$('.safari-only').remove();
	}

	// Search on datalist on keyup
	$("input[list]").on('click', function() {
		let list = $(this).attr('data-list-id'),
			inputClass = $(this).attr('subject'),
			listOpen = $(`datalist#${list}`).hasClass('open'),
			safari_listOpen = $(`.safari-datalist#datalist-${list}`).hasClass('open');

		$(`.safari-datalist#datalist-${list}`).attr('input-focus', inputClass);
		$(`datalist#${list}`).attr('input-focus', inputClass);
		$(`datalist`).removeClass('open');

		if (listOpen)
			$(`datalist#${list}`).removeClass('open');
		else
			$(`datalist#${list}`).addClass('open');

		/*Safari*/
		if (safari_listOpen)
			$(`.safari-datalist#datalist-${list}`).removeClass('open');
		else
			$(`.safari-datalist#datalist-${list}`).addClass('open');
	});

	$('datalist option').on('click', function() {
		let datalist = $(this).parents('datalist'),
			input = datalist.attr('input-focus');

		$(`input[type="hidden"][name=${input}]`).val($(this).val());
		$(`input.fake.${input}`).val($(this).html());
		datalist.toggleClass('open');
	});

	/*Safari datalist fake*/
	$('.safari-datalist .option').on('click', function() {
		let datalist = $(this).parents('.safari-datalist'),
			input = datalist.attr('input-focus');

		$(`input.${input}`).val($(this).attr('value'));
		datalist.toggleClass('open');
	});

	$("input[list]").on("keyup focus blur change", function(){

		let list = $(this).attr('data-list-id'),
			filter, datalist, option, title, i;

		filter = $(this).val().toUpperCase();
		datalist = document.querySelector(`#${list}`);
		option = datalist.querySelectorAll('option');

		for (i = 0; i < option.length; i++) {
			title = option[i].innerText || option[i].textContent;
			if (title.toUpperCase().indexOf(filter) > -1) {
				option[i].style.display = "flex";
			} else {
				option[i].style.display = "none";
			}
		}
	});

	// Orderby on click on list
	$('ul[id*="dropdown-ordina-"]').on('click', 'li', function() {
		let orderby = $(this).attr('data-orderby'),
			form = $(`form#filtra`);
			
		form.find(`input[name="sort"]`).val(orderby);
		form.trigger('submit');
	});

});