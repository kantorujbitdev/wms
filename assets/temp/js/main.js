$(document).ready(function () {
	// Apply custom colors to DataTables if used
	if (typeof $.fn.DataTable !== "undefined") {
		$.extend(true, $.fn.dataTable.defaults, {
			dom:
				'<"row"<"col-sm-6"l><"col-sm-6"f>>' +
				'<"row"<"col-sm-12"tr>>' +
				'<"row"<"col-sm-5"i><"col-sm-7"p>>',
			pageLength: 5,
			lengthMenu: [
				[5, 10, 25, 50, 100],
				[5, 10, 25, 50, 100],
			],
			responsive: true,
			language: {
				search: "_INPUT_",
				searchPlaceholder: "Search records...",
				lengthMenu: "Show _MENU_ entries",
				info: "Showing _START_ to _END_ of _TOTAL_ entries",
				paginate: {
					first: "First",
					last: "Last",
					next: "Next",
					previous: "Previous",
				},
			},
		});
	}

	// Initialize tooltips
	var tooltipTriggerList = [].slice.call(
		document.querySelectorAll('[data-bs-toggle="tooltip"]')
	);
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl);
	});

	// Initialize popovers
	var popoverTriggerList = [].slice.call(
		document.querySelectorAll('[data-bs-toggle="popover"]')
	);
	var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
		return new bootstrap.Popover(popoverTriggerEl);
	});

	// Auto-hide alerts after 5 seconds
	setTimeout(function () {
		$(".alert").fadeOut();
	}, 5000);

	// Custom color scheme based on CSS variables
	const colorScheme = {
		primary: "#4361ee",
		secondary: "#3f37c9",
		success: "#4cc9f0",
		info: "#4895ef",
		warning: "#f72585",
		danger: "#b5179e",
		light: "#f8f9fa",
		dark: "#212529",
	};

	// Apply custom colors to charts if Chart.js is used
	if (typeof Chart !== "undefined") {
		Chart.defaults.color = colorScheme.dark;
		Chart.defaults.borderColor = colorScheme.light;

		// Custom chart colors
		window.chartColors = {
			primary: colorScheme.primary,
			secondary: colorScheme.secondary,
			success: colorScheme.success,
			info: colorScheme.info,
			warning: colorScheme.warning,
			danger: colorScheme.danger,
			light: colorScheme.light,
			dark: colorScheme.dark,
		};
	}

	// Form validation styling
	$("form").on("submit", function () {
		$(this).find(".is-invalid").removeClass("is-invalid");
	});

	// Custom file input styling
	$(".custom-file-input").on("change", function () {
		var fileName = $(this).val().split("\\").pop();
		$(this).next(".custom-file-label").html(fileName);
	});

	// Print button functionality
	$(".print-btn").on("click", function () {
		window.print();
	});

	// Export button functionality
	$(".export-btn").on("click", function () {
		var format = $(this).data("format");
		var url = $(this).data("url");

		if (format && url) {
			window.location.href = url + "?format=" + format;
		}
	});

	// Date picker initialization if used
	if (typeof $.fn.datepicker !== "undefined") {
		$(".datepicker").datepicker({
			format: "dd-mm-yyyy",
			autoclose: true,
			todayHighlight: true,
		});
	}

	// Time picker initialization if used
	if (typeof $.fn.timepicker !== "undefined") {
		$(".timepicker").timepicker({
			showSeconds: true,
			showMeridian: false,
			defaultTime: "current",
		});
	}

	// Custom select styling
	$(".custom-select").select2({
		theme: "bootstrap4",
		width: "100%",
	});

	// Toggle password visibility
	$(".toggle-password").on("click", function () {
		var input = $(this).prev("input");
		var icon = $(this).find("i");

		if (input.attr("type") === "password") {
			input.attr("type", "text");
			icon.removeClass("fa-eye").addClass("fa-eye-slash");
		} else {
			input.attr("type", "password");
			icon.removeClass("fa-eye-slash").addClass("fa-eye");
		}
	});

	// Loading spinner for AJAX requests
	$(document).ajaxStart(function () {
		$("#loading-spinner").show();
	});

	$(document).ajaxStop(function () {
		$("#loading-spinner").hide();
	});

	// Custom AJAX error handling
	$(document).ajaxError(function (event, jqXHR, settings, thrownError) {
		var errorMessage = "An error occurred while processing your request.";

		if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
			errorMessage = jqXHR.responseJSON.message;
		}

		showConfirmationModal({
			title: "Error",
			message: errorMessage,
			confirmText: "Ya, Hapus",
			onfirmButtonColor: colorScheme.primary,
		});
	});

	// Smooth scrolling for anchor links
	$('a[href*="#"]')
		.not('[href="#"]')
		.not('[href="#0"]')
		.on("click", function (event) {
			if (
				location.pathname.replace(/^\//, "") ===
					this.pathname.replace(/^\//, "") &&
				location.hostname === this.hostname
			) {
				var target = $(this.hash);
				target = target.length
					? target
					: $("[name=" + this.hash.slice(1) + "]");

				if (target.length) {
					event.preventDefault();
					$("html, body").animate(
						{
							scrollTop: target.offset().top - 70,
						},
						1000
					);
				}
			}
		});

	// Back to top button
	$(window).on("scroll", function () {
		if ($(this).scrollTop() > 100) {
			$("#back-to-top").fadeIn();
		} else {
			$("#back-to-top").fadeOut();
		}
	});

	$("#back-to-top").on("click", function () {
		$("html, body").animate(
			{
				scrollTop: 0,
			},
			800
		);
		return false;
	});
});
