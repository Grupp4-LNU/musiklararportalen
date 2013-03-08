$("#navigation select").on('change', function() {
	window.location = jq(this).find("option:selected").val();
});

$("#navigation option").each(function () {
    if ($(this).val() == window.location) {
        $(this).prop('selected', true);
    }
});