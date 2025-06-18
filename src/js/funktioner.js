/* jshint esversion: 6 */

'use strict';

import * as g from './gemensamt.js';

(function() { // Tips
	$("#flikar").tabs();

	$("input[type='radio'], input[type='checkbox']").checkboxradio();
	$("input[type=submit], button").button();

	$("#sortera_moduler").controlgroup();
	$("table#sortera_moduler").sortable({
		items: "tr.modulsortering",
		axis: "y",
		opacity: 0.6,
		tolerance: "intersect",
		forceHelperSize: true,
		forcePlaceholderSize: true,
		cursor: "move",
		revert: 200,
		update: function() {
			$.post("/ajax/ModulerAjax.php", {uppdatera_moduler: $("table#sortera_moduler").sortable("serialize")});
		}
	});

	$("#matchtabell input[name^='streck']").change(function() {
		this.value = this.value.replace(/\D/g,'');
	});

	$("#matchtabell input[name^='odds']").change(function() {
		this.value = this.value.replace(/[,]/g,'.');
		this.value = this.value.replace(/[^0-9.]/g,'');
	});

	$("img.tipsgraf").on("click", function() {
		if (this.requestFullscreen) {
			this.requestFullscreen();
		} else if (this.webkitRequestFullscreen) { /* Safari */
			this.webkitRequestFullscreen();
		} else if (this.msRequestFullscreen) { /* IE11 */
			this.msRequestFullscreen();
		}
	});
})(); // Tips

(function() { // preferenser
	/* trådar */
	$("#preferenser-tradar").controlgroup();
	$("#preferenser-tradar input[name^='trådar']").on("click", function() {
		const t = $(this);
		$.post("/ajax/PreferenserAjax.php", {
			trådar: t.val()
		});
	});

	/* api */
	$("#preferenser-spara-api").on("click", function(e) {
		e.preventDefault();
		$.post("/ajax/PreferenserAjax.php", {
			api: $("#preferenser-api").val()
		}).done(function() {
			window.location.replace('/');
		});
	});

	/* php */
	$("#preferenser-spara-php").on("click", function(e) {
		e.preventDefault();
		$.post("/ajax/PreferenserAjax.php", {
			php: $("#preferenser-php").val(),
			fcgi: $("#preferenser-fcgi").val()
		}).done(function() {
			window.location.replace('/');
		});
	});

	/* avlusa */
	$("#preferenser-avlusa").controlgroup();
	$("#preferenser-avlusa input[name^='avlusa']").on("click", function() {
		const t = $(this);
		$.post("/ajax/PreferenserAjax.php", {
			avlusa: t.val()
		});
	});

	/* antal rader */
	$("#max_rader").on("change", function(e) {
		e.preventDefault();
		$.post("/ajax/PreferenserAjax.php", {
			antal_rader: $("#max_rader").val()
		}).done(function() {
			$("#max_rader").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	/* mål utdelning */
	$("#u13_min, #u13_max").on("change", function(e) {
		e.preventDefault();
		const t = $(this);
		$.post("/ajax/PreferenserAjax.php", {
			u13_min: $("#u13_min").val(),
			u13_max: $("#u13_max").val()
		}).done(function() {
			t.fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});
})(); // preferenser

(function() { // investeringar
	$("#investera_visa_antal").on("change", function(e) {
		e.preventDefault();
		$.post("/ajax/InvesteraAjax.php", {
			visa_antal: $("#investera_visa_antal").val()
		}).done(function() {
			window.location.replace('/');
		});
	});
})(); // investeringar

(function() { // speltyp
	$("#speltyp").selectmenu({
		width: 200
	});

	$("#speltyp").on("selectmenuchange", function(e) {
		e.preventDefault();
		$.post("/ajax/SpelAjax.php", {
			ändra_speltyp: $(this).val()
		}).done(function() {
			window.location.replace('/');
		});
	});
})(); // speltyp

(function() { // sekvenser
	$("#sekvens").selectmenu({
		width: 80
	});

	/* ändra sekvens */
	$("#sekvens").on("selectmenuchange", function(e) {
		e.preventDefault();
		$.post("/ajax/SpelAjax.php", {
			ändra_sekvens: $(this).val()
		}).done(function() {
			window.location.replace('/');
		});
	});

	/* ny sekvens */
	$("#ny_sekvens").on("click", function(e) {
		e.preventDefault();
		$.post("/ajax/SpelAjax.php", {
			ny_sekvens: true
		}).done(function() {
			window.location.replace('/');
		});
	});

	/* radera sekvens */
	$("#radera_sekvens").on("click", function(e) {
		e.preventDefault();
		const omgång = $("#omgång").val();
		const speltyp = $("#speltyp").val();
		const sekvens = $("#sekvens").val();
		if (confirm('Radera sekvens ' + sekvens +' (omgång ' + omgång + ', speltyp ' + speltyp + ')?')) {
			$.post("/ajax/SekvensAjax.php", {
				radera_sekvens: sekvens,
				omgång: omgång,
				speltyp: speltyp
			}).done(function() {
				window.location.replace('/');
			});
		}
	});
})(); // sekvenser

(function() { // omgång
	/* ändra omgång */
	$("#genererad_omgång").selectmenu({
		width: 130
	});

	$("#genererad_omgång").on("selectmenuchange", function(e) {
		e.preventDefault();
		$.post("/ajax/SpelAjax.php", {
			ändra_omgång: $(this).val()
		}).done(function() {
			window.location.replace('/');
		});
	});

	$("#föregående, #nästa").on("click", function(e) {
		e.preventDefault();
		$.post("/ajax/SpelAjax.php", {
			ändra_omgång: $(this).val()
		}).done(function() {
			window.location.replace('/');
		});
	});

	$("#manuell").on("submit", function(e) {
		e.preventDefault();
		$.post("/ajax/SpelAjax.php", {
			ändra_omgång: $("#manuell_omgång").val()
		}).done(function() {
			window.location.replace('/');
		});
	});

	/* spara omgång */
	$("#spara_omgång").on("click", function(e) {
		e.preventDefault();
		$.post("/ajax/MatchdataAjax.php", {
			spara_matchdata: $("#matchdata").serialize()
		}).done(function() {
			window.location.replace('/');
		});
	});

	/* radera omgång */
	$("#radera_omgång").on("click", function(e) {
		e.preventDefault();
		const omgång = $("#omgång").val();
		const speltyp = $("#speltyp").val();
		if (confirm('Radera omgång ' + omgång + ', speltyp ' + speltyp + '?')) {
			$.post("/ajax/OmgangAjax.php", {
				radera_omgång: omgång,
				speltyp: speltyp
			}).done(function() {
				window.location.replace('/');
			});
		}
	});

	$("#strategi").on("change", function() {
		$.post("/ajax/OmgangAjax.php", {
			strategi: $("#strategi").val()
		}).done(function() {
			$("#strategi").fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		});
	});

	$(".omgångstabell").on("click", "#omgång_strategi_p, #omgång_kommentar_p", function() {
		$("#omgång_strategi").toggle();
		$("#omgång_kommentar").toggle();
	});


})(); // omgång

(function() { // generering
	$("#spara_generering").on("click", function(e) {
		e.preventDefault();
		var investera_sparad = 0;
		if ($("#investera_sparad").is(":checked")) {
			investera_sparad = 1;
		}
		$.post("/ajax/OmgangAjax.php", {
			investera_sparad: investera_sparad
		}).done(function() {
			window.location.replace('/');
		});
	});
})(); // generering

(function() { // tipsdata
	$("#hämta_tipsdata, #hämta_tipsresultat").on("click", function(e) {
		e.preventDefault();
		$.post("/ajax/TipsdataAjax.php", {
			hämta_tips: this.id
		}).done(function() {
			window.location.replace('/');
		});
	});
})(); // tipsdata

(function() { // matchdata
	$("#matchtabell").on("click", "td.återvinn", function(e) {
		e.preventDefault();
		const p = $(this).parent();
		var i, streck;
		for (i = 0; i < 3; i++) {
			streck = p.find("td.s" + i).text();
			p.find("td.o" + i + " input").val(streck);
		}
	});

	$("#favorittabell tr").hover(function() {
		const childNum = $(this).index() + 1;
		const data_match = parseInt($(this).find("td:eq(0)").text()) + 1;
		if (childNum > 1) {
			$('#favorittabell tr:nth-child(' + childNum + ')').css("outline", "medium solid red");
			$('#matchtabell tr:nth-child(' + data_match + ')').css("outline", "medium solid red");
		}
	}, function() {
		const childNum = $(this).index() + 1;
		const data_match = parseInt($(this).find("td:eq(0)").text()) + 1;
		if (childNum > 1) {
			$('#favorittabell tr:nth-child(' + childNum + ')').css("outline", "none");
			$('#matchtabell tr:nth-child(' + data_match + ')').css("outline", "none");
		}
	});

	$("#matchtabell tr").hover(function() {
		const childNum = $(this).index() + 1;
		$('#matchtabell tr:nth-child(' + childNum + ')').css("outline", "medium solid red");
	}, function() {
		const childNum = $(this).index() + 1;
		$('#matchtabell tr:nth-child(' + childNum + ')').css("outline", "none");
	});
})(); // matchdata


(function() { // scheman
	"use strict";

	$("#flikar-scheman").on("click", "#schema_nytt", function(e) {
		e.preventDefault();
		$.post("/ajax/SchemanAjax.php", {
			nytt_schema: 1
		}).done(function(data) {
			$("#schemamall").replaceWith(data);
			$("#schema_spara, #schema_nytt").button();
		});
	});

	$("#flikar-scheman").on("click", "#schema_spara", function(e) {
		e.preventDefault();
		$.post("/ajax/SparaSchemaAjax.php", {
			spara_nytt_schema: $("#schemadata").serialize()
		}).done(function(data) {
			window.location.replace('/');
		});
	});

	$("button.schema-radera", "button.schema-tillämpa").keypress(function(e) {
		if (e.which === '10' || e.which === '13') e.preventDefault();
	})

	$("button.schema-radera").on("click", function(e) {
		e.preventDefault();
		const t = $(this);
		const id = t.prop("id").split("-")[2];

		if (confirm('Radera schema ' + id + '?')) {
			$.post("/ajax/SparaSchemaAjax.php", {
				radera_schema: id
			}).done(function() {
				$("#schema-" + id).remove();
			});
		}
	});

	$("button.schema-tillämpa").on("click", function(e) {
		e.preventDefault();
		const t = $(this);
		const id = t.prop("id").split("-")[2];
		$.post("/ajax/SchemanAjax.php", {
			tillämpa_schema: $("#schema-" + id).serialize(),
			id: id // ?
		}).done(function() {
			window.location.replace('/');
		});
	});

	$("button.schema-uppdatera").on("click", function(e) {
		e.preventDefault();
		const t = $(this);
		const id = t.prop("id").split("-")[2];
		uppdatera_schema(id);
	});

	$("tr.radera").on("click", function(e) {
		const t = $(this);
		const id = parseInt(t.closest("form").attr("id").replace ( /[^\d.]/g, ''));
		$(this).remove();
		uppdatera_schema(id);
	});

	function uppdatera_schema(id) {
		$.post("/ajax/SparaSchemaAjax.php", {
			uppdatera_schema: $("#schema-" + id).serialize(),
			id: id
		}).done(function(data) {
			if (data) {
				$("#schema-uppdatera-" + id).fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			} else {
				$("#schema-uppdatera-" + id).html('❌ Uppdatera')
			}
			$("schema-uppdatera-#" + id).blur();
		});
	}

})(); // Scheman

(function() { // timer
	timer();

	function timer() {
		const snd = new Audio('../snd/click.mp3')
		const sluttid = new Date($("#datumtid").text()).getTime();
		const nedräkning = setInterval(function() {
			var nu = new Date().getTime();
			var t = sluttid - nu;
			if (t < 1_000) stäng_nedräkning(nedräkning);

			var dagar = Math.floor(t / 86_400_000);
			var timmar = Math.floor((t % 86_400_000) / 3_600_000);
			var minuter = Math.floor((t % 3_600_000) / 60_000);
			var sekunder = Math.floor((t % 60_000) / 1_000);
			var minuter_kvar = t / 60_000;
			var text = "";

			if (
				(minuter_kvar <= 10 && minuter_kvar >= 9.5) ||
				(minuter_kvar <= 5 && minuter_kvar >= 4.5) ||
				(minuter_kvar <= 2 && minuter_kvar >= 1.5)
			) snd.play();

			if (dagar) text += String(dagar) + ":";
			if (dagar || timmar) text += String(timmar).padStart(2, '0') + ":";
			if (timmar || minuter) text += String(minuter).padStart(2, '0') + ":";
			if (minuter || sekunder) text += String(sekunder).padStart(2, '0');

			$("#tidsangivelse").text(text);
			$("#tidsangivelse").on("click", function() {
				stäng_nedräkning(nedräkning);
			});
		}, 1_000);
	}

	function stäng_nedräkning(nedräkning) {
		clearInterval(nedräkning);
		$("#tidsangivelse").remove();
	}
})(); // timer
