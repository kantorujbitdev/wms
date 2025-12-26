$(document).ready(function () {
	// Initialize DataTables for all tables with id="dataTable"
	initializeDataTables();

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
			confirmButtonColor: colorScheme.primary,
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

	// Initialize sidebar functionality
	initializeSidebar();

	// Add active class to submenu items
	$(".sidebar-sublink").each(function () {
		if ($(this).attr("href") === window.location.pathname) {
			$(this).addClass("active");
			$(this).closest(".collapse").addClass("show");
			$(this).closest(".sidebar-item").addClass("active");
		}
	});

	// Handle dropdown menu on mobile
	$(".dropdown-toggle").on("click", function (e) {
		if (window.innerWidth < 992) {
			// Let Bootstrap handle the dropdown toggle
			// This ensures it works properly on mobile
		}
	});

	// Fungsi untuk menampilkan modal konfirmasi
	window.showConfirmationModal = function (options) {
		// Set default values
		const defaults = {
			title: "Konfirmasi",
			message: "Apakah Anda yakin ingin melanjutkan?",
			confirmText: "Ya",
			confirmClass: "btn-danger",
			onConfirm: null,
			confirmUrl: null,
		};

		// Merge options with defaults
		const settings = $.extend({}, defaults, options);

		// Set modal content
		$("#confirmationModalLabel").text(settings.title);
		$("#confirmationMessage").html(settings.message);
		$("#confirmButton").text(settings.confirmText);

		// Set button class
		$("#confirmButton")
			.removeClass("btn-danger btn-success btn-primary btn-warning")
			.addClass(settings.confirmClass);

		// Remove previous event handlers
		$("#confirmButton").off("click");

		// Set confirm button action
		if (settings.confirmUrl) {
			// If URL is provided, redirect to that URL
			$("#confirmButton").on("click", function () {
				window.location.href = settings.confirmUrl;
			});
		} else if (settings.onConfirm && typeof settings.onConfirm === "function") {
			// If callback function is provided, execute it
			$("#confirmButton").on("click", function () {
				settings.onConfirm();
				$("#confirmationModal").modal("hide");
			});
		}

		// Show the modal
		$("#confirmationModal").modal("show");
	};

	// Logout confirmation modal
	$("#logoutBtn").on("click", function (e) {
		e.preventDefault();
		showConfirmationModal({
			title: "Konfirmasi Logout",
			message: "Apakah Anda yakin ingin keluar dari sistem?",
			confirmText: "Ya, Logout",
			confirmClass: "btn-danger",
			confirmUrl: $("#logoutBtn").data("url") || "/auth/logout",
		});
	});

	$(document).on("click", ".actionBtnDelete", function () {
		const url = $(this).data("url");
		const id = $(this).data("id");
		const name = $(this).data("name");

		showConfirmationModal({
			title: "Konfirmasi Hapus?",
			message:
				"Yakin ingin menghapus data: " +
				'<span class="text-danger fw-semibold">' +
				name +
				"</span>",

			confirmText: "Ya, Hapus",
			confirmClass: "btn-danger",
			confirmUrl: url + "/" + id,
		});
	});

	$("#confirmationModal").on("hidden.bs.modal", function () {
		// Hilangkan fokus dari elemen yang sebelumnya aktif
		if (document.activeElement) {
			document.activeElement.blur();
		}

		// Opsional: arahkan fokus kembali ke body atau tombol pemicu
		$("body").trigger("focus");
	});
});

// ============================================================================
// SIDEBAR FUNCTIONS
// ============================================================================

/**
 * Initialize sidebar functionality
 */
function initializeSidebar() {
	// Mobile sidebar toggle functionality
	$("#sidebarToggle")
		.off("click")
		.on("click", function () {
			$(".sidebar").toggleClass("show");
			$("body").toggleClass("sidebar-open");
		});

	// Desktop sidebar toggle functionality
	$("#sidebarToggleDesktop")
		.off("click")
		.on("click", function () {
			$(".sidebar").toggleClass("collapsed");
			$(".content-wrapper").toggleClass("expanded");

			// Save sidebar state to localStorage
			if ($(".sidebar").hasClass("collapsed")) {
				localStorage.setItem("sidebarState", "collapsed");
			} else {
				localStorage.setItem("sidebarState", "expanded");
			}
		});

	// Check saved sidebar state for desktop
	if (window.innerWidth >= 992) {
		const savedState = localStorage.getItem("sidebarState");
		if (savedState === "collapsed") {
			$(".sidebar").addClass("collapsed");
			$(".content-wrapper").addClass("expanded");
		} else {
			$(".sidebar").removeClass("collapsed");
			$(".content-wrapper").removeClass("expanded");
		}
	}

	// Close sidebar when clicking outside on mobile
	$(document)
		.off("click.sidebar")
		.on("click.sidebar", function (e) {
			if (window.innerWidth < 992) {
				if (
					!$(e.target).closest(".sidebar").length &&
					!$(e.target).closest("#sidebarToggle").length &&
					$(".sidebar").hasClass("show")
				) {
					$(".sidebar").removeClass("show");
					$("body").removeClass("sidebar-open");
				}
			}
		});

	// Handle window resize
	let resizeTimer;
	$(window)
		.off("resize.sidebar")
		.on("resize.sidebar", function () {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(function () {
				if (window.innerWidth >= 992) {
					// For desktop views
					const savedState = localStorage.getItem("sidebarState");
					if (savedState === "collapsed") {
						$(".sidebar").addClass("collapsed").removeClass("show");
						$(".content-wrapper").addClass("expanded");
						$("body").removeClass("sidebar-open");
					} else {
						$(".sidebar").removeClass("collapsed show");
						$(".content-wrapper").removeClass("expanded");
						$("body").removeClass("sidebar-open");
					}
				} else {
					// For mobile views
					$(".sidebar").removeClass("collapsed");
					$(".content-wrapper").removeClass("expanded");
					$("body").removeClass("sidebar-open");
				}
			}, 250);
		});

	// Initialize sidebar state on load
	updateSidebarState();
}

/**
 * Update sidebar state based on screen size
 */
function updateSidebarState() {
	if (window.innerWidth >= 992) {
		// Desktop
		const savedState = localStorage.getItem("sidebarState");
		if (savedState === "collapsed") {
			$(".sidebar").addClass("collapsed");
			$(".content-wrapper").addClass("expanded");
		} else {
			$(".sidebar").removeClass("collapsed");
			$(".content-wrapper").removeClass("expanded");
		}
		$(".sidebar").removeClass("show");
		$("body").removeClass("sidebar-open");
	} else {
		// Mobile
		$(".sidebar").removeClass("collapsed");
		$(".content-wrapper").removeClass("expanded");
	}
}

// ============================================================================
// DATA TABLES MODULAR FUNCTIONS
// ============================================================================

/**
 * Initialize DataTables for all tables with id="dataTable"
 * @returns {Object|null} DataTable instance or null
 */
function initializeDataTables() {
	if ($.fn.DataTable && $("#dataTable").length > 0) {
		// Check if DataTable is already initialized
		if ($.fn.DataTable.isDataTable("#dataTable")) {
			return $("#dataTable").DataTable();
		}

		const hasData =
			$("#dataTable tbody tr").length > 0 &&
			!$("#dataTable tbody td").first().text().includes("Tidak ada data");

		if (hasData) {
			// Default configuration for all DataTables
			return $("#dataTable").DataTable({
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
					searchPlaceholder: "Cari data...",
					lengthMenu: "Tampilkan _MENU_",
					info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
					infoEmpty: "Tidak ada data yang tersedia",
					infoFiltered: "(disaring dari total _MAX_ data)",
					zeroRecords: "Tidak ada data yang cocok",
					paginate: {
						first: "Pertama",
						last: "Terakhir",
						next: "Berikutnya",
						previous: "Sebelumnya",
					},
					loadingRecords: "Memuat data...",
					processing: "Memproses...",
					emptyTable: "Tidak ada data tersedia pada tabel ini",
				},
				customFilter: false,
			});
		}
	}
	return null;
}

/**
 * Get DataTable instance safely
 * @returns {Object|null} DataTable instance or null
 */
function getDataTableInstance() {
	if (
		$.fn.DataTable &&
		$("#dataTable").length > 0 &&
		$.fn.DataTable.isDataTable("#dataTable")
	) {
		return $("#dataTable").DataTable();
	}
	return null;
}

/**
 * Show empty state message in DataTable
 * @param {string} message - Custom message to display
 */
function showDataTableEmptyState(message = "Tidak ada data yang tersedia") {
	const table = getDataTableInstance();
	if (table) {
		table.clear().draw();

		// Add empty state row
		$("#dataTable tbody").html(
			"<tr>" +
				'<td colspan="' +
				$("#dataTable thead th").length +
				'" class="text-center py-4">' +
				'<span class="text-center">' +
				message +
				"</span>" +
				"</td>" +
				"</tr>"
		);
	}
}

/**
 * Check if DataTable has real data (not empty state)
 * @returns {boolean}
 */
function hasDataTableRealData() {
	const table = getDataTableInstance();
	if (table) {
		const data = table.rows().data();
		return data.length > 0 && !data[0][0].includes("Tidak ada data");
	}
	return false;
}

/**
 * Refresh DataTable with new data
 * @param {Array} data - Array of row data
 */
function refreshDataTable(data) {
	const table = getDataTableInstance();
	if (table) {
		// Clear existing data
		table.clear();

		// Add new data if provided
		if (data && data.length > 0) {
			data.forEach(function (rowData) {
				table.row.add(rowData);
			});
		}

		// Redraw the table (this will reset pagination to page 1)
		table.draw();

		return true;
	}
	return false;
}

/**
 * Destroy and reinitialize DataTable
 * @returns {Object|null} New DataTable instance or null
 */
function reinitializeDataTable() {
	const table = getDataTableInstance();
	if (table) {
		table.destroy();
	}
	return initializeDataTables();
}

/**
 * Update DataTable with AJAX response data
 * @param {Object} response - AJAX response object
 * @param {Function} dataMapper - Function to map API data to table columns
 * @returns {boolean} Success status
 */
function updateDataTableWithAjax(response, dataMapper) {
	if (!response || !response.success) {
		console.error("Invalid response format");
		return false;
	}

	const tableData = response.data || [];
	const mappedData = [];

	if (dataMapper && typeof dataMapper === "function") {
		// Use custom mapper function
		tableData.forEach(function (item, index) {
			mappedData.push(dataMapper(item, index));
		});
	} else {
		// Default mapper - adjust according to your column structure
		tableData.forEach(function (item, index) {
			mappedData.push([
				index + 1,
				item.warehouse_name || "-",
				item.product_name || "-",
				item.type_name || "-",
				item.current_stock || "0.00",
			]);
		});
	}

	return refreshDataTable(mappedData);
}

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

/**
 * Show loading state for DataTable
 */
function showDataTableLoading() {
	const table = getDataTableInstance();
	if (table) {
		table.processing(true);
	}
}

/**
 * Hide loading state for DataTable
 */
function hideDataTableLoading() {
	const table = getDataTableInstance();
	if (table) {
		table.processing(false);
	}
}

/**
 * Reset DataTable search and filters
 */
function resetDataTable() {
	const table = getDataTableInstance();
	if (table) {
		table.search("").draw();
		table.columns().search("").draw();
	}
}

// Fix Bootstrap modal focus warning
$(document).on("hidden.bs.modal", ".modal", function () {
	// Hilangkan fokus dari elemen yang sebelumnya aktif
	if (document.activeElement) {
		document.activeElement.blur();
	}

	// Opsional: arahkan fokus kembali ke body atau tombol pemicu
	$("body").trigger("focus");
});
