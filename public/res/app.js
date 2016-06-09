var link_action = "";

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

	// Add link form
	$("#button-show-link-add").on('click', function() {
		
		link_action = "add_link";

		// Hide stuff
		$("#link-list-buttons").fadeOut();
		$("#link-list").slideUp();
		$("#link-list-header-buttons").fadeOut();
		$("#link-list-bottom-buttons").slideUp();

		// Show form
		$("#form-link-add").slideDown();
		$("#link-list-footer-buttons").slideDown();
		Utils.showScroll($("#form-link-add"));

		$("#link-list-header-text").fadeOut(function() {
			$(this).text("Add a node").fadeIn();
		});

		$("#node-add-type-link").on('change', function (e) {
			link_action = "add_link";
			$("#node-add-category").slideUp();
			$("#node-add-link").slideDown();
		});

		$("#node-add-type-category").on('change', function (e) {
			link_action = "add_category";
			$("#node-add-link").slideUp();
			$("#node-add-category").slideDown();
		});
	});

	// Edit link form
	$("#button-show-link-edit").on('click', function() {
		set_action('edit');
	});

	// Delete link
	$("#button-show-link-delete").on('click', function() {
		set_action('delete');
	});

	// Cancel button when editing a link
	$(".button-link-cancel").on("click", function() {

		link_action = "";

		// Hide form
		$("#form-link-add").slideUp();
		$("#form-link-edit").slideUp();

		// Show back stuff
		$("#link-list").slideDown();
		$("#link-list-header-buttons").fadeIn();
		$("#link-list-footer-buttons").slideUp();

		$("#link-list-header-text").fadeOut(function() {
			$(this).text("Your links").fadeIn();
		});

		Utils.showScroll($("#link-list-header"));
	});

	$("#button-link-save").on('click', function(e) {
		if (link_action === 'add_link')
		{
			$("#node-add-link .submit").click();
		}
		if (link_action === 'add_category')
		{
			$("#node-add-category .submit").click();
		}
		else if (link_action === 'edit')
		{
			$("#form-link-edit form .submit").click();
		}
	});

	// Change link behaviour to show edit form
	$("#link-list a").on('click', function(e) {

		var elem;

		if (link_action === 'edit')
		{
			e.preventDefault();

			// Find the actual link element
			if (e.target.nodeName === "A")
				elem = $(e.target);
			else
				elem = $(e.target).parent();

			// Show edit link form
			$("#link-edit-url").val(elem.attr('href'));
			$("#link-edit-name").val(elem.attr('title'));
			$("#link-edit-description").val(elem.attr('alt'));
			$("#form-link-edit").slideDown();
			$("#link-list-footer-buttons").slideDown();
			
			// Hide the rest of the stuff
			$("#alert-info-edit").slideUp();
			$("#link-list").slideUp();

			// Change header text
			$("#link-list-header-text").fadeOut(function() {
				$(this).text("Editing link").fadeIn();
			});

			Utils.showScroll($("#form-link-edit"));
		}
		else if (link_action === 'delete')
		{
			e.preventDefault();

			// Find the actual link element
			if (e.target.nodeName === "A")
				elem = $(e.target);
			else
				elem = $(e.target).parent();

			// Show confirm delete dialog
			$('#link-delete-dialog').modal('show');

			$("#link-delete-url").text(elem.attr('href'));
			$("#link-delete-name").text(elem.attr('title'));
			$("#link-delete-description").text(elem.attr('alt'));
		}
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

function set_action(action)
{
	if ((action !== null) && (action !== undefined))
	{
		link_action = action;

		// Change header text
		$("#link-list-header-text").fadeOut(function() {
			$(this).text("Select a link to " + action).fadeIn();
		});

		// Hide top buttons
		$("#link-list-header-buttons").fadeOut();
	}
}