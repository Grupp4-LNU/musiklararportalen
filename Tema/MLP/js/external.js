$(function() {
	
	$("#navigation select").on('change', function() {
		window.location = jq(this).find("option:selected").val();
	});

	$("#navigation option").each(function () {
	    if ($(this).val() == window.location) {
	        $(this).prop('selected', true);
	    }
	});

	$("#add_file_form").on("click", function(e) {
		e.preventDefault();
		var files = $(".files").children();
		var counter = files.length; 
		$(".files").append("<br /><input type=\"file\" id=\"lesson_file" + counter + "\" name=\"lesson_file"+counter+"\">")
	});

	$.validator.addMethod('atLeastOneCat', function(value, element, param) {
	    return $('input[name^="category"]').is(':checked');
	}, 'Välj minst ett huvudområde');

	$.validator.addMethod('atLeastOneYear', function(value, element, param) {
	    return $('input[name^="grade"]').is(':checked');
	}, 'Välj minst en årskurs');

	$('#submit').on('click', function(){
		$('#category_error').html('');
		$('#grade_error').html('');
	});

	$("#insert_new_lesson").validate({
		rules: {
			'category[]': 'atLeastOneCat',
			'grade[]':  'atLeastOneYear',
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
		}, groups: {
            checkboxes: 'category[]'
        }, errorPlacement: function(error, elem) {
            if (elem.attr('name').match(/category\[\]/)) {
				$('#category_error').html(error[0].innerHTML);
			}
			else if (elem.attr('name').match(/grade\[\]/)) {
				$('#grade_error').html(error[0].innerHTML);
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