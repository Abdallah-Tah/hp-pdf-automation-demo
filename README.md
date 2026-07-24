# PHP PDF Document Automation with TCPDF

A complete PHP PDF automation system built with [TCPDF](https://tcpdf.org/) — generate professional invoices, batch-process documents, and create dynamic reports programmatically.

## 📋 Features

- **Invoice Generator** — Create professional PDF invoices from structured data
- **Batch Processor** — Generate multiple PDFs in a single run with error handling
- **Report Generator** — Dynamic PDF reports with custom layouts
- **Reusable Classes** — Clean OOP design, easy to extend
- **Zero Dependencies** — Just TCPDF, runs on any PHP 8.1+ host

## 🛠️ Tech Stack

- PHP 8.1+
- [TCPDF](https://github.com/tecnickcom/TCPDF) v6.7+ — Pure PHP PDF library

## 📁 Project Structure

```
php-pdf-automation-demo/
├── src/
│   ├── InvoiceGenerator.php   # Single invoice PDF generation
│   ── BatchProcessor.php     # Batch invoice + report generation
├── output/                    # Generated PDFs
├── composer.json
└── generate.php               # Demo runner
```

## 🚀 Quick Start

```bash
# Clone the repo
git clone https://github.com/Abdallah-Tah/hp-pdf-automation-demo.git
cd hp-pdf-automation-demo

# Install dependencies
composer install

# Run the demo
php generate.php
```

Check the `output/` folder for generated PDFs.

## 💡 How It Works

### Single Invoice

```php
use PdfAutomation\InvoiceGenerator;

$invoiceData = [
    'number' => 'INV-2026-001',
    'date' => '2026-07-23',
    'from' => ['company' => 'Your Company', 'address' => '...', 'email' => '...'],
    'to' => ['name' => 'Client Name', 'address' => '...', 'email' => '...'],
    'items' => [
        ['description' => 'Web Development', 'qty' => 40, 'rate' => 75.00],
        // ...
    ],
    'subtotal' => 6250.00,
    'tax_rate' => 8,
    'tax' => 500.00,
    'total' => 6750.00
];

$generator = new InvoiceGenerator($invoiceData);
$generator->generate(__DIR__ . '/output/invoice.pdf');
```

### Batch Processing

```php
use PdfAutomation\BatchProcessor;

$batch = new BatchProcessor(__DIR__ . '/output');
$results = $batch->generateInvoices([$invoice1, $invoice2, $invoice3]);
// All invoices generated with error handling per-file
```

## 📄 Real-World Use Cases

| Scenario | How |
|---|---|
| Monthly invoicing | Batch processor + cron job |
| Report generation | Dynamic data → structured PDF |
| Certificates | Template + merge with data |
| Receipts | Generate on payment confirmation |
| Contracts | Structured data → PDF with signatures |

## 🔌 Extending TCPDF

TCPDF supports much more than what this demo covers:

- **Custom fonts** — `addFont()` for brand typefaces
- **Images & logos** — `Image()` for PNG/JPG/SVG
- **HTML rendering** — `writeHTML()` for complex layouts
- **Encryption** — password-protect PDFs with `SetProtection()`
- **Barcodes & QR codes** — `write1DBarcode()` / `write2DBarcode()`
- **Headers & footers** — override `Header()` / `Footer()` methods

##  Tutorial

Full step-by-step tutorial: [Automating PDF Document Generation in PHP with TCPDF](https://buildwithabdallah.com/tutorials/automating-pdf-document-generation-in-php-with-tcpdf)

## 📄 License

MIT — free to use, modify, and distribute.
