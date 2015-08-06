$(document).ready(function(){
	$('label[for^=authors]').click(function() {
		/*
		var labelID;
	    labelID = $(this).attr('for');
	    $('#'+labelID).hide();
	    */
		if($("#subform_list_authors").children().length == 1){
			alert('Mindestens ein Autor sollte es schon sein.');
		}
		else{
			if (!confirm('Teilnehmer aus der Autorenliste löschen?')) return false;
			$(this).parent().remove();
		}
	});
	
});