(function() { // Vinstgraf
	"use strict";

	$("#uppdatera_vinstgraf").on("click", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/VinstgrafAjax.php", {
			utdelning_13_min: $("#utdelning_13_min").val(),
			utdelning_13_max: $("#utdelning_13_max").val()
		}).done(function() {
			window.location.replace('/');
		});
	});

})(); // Vinstgraf
