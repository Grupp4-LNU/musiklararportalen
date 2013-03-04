jq("#navigation select").on('change', function() {
	window.location = jq(this).find("option:selected").val();
});