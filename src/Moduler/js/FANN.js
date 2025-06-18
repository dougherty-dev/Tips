(function() { // FANN
	"use strict";

	function välj_fann(id) {
		$(id).selectmenu({width: 150});
		$(id).on("selectmenuchange", function(e) {
			e.preventDefault();
			$.post("/moduler/ajax/FANNAjax.php", {
				fann_min: $(id).val()
			}).done(function() {
				$(id + "-button").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			});
		});
	}

	välj_fann("#fann_min");
	välj_fann("#fann_min_ext");

	$("#fann_feltolerans").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/FANNAjax.php", {
			fann_feltolerans: $("#fann_feltolerans").val()
		}).done(function() {
			$("#fann_feltolerans").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#fann_attraktionsfaktor").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/FANNAjax.php", {
			attraktionsfaktor: $("#fann_attraktionsfaktor").val()
		}).done(function() {
			$("#fann_attraktionsfaktor").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#fann_attraktionsfaktor_min").on("click", function() {
		$("#fann_attraktionsfaktor").val('1');
		$("#fann_attraktionsfaktor").change();
	});

	$("#fann_attraktionsfaktor_max").on("click", function() {
		$("#fann_attraktionsfaktor").val('1594323');
		$("#fann_attraktionsfaktor").change();
	});
})(); // FANN
