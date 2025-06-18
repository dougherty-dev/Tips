(function() { // HG
	"use strict";

	function välj_hg(id) {
		$(id).selectmenu({width: 150});
		$(id).on("selectmenuchange", function(e) {
			e.preventDefault();
			$.post("/moduler/ajax/HGAjax.php", {
				hg_min: $(id).val()
			}).done(function() {
				$(id + "-button").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			});
		});
	}

	välj_hg("#hg_min");
	välj_hg("#hg_min_ext");

	$("#hg_attraktionsfaktor").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/HGAjax.php", {
			attraktionsfaktor: $("#hg_attraktionsfaktor").val()
		}).done(function() {
			$("#hg_attraktionsfaktor").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#hg_attraktionsfaktor_min").on("click", function() {
		$("#hg_attraktionsfaktor").val('1');
		$("#hg_attraktionsfaktor").change();
	});

	$("#hg_attraktionsfaktor_max").on("click", function() {
		$("#hg_attraktionsfaktor").val('1594323');
		$("#hg_attraktionsfaktor").change();
	});
})(); // HG
