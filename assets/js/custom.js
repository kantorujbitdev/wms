/*
 * Custom JavaScript for Warehouse Management System
 */

$(document).ready(function () {
	// Initialize tooltips
	$('[data-toggle="tooltip"]').tooltip();

	// Initialize popovers
	$('[data-toggle="popover"]').popover();

	// Auto hide flash messages after 5 seconds
	setTimeout(function () {
		$(".alert").fadeOut();
	}, 5000);

	// Confirm delete actions
	$(".delete-btn").click(function (e) {
		e.preventDefault();
		var url = $(this).attr("href");

		Swal.fire({
			title: "Apakah Anda yakin?",
			text: "Anda tidak akan dapat mengembalikan data ini!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, hapus!",
			cancelButtonText: "Batal",
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = url;
			}
		});
	});

	// Format currency inputs
	$(".currency").on("input", function (e) {
		$(this).val(formatCurrency($(this).val()));
	});

	// Format number function
	function formatNumber(n) {
		return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}

	// Format currency function
	function formatCurrency(input, blur) {
		var input_val = input;

		if (input_val === "") {
			return;
		}

		var original_len = input_val.length;
		var caret_pos = input.selectionStart;

		input_val = formatNumber(input_val);
		input_val = "Rp " + input_val;

		input_val = input_val.substring(0, 20);

		if (original_len != input_val.length) {
			if (caret_pos === original_len) {
				caret_pos = input_val.length;
			}
		}

		return input_val;
	}

	// Date picker initialization
	$(".datepicker").datepicker({
		format: "yyyy-mm-dd",
		autoclose: true,
		todayHighlight: true,
	});

	// DateTime picker initialization
	$(".datetimepicker").datetimepicker({
		format: "YYYY-MM-DD HH:mm:ss",
	});

	// Select2 initialization
	$(".select2").select2({
		theme: "bootstrap4",
	});

	// Data table initialization
	$(".datatable").DataTable({
		responsive: true,
		pageLength: 10,
		language: {
			url: '<?php echo base_url("assets/js/dataTables.indonesian.json"); ?>',
		},
	});

	// Chart initialization
	if (typeof Chart !== "undefined") {
		// Stock Movement Chart
		var stockChartCanvas = $("#stockChart").get(0).getContext("2d");
		var stockChartData = {
			labels: [
				"Jan",
				"Feb",
				"Mar",
				"Apr",
				"May",
				"Jun",
				"Jul",
				"Aug",
				"Sep",
				"Oct",
				"Nov",
				"Dec",
			],
			datasets: [
				{
					label: "Barang Masuk",
					backgroundColor: "rgba(60,141,188,0.9)",
					borderColor: "rgba(60,141,188,0.8)",
					pointRadius: false,
					pointColor: "#3b8bba",
					pointStrokeColor: "rgba(60,141,188,1)",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(60,141,188,1)",
					data: [65, 59, 80, 81, 56, 55, 40, 45, 60, 70, 75, 80],
				},
				{
					label: "Barang Keluar",
					backgroundColor: "rgba(210, 214, 222, 1)",
					borderColor: "rgba(210, 214, 222, 1)",
					pointRadius: false,
					pointColor: "rgba(210, 214, 222, 1)",
					pointStrokeColor: "#c1c7d1",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(220,220,220,1)",
					data: [28, 48, 40, 19, 86, 27, 90, 75, 65, 55, 60, 70],
				},
			],
		};

		var stockChartOptions = {
			maintainAspectRatio: false,
			responsive: true,
			legend: {
				display: false,
			},
			scales: {
				xAxes: [
					{
						gridLines: {
							display: false,
						},
					},
				],
				yAxes: [
					{
						gridLines: {
							display: false,
						},
					},
				],
			},
		};

		var stockChart = new Chart(stockChartCanvas, {
			type: "line",
			data: stockChartData,
			options: stockChartOptions,
		});
	}

	// Print functionality
	$(".print-btn").click(function () {
		var printContents = $(this).closest(".card").html();
		var originalContents = document.body.innerHTML;

		document.body.innerHTML = printContents;

		window.print();

		document.body.innerHTML = originalContents;
	});

	// Export functionality
	$(".export-btn").click(function (e) {
		e.preventDefault();
		var url = $(this).attr("href");

		Swal.fire({
			title: "Ekspor Data",
			text: "Pilih format ekspor:",
			icon: "info",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Excel",
			cancelButtonText: "PDF",
			showDenyButton: true,
			denyButtonText: "CSV",
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = url + "?format=excel";
			} else if (result.isDenied) {
				window.location.href = url + "?format=csv";
			} else {
				window.location.href = url + "?format=pdf";
			}
		});
	});
});
