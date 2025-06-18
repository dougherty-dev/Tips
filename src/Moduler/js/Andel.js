(function() { // Andel
	"use strict";

	$("#andel_1_min, #andel_1_max").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/AndelAjax.php", {
			andel_1_min: $("#andel_1_min").val(),
			andel_1_max: $("#andel_1_max").val()
		}).done(function() {
			$("#andel_1_min").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			$("#andel_1_max").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#andel_x_min, #andel_x_max").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/AndelAjax.php", {
			andel_x_min: $("#andel_x_min").val(),
			andel_x_max: $("#andel_x_max").val()
		}).done(function() {
			$("#andel_x_min").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			$("#andel_x_max").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#andel_2_min, #andel_2_max").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/AndelAjax.php", {
			andel_2_min: $("#andel_2_min").val(),
			andel_2_max: $("#andel_2_max").val()
		}).done(function() {
			$("#andel_2_min").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			$("#andel_2_max").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#andel_attraktionsfaktor").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/AndelAjax.php", {
			attraktionsfaktor: $("#andel_attraktionsfaktor").val()
		}).done(function() {
			$("#andel_attraktionsfaktor").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#andel_attraktionsfaktor_min").on("click", function() {
		$("#andel_attraktionsfaktor").val('1');
		$("#andel_attraktionsfaktor").change();
	});

	$("#andel_attraktionsfaktor_max").on("click", function() {
		$("#andel_attraktionsfaktor").val('1594323');
		$("#andel_attraktionsfaktor").change();
	});

})(); // Andel
