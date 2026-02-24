<style>
    /* Reset dan base style */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        line-height: 1.3;
        color: #000;
        background: #fff;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-size: 17px;
    }

    .page {
        width: 216mm;
        min-height: 279mm;
        margin: 0 auto;
        padding: 5mm 10mm;
        background: white;
        position: relative;
        page-break-after: always;
        display: flex;
        flex-direction: column;
    }

    /* Watermark */
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        opacity: 0.08;
        z-index: -1;
        pointer-events: none;
    }

    .watermark-logo {
        width: 350px;
        height: 350px;
        object-fit: contain;
    }

    /* Header Surat */
    .header-surat {
        margin-bottom: 10px;
    }

    .kop-surat {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .logo-perusahaan {
        width: 100px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .logo-perusahaan img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .info-perusahaan {
        text-align: center;
        flex-grow: 1;
        padding: 0 10px;
    }

    .info-perusahaan h1 {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 19px;
        font-weight: bold;
        margin-bottom: 2px;
        color: #000;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-perusahaan h2 {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 17px;
        font-weight: bold;
        margin-bottom: 2px;
        color: #000;
    }

    .info-perusahaan p {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 30px;
        font-weight: bold;
        margin-top: 2px;
    }

    .stamp-original {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        width: 100px;
        text-align: center;
        flex-shrink: 0;
        border: 1px solid #000;
        padding: 15px;
        font-size: 17px;
        font-weight: bold;
        margin-top: 10px;
    }

    .header-line {
        border-bottom: 1px solid #000;
        margin: 2px 0 5px;
        margin-top: 10px;
        margin-bottom: 10px;
        margin-left: 50px;
        margin-right: 50px;
    }

    /* Informasi Penerimaan */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin-bottom: 12px;
    }

    .info-box {
        border: 1px solid #000;
        padding: 6px 8px;
        border-radius: 3px;
    }

    .info-box h4 {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 17px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 4px;
        padding: 2px 0;
        border-bottom: 1px solid #000;
    }

    .info-row {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        display: flex;
        margin-bottom: 3px;
        font-size: 17px;
        margin-top: 5px;
    }

    .info-label {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        width: 120px;
        font-weight: bold;
        flex-shrink: 0;
        font-size: 17px;
        margin-top: 3px;
    }

    .info-value {
        flex-grow: 1;
        margin-top: 3px;
    }

    /* Konten utama */
    .content-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .table-title {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        text-align: center;
        font-weight: bold;
        font-size: 17px;
        margin: 5px 0 8px;
        text-transform: uppercase;
    }

    /* Tabel Detail Barang */
    .table-container {
        flex: 1;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .detail-table {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        width: 100%;
        border-collapse: collapse;
        font-size: 17px;
        table-layout: fixed;
        border: 1px solid #000;
    }

    .detail-table thead {
        display: table-header-group;
    }

    .detail-table th {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-weight: bold;
        padding: 4px 3px;
        text-align: center;
        border: 1px solid #000;
        /* background-color: #f0f0f0; */
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        font-size: 17px;
        height: 25px;
    }

    .detail-table td {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        padding: 3px 3px;
        border: 1px solid #000;
        vertical-align: top;
        word-wrap: break-word;
        font-size: 17px;
        height: 20px;
    }

    .detail-table tbody tr:nth-child(even) {
        /* background-color: #f8f8f8 !important; */
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Spesifik untuk kolom */
    .col-no {
        width: 5%;
    }

    .col-code {
        width: 15%;
    }

    .col-name {
        width: 45%;
    }

    .col-qty {
        width: 10%;
    }

    .col-unit {
        width: 10%;
    }

    .col-note {
        width: 15%;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    /* Baris total */
    .total-row {
        font-weight: bold;
        /* background-color: #e0e0e0 !important; */
        -webkit-print-color-adjust: exact;
    }

    .subtotal-row {
        font-weight: bold;
        /* background-color: #f0f0f0 !important; */
        -webkit-print-color-adjust: exact;
    }

    /* Bagian Tanda Tangan */
    .signature-section {
        margin-top: 15px;
        padding-top: 10px;
    }

    .signature-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        text-align: center;
        margin-top: 10px;
    }

    .signature-box {
        padding: 5px;
    }

    .signature-title {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-weight: bold;
        font-size: 17px;
        margin-bottom: 75px;
    }

    .signature-line {
        border-top: 1px solid #000;
        width: 80%;
        margin: 0 auto;
    }

    .signature-name {
        font-size: 17px;
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        margin-top: 5px;
        min-height: 15px;
    }

    .signature-note {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 14px;
        color: #000;
        margin-top: 2px;
    }

    /* Footer */
    .footer {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        margin-top: 10px;
        font-size: 14px;
        color: #000;
        text-align: left;
        padding-top: 5px;
    }

    /* Nomor Halaman */
    .page-number {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        position: absolute;
        bottom: 5mm;
        right: 15mm;
        font-size: 14px;
        color: #000;
    }

    /* Tombol Aksi (Screen Only) */
    .action-buttons {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .btn-print {
        font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 17px;
        display: flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* ============================================
       PRINT STYLES
    ============================================ */
    @media print {
        body {
            font-family: 'Calibri', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 17px;
            background: white;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 216mm;
            min-height: 279mm;
            margin: 0;
            /* padding: 15mm 10mm; */
            page-break-after: always;
            box-shadow: none;
        }

        .action-buttons,
        .no-print {
            display: none !important;
        }

        /* Pastikan tabel tidak terpotong antar halaman */
        .detail-table {
            page-break-inside: auto;
        }

        .detail-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .detail-table td,
        .detail-table th {
            page-break-inside: avoid;
        }

        /* Tanda tangan container di print mode */
        .tanda-tangan-container {
            position: absolute;
            bottom: 50mm;
            left: 10mm;
            right: 10mm;
        }

        /* Force background colors in print */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Page margins */
        @page {
            margin-top: 5mm;
            margin-bottom: 5mm;
            margin-left: 5mm;
            margin-right: 5mm;
            size: letter;
        }
    }

    /* ============================================
       SCREEN STYLES
    ============================================ */
    @media screen {
        body {
            background: #f5f5f5;
            padding: 20px;
        }

        .page {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* Tanda tangan container di screen mode */
        .tanda-tangan-container {
            position: relative;
            bottom: auto;
            left: auto;
            right: auto;
            margin-top: 20px;
        }

        /* Sembunyikan tanda tangan di halaman bukan terakhir di screen mode */
        .page:not(:last-of-type) .tanda-tangan-container {
            display: none !important;
        }

        /* Footer di screen mode */
        .footer-surat {
            position: relative;
            bottom: auto;
            left: auto;
            right: auto;
            margin-top: 20px;
        }

        /* Page number di screen mode */
        .page-number {
            position: relative;
            bottom: auto;
            right: auto;
            margin-top: 10px;
            text-align: right;
        }
    }
</style>