$(document).ready(function(){
	$('label[for^=authors]').click(function() {
		/*
		var labelID;
	    labelID = $(this).attr('for');
	    $('#'+labelID).hide();
	    */
		if (!confirm('Teilnehmer aus der Autorenliste löschen?')) return false;
		$(this).parent().remove();
	});
	
});