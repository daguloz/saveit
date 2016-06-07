$(document).ready(function () {
	
	"use strict";

	////////////////////
	// Datetimepicker //
	////////////////////
	
	if ($().datetimepicker !== undefined) {
		$(".input-calendar").datetimepicker({
			format: 'd/m/Y',
			scrollInput: false,
			timepicker: false

		});
		jQuery.datetimepicker.setLocale('es');
		$(".input-calendar").trigger('set');
	}

	// Select2
	if ($().select2 !== undefined)
		Utils.setupSelect2(".select2", false);

	$("#btn_show_add_link").on('click', function() {
		$("#btn_show_add_link").parent().hide();
		$("#form_add_link").slideDown();
		Utils.showScroll($("#form_add_link"));
	});

	$("#btn_show_edit_link").on('click', function() {

		$("#link-list a").on('click', function(e) {
			e.preventDefault();
			var elem;
			if (e.target.nodeName === "A")
				elem = $(e.target);
			else
				elem = $(e.target).parent();
			$("#edit_link_url").val(elem.attr('href'));
			$("#edit_link_name").val(elem.attr('title'));
			$("#edit_link_description").val(elem.attr('alt'));
			$("#form_edit_link").slideDown();
			Utils.showScroll($("#form_edit_link"));
		});
		$("#alert_edit_info").slideDown();
		Utils.showScroll($("#alert_edit_info"));
	});
});

////////////////////////
// Funciones globales //
////////////////////////

var Utils = {


	/**
	 * Realiza una petición AJAX a la API y ejecuta las funciones tras realizar la petición
	 * @param  {string} url          Dirección 
	 * @param  {function} funcSuccess  Función ejecutada si la petición AJAX es correcta
	 * @param  {function} funcError    Función ejecutada si la petición AJAX falla
	 * @param  {function} funcComplete Función ejecutada siempre, después del resto
	 */
	getApi: function (url, params, funcSuccess, funcError, funcComplete) {


		if (!url)
			return false;
		if (!funcSuccess)
			return false;
		
		if (params === null)
			params = {env: false};

		var env = Utils.getUrlParam('env');
		if (env !== undefined)
			params.env = env;
		// Imprime en consola la petición
		var paramsPrint = " Params: ";
		for (var key in params)
			paramsPrint += key + '=' + params[key] + ' ';
		console.log("Request: " + location.origin + url + paramsPrint);

		$.ajax({
			type: 'GET',
			url: url,
			dataType: 'json',
			data: params,

			// Petición correcta
			success: function (jsonData, textStatus) {
				funcSuccess(jsonData, textStatus);
			},

			// Error en la petición
			error: function (jqXHR, textStatus, error) {
				var err = textStatus + ", " + error;
				console.log("Respuesta " + textStatus + " (" + err + "): " + location.origin + url + paramsPrint);
				if (funcError !== undefined)
					funcError(jqXHR, textStatus, error);
			},

			// Petición completada
			complete: function (jqXHR, textStatus) {
				if (jqXHR.responseJSON !== undefined) {
					console.log("Respuesta " + jqXHR.responseJSON.status + " (" + jqXHR.responseJSON.message + "): " + location.origin + url + paramsPrint);
					if (jqXHR.responseJSON.status == 200)
						console.log(jqXHR.responseJSON.response);
				}
				else {
					var res = {response: jqXHR.responseText};
					console.log(res);
				}
				if (funcComplete !== undefined)
					funcComplete(jqXHR, textStatus);
			}
		});
	},

	/**
	 * Obtiene un parametro de la URL
	 * @param  {string} sParam Parámetro a obtener
	 * @return {string} Valor del parámetro
	 */
	getUrlParam: function (sParam) {
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	},

	/**
	 * Realiza una petición POST mediante AJAX a la API y ejecuta las funciones tras finalizar
	 * @param  {string} url          Dirección 
	 * @param  {function} funcSuccess  Función ejecutada si la petición AJAX es correcta
	 * @param  {function} funcError    Función ejecutada si la petición AJAX falla
	 * @param  {function} funcComplete Función ejecutada siempre, después del resto
	 */
	postApi: function (url, data, funcSuccess, funcError, funcComplete) {


		if (!url)
			return false;
		if (!funcSuccess)
			return false;
		
		if (data === null)
			data = {env: false};

		var env = Utils.getUrlParam('env');
		if (env !== undefined)
			data.env = env;
		// Imprime en consola la petición
		var paramsPrint = " Params: ";
		for (var key in data)
			paramsPrint += key + '=' + data[key] + ' ';
		console.log("Request: " + location.origin + url + paramsPrint);

		$.ajax({
			type: 'POST',
			url: url,
			data: JSON.stringify(data),
			contentType: "application/json; charset=utf-8",
			dataType: 'json',

			// Petición correcta
			success: function (jsonData, textStatus) {
				funcSuccess(jsonData, textStatus);
			},

			// Error en la petición
			error: function (jqXHR, textStatus, error) {
				var err = textStatus + ", " + error;
				console.log("Respuesta " + textStatus + " (" + err + "): " + location.origin + url + paramsPrint);
				if (funcError !== undefined)
					funcError(jqXHR, textStatus, error);
			},

			// Petición completada
			complete: function (jqXHR, textStatus) {
				if (jqXHR.responseJSON !== undefined) {
					console.log("Respuesta " + jqXHR.responseJSON.status + " (" + jqXHR.responseJSON.message + "): " + location.origin + url + paramsPrint);
					if (jqXHR.responseJSON.status == 200)
						console.log(jqXHR.responseJSON.response);
				}
				else {
					var res = {response: jqXHR.responseText};
					console.log(res);
				}
				if (funcComplete !== undefined)
					funcComplete(jqXHR, textStatus);
			}
		});
	},

	/** [setupSelect2 description] */
	setupSelect2: function (selectInput, data) {
		
		// Añade elementos input hidden a cada select2 (usado para validar)
		$(selectInput).each(function (index, elem) {
			$(elem).after($('<input type="hidden" id="' + elem.id + '-value" />'));
		});

		if (data !== false)
		{
			$(selectInput).select2({
				data: data,
				minimumResultsForSearch: 5,
				templateSelection: function (state) {
					if (state.text != 'Cargando...') { return state.text; }
						var $state = $(
							'<div class="select-loading">' + state.text + '</div>'
						);
					return $state;
				}
			});
		}
		else
		{
			$(selectInput).select2({
				minimumResultsForSearch: 5,
				templateSelection: function (state) {
					if (state.text != 'Cargando...') { return state.text; }
						var $state = $(
							'<div class="select-loading">' + state.text + '</div>'
						);
					return $state;
				}
			});
		}
		$(selectInput).on('change', function(elem) {
			var field = $('#' + elem.target.id + '-value');
			field.val(elem.target.value).trigger('change');

		});
	},

	/**	
	 * Muestra un elemento y hace scroll hacia él
	 * @param  {HTMLElement} elem Elemento a activar
	 */
	showScroll: function (elem) {
		$(elem).show();
		$('html, body').animate({
			scrollTop: $(elem).offset().top - 60
		}, 400);
	},

	/**
	 * Desactiva un elemento basado en select2
	 * @param  {HTMLElement} elem Elemento a desactivar
	 */
	selectDisable: function (elem) {
		$(elem).select2({
			disabled: true,
			templateSelection: function (state) {
				var $state = $('<div></div>');
				return $state;
			}
		});
	},

	/**
	 * Activa un elemento Select2 y asigna un placeholder
	 * @param  {HTMLElement} elem Elemento a activar
	 * @param  {[string]} msg Mensaje a mostrar como placeholder
	 */
	selectEnable: function (elem, msg) {
		$(elem).select2({
			templateSelection: function (state) {
				if (state.text != 'Cargando...') { return state.text; }
				var $state = $('<div>' + msg + '</div>');
				return $state;
			}
		});
	}
};