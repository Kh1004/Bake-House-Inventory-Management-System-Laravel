<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @page {
            size: A4;
            margin: 15mm 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            background: #fff !important;
            padding: 0;
            margin: 0;
        }
        .container {
            max-width: 100% !important;
            padding: 0 15px;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            padding: 8px;
            vertical-align: top;
            border: 1px solid #dee2e6;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
            color: #000;
            font-weight: 600;
        }
        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            font-size: 85%;
        }
        .text-muted {
            color: #6c757d !important;
        }
        .text-uppercase {
            letter-spacing: 0.05em;
        }
        .border-0 {
            border: 0 !important;
        }
        .table-light {
            background-color: #f8f9fa;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .container {
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
            tfoot {
                display: table-footer-group;
            }
        }
    </style>
</head>
<body>
    @yield('content')
    
    <script>
        // Auto-print when the page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
                // Close the window after printing
                setTimeout(function() {
                    window.close();
                }, 100);
            }, 200);
        };
    </script>
</body>
</html>