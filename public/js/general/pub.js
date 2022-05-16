

$.fn.serializeObject = function () {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function () {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};

function Confirm(Title, BoxWidth, AutoClose, Content, Buttons, Type) {
	$.confirm({
		title: Title,
		boxWidth: BoxWidth,
		useBootstrap: false,
		autoClose: AutoClose,
		content: Content,
		type: Type,
		rtl: true,
		typeAnimated: true,
		buttons: Buttons
	});
}

function funcAlert(title, text) {
	$.alert({
		title: '<div>' + title + '</div>',
		content: text,
		titleClass: 'left floated right aligned red',
		confirmButtonClass: 'btn-primary',
		rtl: true,
		draggable: true,
		dragWindowBorder: false,
		columnClass: 'col-md-4',
		buttons: [{
			btnClass: 'btn-primary text-left',
			text: "تایید", click: function () {
			}
		}]
	});
}

function checkRequired(elements) {
	var firstElement = "";
	rflag = true;
	$(elements).each(function (index, element) {
		if ($(element).is("input, textarea")) {
			if ($(element).val().trim().length > 0) {
				$(element).css("border", "1px solid #ccc");
				$(element).css("box-shadow", "unset");
			} else {
				if (firstElement == "")
					firstElement = $(element);
				$(element).css("border", "1px solid rgba(255, 0, 0, 0.8");
				$(element).css("box-shadow", "0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(255, 0, 0, 0.3)");
				rflag = false;
			}

			if ($(element).attr("type") == "checkbox" && !($(element).is(":checked"))) {
				$(element).parent().children().css("color", "red");
				firstElement = $(element);
				rflag = false;
			}
		} else if ($(element).is("select")) {
			if ($(element).val() != null && $(element).val() != "-1") {
				$(element).css("border", "1px solid #ccc");
				$(element).css("box-shadow", "unset");

			} else {
				if (firstElement == "")
					firstElement = $(element);
				$(element).parent().css("border", "1px solid rgba(255, 0, 0, 0.8)");
				$(element).parent().css("box-shadow", " 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(255, 0, 0, 0.3)");
				rflag = false;
			}
		}

	});
	if (!rflag)
		$("html").animate({scrollTop: $(firstElement).parent().offset().top - 5}, "fast");
	return rflag;
}

$(document).ready(function () {
	// $('.modal').on('click', '.close-mdl', function (eventObject) {
	// 	$('.modal').hide();
	// });
})
