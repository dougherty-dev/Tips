(function() { // Autospik
	"use strict";

	function välj_autospik(id) {
		$(id).selectmenu({width: 80});
		$(id).on("selectmenuchange", function(e) {
			e.preventDefault();
			$.post("/moduler/ajax/AutospikAjax.php", {
				valda_spikar: $(id).val()
			}).done(function() {
				$(id + "-button").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			});
		});
	}

	välj_autospik("#autospik");
	välj_autospik("#autospik_ext");

	$("#autospik_attraktionsfaktor").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/AutospikAjax.php", {
			attraktionsfaktor: $("#autospik_attraktionsfaktor").val()
		}).done(function() {
			$("#autospik_attraktionsfaktor").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#autospik_attraktionsfaktor_min").on("click", function() {
		$("#autospik_attraktionsfaktor").val('1');
		$("#autospik_attraktionsfaktor").change();
	});

	$("#autospik_attraktionsfaktor_max").on("click", function() {
		$("#autospik_attraktionsfaktor").val('1594323');
		$("#autospik_attraktionsfaktor").change();
	});
})(); // Autospik
