(function() { // Kluster
	"use strict";

	$("#kluster_min_antal_per_kluster").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/KlusterAjax.php", {
			min_antal: $("#kluster_min_antal_per_kluster").val()
		}).done(function() {
			$("#kluster_min_antal_per_kluster").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			rita_klustergraf();
		});
	});

	$("#kluster_min_radie").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/KlusterAjax.php", {
			min_radie: $("#kluster_min_radie").val()
		}).done(function() {
			$("#kluster_min_radie").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			rita_klustergraf();
		});
	});

	function rita_klustergraf() {
		const d = new Date();
		const src = $('#klustergraf .tipsgraf').attr("src");
		$('#klustergraf .tipsgraf').attr("src", src + '?' + d.getTime());
	}

	$("#kluster_attraktionsfaktor").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/KlusterAjax.php", {
			attraktionsfaktor: $("#kluster_attraktionsfaktor").val()
		}).done(function() {
			$("#kluster_attraktionsfaktor").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#kluster_attraktionsfaktor_min").on("click", function() {
		$("#kluster_attraktionsfaktor").val('1');
		$("#kluster_attraktionsfaktor").change();
	});

	$("#kluster_attraktionsfaktor_max").on("click", function() {
		$("#kluster_attraktionsfaktor").val('1594323');
		$("#kluster_attraktionsfaktor").change();
	});
})(); // Kluster
