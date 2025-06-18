(function() { // Distribution
	"use strict";

	function distributionsval(id1, id2) {
		$(id1 + ', ' + id2).on("change", function(e) {
			e.preventDefault();
			$.post("/moduler/ajax/DistributionAjax.php", {
				distribution_minprocent: $(id1).val(),
				distribution_maxprocent: $(id2).val()
			}).done(function() {
				window.location.replace('/');
			});
		});
	}

	distributionsval("#distribution_minprocent", "#distribution_maxprocent");
	distributionsval("#distribution_minprocent_ext", "#distribution_maxprocent_ext");

	$("#grunddistribution_minprocent, #grunddistribution_maxprocent").on("change", function(e) {
		e.preventDefault();
		const t = $(this);
		$.post("/moduler/ajax/DistributionAjax.php", {
			grund_minprocent: $("#grunddistribution_minprocent").val(),
			grund_maxprocent: $("#grunddistribution_maxprocent").val()
		}).done(function() {
			t.fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#grunddistribution").on("click", function() {
		$("#distribution_minprocent").val($("#grunddistribution_minprocent").val());
		$("#distribution_maxprocent").val($("#grunddistribution_maxprocent").val());
		$("#distribution_minprocent").change();
	});

	$("#distribution_attraktionsfaktor").on("change", function(e) {
		e.preventDefault();
		$.post("/moduler/ajax/DistributionAjax.php", {
			attraktionsfaktor: $("#distribution_attraktionsfaktor").val()
		}).done(function() {
			$("#distribution_attraktionsfaktor").fadeTo("slow", 0.5).fadeTo("slow", 1.0);
		});
	});

	$("#distribution_attraktionsfaktor_min").on("click", function() {
		$("#distribution_attraktionsfaktor").val('1');
		$("#distribution_attraktionsfaktor").change();
	});

	$("#distribution_attraktionsfaktor_max").on("click", function() {
		$("#distribution_attraktionsfaktor").val('1594323');
		$("#distribution_attraktionsfaktor").change();
	});

	$(".distribution_schema").on("click", function(e) {
		e.preventDefault();
		const t = $(this);
		const v = JSON.parse(t.val());
		$("#distribution_minprocent").val(v[0]);
		$("#distribution_maxprocent").val(v[1]);
		$("#distribution_minprocent").change();
	});

})(); // Distribution
