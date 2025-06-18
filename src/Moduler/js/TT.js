import * as g from '../../js/gemensamt.js';

(function() { // TT
	"use strict";

	$("#topptipstyp").selectmenu({
		width: 200
	});

	$("#tt_kod, #tt_rkod").selectmenu({
		width: 350
	});

	function sätt_klass(t, tecken) {
		if (t.val().length === 0) {
			t.removeClass("vinst förlust storförlust");
		} else {
			var s = t.attr("data-sortering");
			var e = $("#tt_enkelrad" + s).text();
			var klass = "förlust";
			if (tecken === '1') {
				klass = (e === '1') ? "vinst" : "storförlust";
			} else if (tecken === '2') {
				klass = (e === '2') ? "vinst" : "storförlust";
			}
			t.addClass(klass);
		}
	};

	/* Reduktion */
	var HELGARDERINGAR, HALVGARDERINGAR;
	bestäm_r_värden();

	function bestäm_r_värden() {
		HELGARDERINGAR = parseInt($("#tt_rkodanalys .antal_helgarderingar").val());
		HALVGARDERINGAR = parseInt($("#tt_rkodanalys .antal_halvgarderingar").val());
	};

	$("#tt_rkod").on("selectmenuchange", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/TTAjax.php", {
			tt_rkod: $("#tt_rkod").val()
		}).done(function(res) {
			$("#tt_rkod" + "-button").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			if (res) {
				$("#tt_rkodanalys").html(res);
				bestäm_r_värden();
				uppdatera_garderingar();
				fyll_r(HELGARDERINGAR, HALVGARDERINGAR);
				$("#tt_r_schema").val("[" + HELGARDERINGAR + "," + HALVGARDERINGAR +  "]");
				$("#tt_r_schema").text($("#tt_rkod").val());
			}
		});
	});

	function uppdatera_garderingar() {
		antal_helgarderingar();
		antal_halvgarderingar();
	}

	function antal_helgarderingar() {
		var antal_helgarderingar = 0;
		var t1, tx, t2;
		$("#topptipstabell tr.tt-match").each(function(i) {
			var t = $(this);
			t1 = t.find("td input.tt_r[data-ruta=1]");
			tx = t.find("td input.tt_r[data-ruta=X]");
			t2 = t.find("td input.tt_r[data-ruta=2]");
			if (t1.val() && tx.val() && t2.val()) antal_helgarderingar++;
		});

		var t = $("#tt_antal_helgarderingar");
		t.text(antal_helgarderingar);
		if (antal_helgarderingar === HELGARDERINGAR) t.removeClass("storförlust").addClass("vinst");
		else t.removeClass("vinst").addClass("storförlust");
	}

	function antal_halvgarderingar() {
		var antal_halvgarderingar = 0;
		var t1, tx, t2;
		$("#topptipstabell tr.tt-match").each(function(i) {
			var t = $(this);
			t1 = t.find("td input.tt_r[data-ruta=1]");
			tx = t.find("td input.tt_r[data-ruta=X]");
			t2 = t.find("td input.tt_r[data-ruta=2]");
			if (!(t1.val() && tx.val() && t2.val())) {
				if ((t1.val() && tx.val()) || (t1.val() && t2.val()) || (tx.val() && t2.val())) {
					antal_halvgarderingar++;
				}
			}
		});

		const t = $("#tt_antal_halvgarderingar");
		t.text(antal_halvgarderingar);
		if (antal_halvgarderingar === HALVGARDERINGAR) t.removeClass("storförlust").addClass("vinst");
		else t.removeClass("vinst").addClass("storförlust");
	}

	uppdatera_garderingar();

	function uppdatera_reduktion() {
		$.post("/moduler/ajax/TTAjax.php", {
			tt_reduktion: $("#topptipstabell input[name^='tt_reduktion']").serialize()
		});
		uppdatera_garderingar();
	}

	function rensa_reduktion() {
		$("#topptipstabell input[name^='tt_reduktion']").each(function() {
			$(this).val('');
			$(this).removeClass("vinst förlust storförlust");
		});
	}

	$("#topptipstabell").on("click", ".tt-helgardering", function() {
		const t = $(this);
		const p = t.parents("tr");
		const t1 = p.find("input.tt_r[data-ruta=1]");
		const tx = p.find("input.tt_r[data-ruta=X]");
		const t2 = p.find("input.tt_r[data-ruta=2]");

		t1.val('1'); tx.val('X'); t2.val('2');

		$.each([t1, tx, t2], function(i, tecken) {
			tecken.removeClass("vinst förlust storförlust");
			sätt_klass(tecken, tecken.val());
		});

		uppdatera_reduktion();
	});

	$("#topptipstabell").on("click", ".tt-rensa-gardering", function() {
		const t = $(this);
		const p = t.parents("tr");
		const t1 = p.find("input.tt_r[data-ruta=1]");
		const tx = p.find("input.tt_r[data-ruta=X]");
		const t2 = p.find("input.tt_r[data-ruta=2]");

		t1.val(''); tx.val(''); t2.val('');

		$.each([t1, tx, t2], function(i, tecken) {
			tecken.removeClass("vinst förlust storförlust");
		});

		uppdatera_reduktion();
	});

	$("#topptipstabell").on("click keyup", "input.tt_r", function() {
		var t = $(this);
		var p = t.parents("tr");
		var t1 = p.find("input.tt_r[data-ruta=1]");
		var tx = p.find("input.tt_r[data-ruta=X]");
		var t2 = p.find("input.tt_r[data-ruta=2]");

		if (t.attr("data-ruta") === '1') {
			t1.val('1'); tx.val('X'); t2.val('');
		} else if (t.attr("data-ruta") === 'X') {
			t1.val('1'); tx.val(''); t2.val('2');
		} else if (t.attr("data-ruta") === '2') {
			t1.val(''); tx.val('X'); t2.val('2');
		}

		$.each([t1, tx, t2], function(i, tecken) {
			tecken.removeClass("vinst förlust storförlust");
			sätt_klass(tecken, tecken.val());
		});
	});

	$("#topptipstabell").on("click keyup", "input[name^='tt_reduktion']", function() {
		uppdatera_reduktion();
	});

	/* Gruppkod */
	$("#tt_kod").on("selectmenuchange", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/TTAjax.php", {
			tt_kod: $("#tt_kod").val()
		}).done(function(res) {
			$("#tt_kod" + "-button").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			if (res) $("#tt_kodanalys").html(res);
		});
	});

	$("#tt_antal_rader").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/TTAjax.php", {
			tt_antal_rader: $("#tt_antal_rader").val()
		}).done(function(){
			$("#tt_antal_rader").fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		});
	});

	$("#modulflikar-TT table.topptipstabell tr").hover(function() {
		const childNum = $(this).index() + 1;
		$('#modulflikar-TT table.topptipstabell tr:nth-child(' + childNum + ')').css("outline", "medium solid red");
	}, function() {
		const childNum = $(this).index() + 1;
		$('#modulflikar-TT table.topptipstabell tr:nth-child(' + childNum + ')').css("outline", "none");
	});

	/* spikar */
	$("input.tt_spik1").on("click keyup", function() {
		this.value = this.value.length === 0 ? '1' : '';
		sätt_klass($(this), this.value);
	});

	$("input.tt_spik2").on("click keyup", function() {
		this.value = this.value.length === 0 ? 'X' : '';
		sätt_klass($(this), this.value);
	});

	$("input.tt_spik3").on("click keyup", function() {
		this.value = this.value.length === 0 ? '2' : '';
		sätt_klass($(this), this.value);
	});

	$("#tt_rensa_spikar").on("click", function(e) {
		e.preventDefault();
		$("input[name^='tt_spik']").each(function() {
			this.value = '';
			$(this).removeClass("vinst förlust storförlust");
		});
		$("input[name^='tt_andel_spikar']").each(function() {
			this.value = 0;
		});
		$("input[name='tt_andel_spikar[0]']").keyup();
	});

	$("input[name^='tt_spikar'], input[name^='tt_andel_spikar']").on("click keyup", function() {
		const t = $(this);
		$.post("/moduler/ajax/TTAjax.php", {
			tt_spikar: $("input[name^='tt_spikar']").serialize(),
			tt_andel_spikar: $("input[name^='tt_andel_spikar']").serialize(),
		}).done(function(res) {
			if (res) {
				t.fadeTo("slow", 0.5).fadeTo("slow", 1.0);
				$("#tt_spikar_notis").text("✅").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			} else {
				$("#tt_spikar_notis").text("❌").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			}
		});
	});

	$("#topptipstyp").on("selectmenuchange", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/TTAjax.php", {
			topptipstyp: $("#topptipstyp").val()
		}).done(function() {
			$("#topptipstyp" + "-button").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#tt_odds_rätt_min, #tt_odds_rätt_max, #tt_antal_1_min, #tt_antal_1_max, #tt_antal_X_min, #tt_antal_X_max, #tt_antal_2_min, #tt_antal_2_max").on("change", function(e) {
		e.preventDefault();
		const t = $(this);
		$.post("/moduler/ajax/TTAjax.php", {
			tt_gränser: 1,
			tt_odds_rätt_min: $("#tt_odds_rätt_min").val(),
			tt_odds_rätt_max: $("#tt_odds_rätt_max").val(),
			tt_antal_1_min: $("#tt_antal_1_min").val(),
			tt_antal_1_max: $("#tt_antal_1_max").val(),
			tt_antal_X_min: $("#tt_antal_X_min").val(),
			tt_antal_X_max: $("#tt_antal_X_max").val(),
			tt_antal_2_min: $("#tt_antal_2_min").val(),
			tt_antal_2_max: $("#tt_antal_2_max").val()
		}).done(function() {
			t.fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#tt_r_schema").on("click", function(e) {
		e.preventDefault();
		const t = $(this);
		const v = JSON.parse(t.val());
		t.fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		fyll_r(v[0], v[1]);
	});

	function fyll_r(H, h) {
		H = parseInt(H);
		h = parseInt(h);
		var klass, vinst, förlust, index_v, index_f;
		rensa_reduktion();

		for (let i = 7; i >= 0; i--) {
			var j = i + 1;
			var s = $("#tt_sortering" + j).text();
			var e = $("#tt_enkelrad" + j).text();
			if (e === '1' || e === 'X') {
				vinst = 1;
				förlust = 2;
				index_v = 0;
				index_f = 2;
			} else if (e === '2') {
				vinst = 2;
				förlust = 1;
				index_v = 2;
				index_f = 0;
			}
			if (i > 7 - H) {
				$("#topptipstabell input[name='tt_reduktion[" + (s - 1) + "][" + index_f + "]']").val(förlust).addClass("storförlust");
			}
			if (i > 7 - H - h) {
				$("#topptipstabell input[name='tt_reduktion[" + (s - 1) + "][" + index_v + "]']").val(vinst).addClass("vinst");
				$("#topptipstabell input[name='tt_reduktion[" + (s - 1) + "][" + 1 + "]']").val('X').addClass("förlust");
			}
		}

		uppdatera_reduktion();
	}

	$("#modulflikar-TT").on("click", ".tt_schema", function(e) {
		e.preventDefault();
		const t = $(this);
		const v = JSON.parse(t.val());
		t.fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		fyll(v[0], v[1], v[2], v[3]);
	});

	$("#modulflikar-TT").on("click", ".tt_kombo", function(e) {
		e.preventDefault();
		const t = $(this);
		const v = JSON.parse(t.val());
		const rsys = 'R_' + v[4].join('_');
		t.fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		fyll(v[0], v[1], v[2], v[3]);
		$("#tt_rkod").val(rsys).selectmenu("refresh").trigger("selectmenuchange");
	});

	function fyll(n, vi, vx, vt) {
		var klass;
		$("#tt_rensa_spikar").click();

		for (let i = 0; i < n; i++) {
			for (let j = vi[2 * i]; j <= vi[2 * i + 1]; j++) {
				var s = $("#tt_sortering" + j).text();
				var e = $("#tt_enkelrad" + j).text();
				if (vx[i] < 0) {
					if (e === '1' || e === 'X') e = 2;
					else if (e === '2') e = 1;
					$("#topptipstabell input[name='tt_spikar[" + i + "][" + (s - 1) + "][" + (2 * (e - 1)) + "]']").val(e).addClass("storförlust");
					if (vt[i] > 0) {
						$("#topptipstabell input[name='tt_spikar[" + i + "][" + (s - 1) + "][" + 1 + "]']").val('X').addClass("förlust");
					}
				} else {
					if (e === 'X') e = 1;
					$("#topptipstabell input[name='tt_spikar[" + i + "][" + (s - 1) + "][" + (2 * (e - 1)) + "]']").val(e).addClass("vinst");
					if (vt[i] > 0) {
						$("#topptipstabell input[name='tt_spikar[" + i + "][" + (s - 1) + "][" + 1 + "]']").val('X').addClass("förlust");
					}
				}
			}
			$("#topptipstabell input[name='tt_andel_spikar[" + i + "]']").val(Math.abs(vx[i]));
		}

		$("input[name='tt_andel_spikar[0]']").keyup();
	}

	$("#tt_pröva_spikar, #tt_pröva_täckning, #tt_pröva_t_intv, #tt_pröva_o_intv, #tt_pröva_reduktion").on("click", function() {
		const t = $(this);
		const id = t.attr("id");
		const status = $(this).prop("checked");
		$.post("/moduler/ajax/TTAjax.php", {
			tt_status: status,
			tt_id: id
		});
	});

	/* spara omgång */
	$("#tt_spara_omgång").on("click", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/TTAjax.php", {
			tt_matchdata: $("#tt_matchdata").serialize()
		}).done(function() {
			window.location.replace('/');
		});
	});

	$("#tt_strategi").on("change", function() {
		$.post("/moduler/ajax/TTAjax.php", {
			tt_strategi: $("#tt_strategi").val()
		}).done(function() {
			$("#tt_strategi").fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		});
	});

	/* bokföring */
	$(".tt_radera_bokföring").on("click", function() {
		const t = $(this);
		const p = t.parent();
		const id = p.attr("data-tt-bokföring-id");
		if (confirm('Radera bokföring ' + id + '?')) {
			$.post("/moduler/ajax/TTAjax.php", {
				tt_radera_bokföring: true,
				id: id
			}).done(function() {
				p.remove();
			});
		}
	});

	$("#tt_bokföringstabell").on("change", "#tt_visa_antal_bokföringar", function() {
		const t = $(this);
		$.post("/moduler/ajax/TTAjax.php", {
			tt_visa_antal_bokföringar: t.val()
		}).done(function() {
			t.fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		});
	})

	$("#tt_bokföringstabell input[name='rätt'], input[name='vinst']").on("change", function() {
		const t = $(this);
		const p = t.parents("tr");
		$.post("/moduler/ajax/TTAjax.php", {
			tt_uppdatera_bokföring: true,
			id: p.attr("data-tt-bokföring-id"),
			kolumn: t.attr("name"),
			värde: t.val(),
		}).done(function() {
			t.fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		});
	})
})(); // TT
