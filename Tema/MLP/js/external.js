$(function() {
	
	$("#navigation select").on('change', function() {
		window.location = jq(this).find("option:selected").val();
	});

	$("#navigation option").each(function () {
	    if ($(this).val() == window.location) {
	        $(this).prop('selected', true);
	    }
	});

	$.validator.prototype.elements = function() {
	    var validator = this;
	    // select all valid inputs inside the form (no submit or reset buttons)
	    return $(this.currentForm)
	        .find(":input")
	        .not(":submit, :reset, :image, [disabled]")
	        .not( this.settings.ignore )
	        .filter(function() {
	            !this.name && validator.settings.debug && window.console && console.error( "%o has no name assigned", this);
	            return validator.objectLength($(this).rules());
	        });
	};

	$("#add_file_form").on("click", function(e) {
			e.preventDefault();
			var files = $(".files").children();
			var counter = files.length; 
			$(".files").append("<br /><input type=\"file\" id=\"lesson_file" + counter + "\" name=\"lesson_file"+counter+"\">")
		});
	$("#insert_new_lesson").validate({
		errorPlacement: function (error, element) { 
			error.insertBefore(element);    
		},
		rules: {
			category: {
				required: 'input[type="checkbox"]:checked',
			},
			lesson_title: {
				required: true,
				minlength: 10
			},
			lesson_intro: {
				required: true,
				minlength: 40
			},
			lesson_goal: {
				required: true,
				minlength: 40
			},
			lesson_execution: {
				required: true,
				minlength: 40
			}
		},
		messages: {
			lesson_title: {
				required: "Detta fält måste fyllas i",
				minlength: "Lektionstiteln är för kort (minst 10 tecken)"
			},
			lesson_intro: {
				required: "Detta fält måste fyllas i",
				minlength: "Lektionsintroduktionen är för kort (minst 40 tecken)"
			},
			lesson_goal: {
				required: "Detta fält måste fyllas i",
				minlength: "Lektionsmål är för kort (minst 40 tecken)"
			},
			lesson_execution: {
				required: "Detta fält måste fyllas i",
				minlength: "Lektionsutförandet är för kort (minst 40 tecken)"
			}				
		}
	});
});