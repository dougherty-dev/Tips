import * as g from '../../js/gemensamt.js';

(function() { // System
	"use strict";

	var HELGARDERINGAR, HALVGARDERINGAR;
	bestäm_r_värden();

	function bestäm_r_värden() {
		HELGARDERINGAR = parseInt($("#system_kodanalys .antal_helgarderingar").val());
		HALVGARDERINGAR = parseInt($("#system_kodanalys .antal_halvgarderingar").val());
	};

	$("#system_kod").selectmenu({
		width: 350
	});

	function sätt_klass(t, tecken) {
		if (t.val().length === 0) {
			t.removeClass("vinst förlust storförlust");
		} else {
			const s = t.attr("data-sortering");
			const e = $("#system_enkelrad" + s).text();
			var klass = "förlust";
			if (tecken === '1') {
				klass = (e === '1') ? "vinst" : "storförlust";
			} else if (tecken === '2') {
				klass = (e === '2') ? "vinst" : "storförlust";
			}
			t.addClass(klass);
			t.fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		}
	};

	/* Reduktion */
	$("#system_kod").on("selectmenuchange", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/SystemAjax.php", {
			kod: $("#system_kod").val()
		}).done(function(res) {
			$("#system_kod" + "-button").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
			if (res) {
				$("#system_kodanalys").html(res);
				bestäm_r_värden();
				uppdatera_garderingar();
				fyll_r(HELGARDERINGAR, HALVGARDERINGAR);
				$("#system_r_schema").val("[" + HELGARDERINGAR + "," + HALVGARDERINGAR +  "]");
				$("#system_r_schema").text($("#system_kod").val());
				$("#system_formatera_kod").val($("#system_kod").val());
				$("#system_formatera_kod").text("Formatera " + $("#system_kod").val());
				$("#system_exportera_kod").val($("#system_kod").val());
				$("#system_exportera_kod").text("Exportera " + $("#system_kod").val());
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
		$("#system-garderingstabell tr.system-match").each(function(i) {
			const t = $(this);
			t1 = t.find("td input.system_r[data-ruta=1]");
			tx = t.find("td input.system_r[data-ruta=X]");
			t2 = t.find("td input.system_r[data-ruta=2]");
			if (t1.val() && tx.val() && t2.val()) antal_helgarderingar++;
		});

		const t = $("#system_antal_helgarderingar");
		t.text(antal_helgarderingar);
		if (antal_helgarderingar === HELGARDERINGAR) t.removeClass("storförlust").addClass("vinst");
		else t.removeClass("vinst").addClass("storförlust");
	}

	function antal_halvgarderingar() {
		var antal_halvgarderingar = 0;
		var t1, tx, t2;
		$("#system-garderingstabell tr.system-match").each(function(i) {
			var t = $(this);
			t1 = t.find("td input.system_r[data-ruta=1]");
			tx = t.find("td input.system_r[data-ruta=X]");
			t2 = t.find("td input.system_r[data-ruta=2]");
			if (!(t1.val() && tx.val() && t2.val())) {
				if ((t1.val() && tx.val()) || (t1.val() && t2.val()) || (tx.val() && t2.val())) {
					antal_halvgarderingar++;
				}
			}
		});

		const t = $("#system_antal_halvgarderingar");
		t.text(antal_halvgarderingar);
		if (antal_halvgarderingar === HALVGARDERINGAR) t.removeClass("storförlust").addClass("vinst");
		else t.removeClass("vinst").addClass("storförlust");
	}

	uppdatera_garderingar();

	function uppdatera_reduktion() {
		$.post("/moduler/ajax/SystemAjax.php", {
			reduktion: $("#system-garderingstabell input[name^='reduktion']").serialize()
		});
		uppdatera_garderingar();
	}

	function rensa_reduktion() {
		$("#system-garderingstabell input[name^='reduktion']").each(function() {
			$(this).val('');
			$(this).removeClass("vinst förlust storförlust");
		});
	}

	$("#system-garderingstabell").on("click", ".system-helgardering", function() {
		const t = $(this);
		const p = t.parents("tr");
		const t1 = p.find("input.system_r[data-ruta=1]");
		const tx = p.find("input.system_r[data-ruta=X]");
		const t2 = p.find("input.system_r[data-ruta=2]");

		t1.val('1'); tx.val('X'); t2.val('2');

		$.each([t1, tx, t2], function(i, tecken) {
			tecken.removeClass("vinst förlust storförlust");
			sätt_klass(tecken, tecken.val());
		});

		uppdatera_reduktion();
	});

	$("#system-garderingstabell").on("click", ".system-rensa-gardering", function() {
		const t = $(this);
		const p = t.parents("tr");
		const t1 = p.find("input.system_r[data-ruta=1]");
		const tx = p.find("input.system_r[data-ruta=X]");
		const t2 = p.find("input.system_r[data-ruta=2]");

		t1.val(''); tx.val(''); t2.val('');

		$.each([t1, tx, t2], function(i, tecken) {
			tecken.removeClass("vinst förlust storförlust");
		});

		uppdatera_reduktion();
	});

	$("#system-garderingstabell").on("click keyup", "input.system_r", function() {
		const t = $(this);
		const p = t.parents("tr");
		const t1 = p.find("input.system_r[data-ruta=1]");
		const tx = p.find("input.system_r[data-ruta=X]");
		const t2 = p.find("input.system_r[data-ruta=2]");

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

	$("#system-garderingstabell").on("click keyup", "input[name^='reduktion']", function() {
		uppdatera_reduktion();
	});

	/* system */

	$("#modulflikar-System table.system-garderingstabell tr").hover(function() {
		const childNum = $(this).index() + 1;
		$('#modulflikar-System table.system-garderingstabell tr:nth-child(' + childNum + ')').css("outline", "medium solid red");
	}, function() {
		const childNum = $(this).index() + 1;
		$('#modulflikar-System table.system-garderingstabell tr:nth-child(' + childNum + ')').css("outline", "none");
	});

	$("#system-garderingstabell").on("click keyup", "input.gardering1", function() {
		this.value = this.value.length === 0 ? '1' : '';
		sätt_klass($(this), this.value);
	});

	$("#system-garderingstabell").on("click keyup", "input.gardering2", function() {
		this.value = this.value.length === 0 ? 'X' : '';
		sätt_klass($(this), this.value);
	});

	$("#system-garderingstabell").on("click keyup", "input.gardering3", function() {
		this.value = this.value.length === 0 ? '2' : '';
		sätt_klass($(this), this.value);
	});

	$("#system_rensa_system").on("click", function(e) {
		e.preventDefault();
		$("#system-garderingstabell input[name^='gardering']").each(function() {
			this.value = '';
			$(this).removeClass("vinst förlust storförlust");
		});
		$("#system-garderingstabell input[name^='andel_garderingar']").each(function() {
			this.value = 0;
		});
		$("#system-garderingstabell input[name='andel_garderingar[0]']").keyup();
	});

	$("#system-garderingstabell").on("click keyup", "input[name^='garderingar'], input[name^='andel_garderingar']", function() {
		const t = $(this);
		$.post("/moduler/ajax/SystemAjax.php", {
			garderingar: $("#system-garderingstabell input[name^='garderingar']").serialize(),
			andel_garderingar: $("#system-garderingstabell input[name^='andel_garderingar']").serialize(),
		}).done(function() {
			t.fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#pröva_garderingar, #pröva_reduktion").on("click", function() {
		const t = $(this);
		const id = t.attr("id");
		const status = $(this).prop("checked");
		$.post("/moduler/ajax/SystemAjax.php", {
			status: status,
			id: id
		});
	});

	$("#modulflikar-System").on("click", "#system_r_schema", function(e) {
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

		for (let i = 12; i >= 0; i--) {
			var j = i + 1;
			var s = $("#system_sortering" + j).text();
			var e = $("#system_enkelrad" + j).text();
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
			if (i > 12 - H) {
				$("#system-garderingstabell input[name='reduktion[" + (s - 1) + "][" + index_f + "]']").val(förlust).addClass("storförlust");
			}
			if (i > 12 - H - h) {
				$("#system-garderingstabell input[name='reduktion[" + (s - 1) + "][" + index_v + "]']").val(vinst).addClass("vinst");
				$("#system-garderingstabell input[name='reduktion[" + (s - 1) + "][" + 1 + "]']").val('X').addClass("förlust");
			}
		}

		uppdatera_reduktion();
	}

	$("#modulflikar-System").on("click", ".system_schema", function(e) {
		e.preventDefault();
		const t = $(this);
		const v = JSON.parse(t.val());
		t.fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		fyll(v[0], v[1], v[2], v[3]);
	});

	$("#modulflikar-System").on("click", ".system_kombo", function(e) {
		e.preventDefault();
		const t = $(this);
		const v = JSON.parse(t.val());
		const rsys = 'R_' + v[4].join('_');
		t.fadeTo("slow", 0.5).fadeTo("slow", 1.0).blur();
		fyll(v[0], v[1], v[2], v[3]);
		$("#system_kod").val(rsys).selectmenu("refresh").trigger("selectmenuchange");
	});

	function fyll(n, vi, vx, vt) {
		var klass;
		$("#system_rensa_system").click();

		for (let i = 0; i < n; i++) {
			for (let j = vi[2 * i]; j <= vi[2 * i + 1]; j++) {
				var s = $("#system_sortering" + j).text();
				var e = $("#system_enkelrad" + j).text();
				if (vx[i] < 0) {
					if (e === '1' || e === 'X') e = 2;
					else if (e === '2') e = 1;
					$("#system-garderingstabell input[name='garderingar[" + i + "][" + (s - 1) + "][" + (2 * (e - 1)) + "]']").val(e).addClass("storförlust");
					if (vt[i] > 0) {
						$("#system-garderingstabell input[name='garderingar[" + i + "][" + (s - 1) + "][" + 1 + "]']").val('X').addClass("förlust");
					}
				} else {
					if (e === 'X') e = 1;
					$("#system-garderingstabell input[name='garderingar[" + i + "][" + (s - 1) + "][" + (2 * (e - 1)) + "]']").val(e).addClass("vinst");
					if (vt[i] > 0) {
						$("#system-garderingstabell input[name='garderingar[" + i + "][" + (s - 1) + "][" + 1 + "]']").val('X').addClass("förlust");
					}
				}
			}
			$("#system-garderingstabell input[name='andel_garderingar[" + i + "]']").val(Math.abs(vx[i]));
		}

		$("#system-garderingstabell input[name='andel_garderingar[0]']").keyup();
	}

	$("#system_attraktionsfaktor").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/SystemAjax.php", {
			attraktionsfaktor: $("#system_attraktionsfaktor").val()
		}).done(function() {
			$("#system_attraktionsfaktor").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#system_attraktionsfaktor_min").on("click", function() {
		$("#system_attraktionsfaktor").val('1');
		$("#system_attraktionsfaktor").change();
	});

	$("#system_attraktionsfaktor_max").on("click", function() {
		$("#system_attraktionsfaktor").val('1594323');
		$("#system_attraktionsfaktor").change();
	});
})(); // System
