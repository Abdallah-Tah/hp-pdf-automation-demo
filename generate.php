<?php

require_once __DIR__ . '/vendor/autoload.php';

use PdfAutomation\InvoiceGenerator;
use PdfAutomation\BatchProcessor;

// Sample invoice data
$invoiceData = [
    'number' => 'INV-2026-001',
    'date' => '2026-07-23',
    'due_date' => '2026-08-22',
    'from' => [
        'company' => 'Build With Abdallah',
        'address' => '123 Developer Street, Code City',
        'email' => 'hello@buildwithabdallah.com'
    ],
    'to' => [
        'name' => 'Client Company Inc.',
        'address' => '456 Business Ave, Enterprise City',
        'email' => 'billing@client.com'
    ],
    'items' => [
        ['description' => 'Web Application Development', 'qty' => 40, 'rate' => 75.00],
        ['description' => 'Database Design & Optimization', 'qty' => 15, 'rate' => 85.00],
        ['description' => 'API Integration Services', 'qty' => 20, 'rate' => 70.00],
        ['description' => 'Code Review & Documentation', 'qty' => 10, 'rate' => 65.00]
    ],
    'subtotal' => 6250.00,
    'tax_rate' => 8,
    'tax' => 500.00,
    'total' => 6750.00
];

echo "=== PHP PDF Document Automation Demo ===\n\n";

// Generate single invoice
echo "1. Generating single invoice...\n";
$generator = new InvoiceGenerator($invoiceData);
$outputPath = __DIR__ . '/output/invoice_sample.pdf';
$generator->generate($outputPath);
echo "   ✓ Created: $outputPath\n\n";

// Generate batch invoices
echo "2. Generating batch invoices (3 invoices)...\n";
$batchData = [];
for ($i = 1; $i <= 3; $i++) {
    $data = $invoiceData;
    $data['number'] = "INV-2026-00" . ($i + 1);
    $data['to']['name'] = 'Client ' . $i . ' Corp';
    $batchData[] = $data;
}

$batchProcessor = new BatchProcessor(__DIR__ . '/output');
$results = $batchProcessor->generateInvoices($batchData);
foreach ($results as $result) {
    if ($result['success']) {
        echo "   ✓ Created: {$result['filename']}\n";
    } else {
        echo "   ✗ Failed: {$result['error']}\n";
    }
}
echo "\n";

// Generate a report
echo "3. Generating PDF report...\n";
$reportData = [
    'Generated At' => date('Y-m-d H:i:s'),
    'Total Invoices' => count($batchData) + 1,
    'Revenue Generated' => '$' . number_format(6750.00 * 4, 2),
    'Processing Time' => '< 1 second',
    'Library' => 'TCPDF (PHP)'
];
$reportPath = $batchProcessor->generateReport('Invoice Generation Report', $reportData);
echo "   ✓ Created: $reportPath\n\n";

echo "=== All PDFs generated successfully! ===\n";
echo "Output directory: " . __DIR__ . "/output\n";
