$(function() {
	
	/* DEN RESPONSIVA MENYN */

	// Ser till att sida ändras när man ändrar val i menyn
	$("#navigation select").on('change', function() {
		window.location = jq(this).find("option:selected").val();
	});

	// Ser till att aktuell sida visas som default-val i menyn
	$("#navigation option").each(function () {
	    if ($(this).val() == window.location) {
	        $(this).prop('selected', true);
	    }
	});

	/* TA BORT ATTACHMENT */
	$('.delete_attachment').on("click", function(e) {
		if(!confirm("Är du säker på att du vill ta bort den här filen?")) {
			return false;
		}
	})
	
	$('.delete_file_error').delay(3000).fadeOut("slow");
	$('.delete_file_success').delay(3000).fadeOut("slow");
	
	/* LÄGGA TILL NYTT FÄLT FÖR NY FIL */
	
	$("#add_file_form").on("click", function(e) {
		e.preventDefault();
		var files = $(".files").children();
		var counter = files.length; 
		$(".files").append("<br /><input type=\"file\" id=\"lesson_file" + counter + "\" name=\"lesson_file"+counter+"\">")
	});

	/* VALIDERING */

	// Validering av kategorier
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

	// Inställningar för valieringen
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