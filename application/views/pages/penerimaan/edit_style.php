<style>
    /* Loading Overlay */
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .loader {
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .spinner {
        width: 36px;
        height: 36px;
        margin: 0 auto 12px;
        border: 3px solid #e9ecef;
        border-top: 3px solid #5a5c69;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    /* Card Structure - Minimal Color */
    .edit-card {
        background: #fff;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .edit-card-header {
        background: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .edit-card-header h5 {
        margin: 0;
        font-weight: 600;
        color: #333;
        font-size: 17px;
    }

    .edit-card-header .header-subtitle {
        font-size: 17px;
        color: #6c757d;
    }

    .edit-card-body {
        padding: 20px;
    }

    /* Section Title */
    .section-title {
        font-size: 17px;
        font-weight: 600;
        color: #333;
        margin-bottom: 17px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e3e6f0;
    }

    /* Info Badge - Minimal */
    .info-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 17px;
        font-weight: 500;
        background: #e9ecef;
        color: #333;
    }

    /* Excel-like Table */
    .excel-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 17px;
    }

    .excel-table th {
        background: #5a5c69;
        color: white;
        padding: 10px 8px;
        text-align: center;
        font-weight: 600;
        font-size: 17px;
        border: none;
    }

    .excel-table th:first-child {
        border-radius: 6px 0 0 0;
    }

    .excel-table th:last-child {
        border-radius: 0 6px 0 0;
    }

    .excel-table td {
        border: 1px solid #e3e6f0;
        padding: 0;
        vertical-align: middle;
        background: #fff;
    }

    .excel-table tr:nth-child(even) td {
        background: #f8f9fc;
    }

    .excel-table tr:hover td {
        background: #f1f3f5;
    }

    .excel-table tr:last-child td:first-child {
        border-radius: 0 0 0 6px;
    }

    .excel-table tr:last-child td:last-child {
        border-radius: 0 0 6px 0;
    }

    /* Cell Inputs */
    .cell-input {
        width: 100%;
        height: 36px;
        padding: 4px 10px;
        border: 1px solid #ced4da;
        background: #fff;
        font-size: 17px;
        border-radius: 4px;
    }

    .cell-input:hover {
        border-color: #adb5bd;
    }

    .cell-input:focus {
        outline: none;
        border-color: #5a5c69;
        box-shadow: 0 0 0 2px rgba(90, 92, 105, 0.1);
    }

    .cell-input-readonly {
        background: #f8f9fc;
        color: #6c757d;
        border: 1px solid #e3e6f0;
    }

    /* Product Display in Table */
    .product-cell {
        padding: 4px 10px;
        min-height: 36px;
        display: flex;
        align-items: center;
    }

    .product-cell-text {
        font-size: 17px;
        font-weight: 500;
        color: #333;
    }

    .product-cell-unit {
        font-size: 17px;
        color: #6c757d;
        background: #e9ecef;
        padding: 2px 6px;
        border-radius: 3px;
        margin-left: 8px;
    }

    /* Action Buttons in table */
    .btn-action {
        padding: 4px 8px;
        font-size: 17px;
        margin: 1px;
        border-radius: 4px;
        border: none;
    }

    .btn-action-edit {
        background: #ffc107;
        color: #333;
    }

    .btn-action-edit:hover {
        background: #e0a800;
    }

    .btn-action-save {
        background: #28a745;
        color: #fff;
    }

    .btn-action-save:hover {
        background: #218838;
    }

    .btn-action-cancel {
        background: #6c757d;
        color: #fff;
    }

    .btn-action-cancel:hover {
        background: #5a6268;
    }

    .btn-action-delete {
        background: #dc3545;
        color: #fff;
    }

    .btn-action-delete:hover {
        background: #c82333;
    }

    /* Buttons */
    .btn-back {
        background: #6c757d;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        border: none;
        font-size: 17px;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
    }

    .btn-save {
        background: #4361ee;
        border: none;
        color: white;
        padding: 10px 24px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 17px;
    }

    .btn-save:hover {
        background: #3f37c9;
        color: white;
    }

    /* Hidden elements */
    .hidden {
        display: none !important;
    }

    /* Form Labels */
    .form-label {
        font-size: 17px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .form-label-required::after {
        content: ' *';
        color: #dc3545;
    }

    /* Table Responsive */
    .table-responsive {
        overflow-x: auto;
    }
</style>