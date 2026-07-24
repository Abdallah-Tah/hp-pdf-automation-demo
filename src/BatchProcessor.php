<?php

namespace PdfAutomation;

class BatchProcessor
{
    private string $outputDir;
    private array $results = [];

    public function __construct(string $outputDir)
    {
        $this->outputDir = $outputDir;
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
    }

    public function generateInvoices(array $invoiceDataList): array
    {
        foreach ($invoiceDataList as $data) {
            $generator = new InvoiceGenerator($data);
            $filename = 'invoice_' . $data['number'] . '.pdf';
            $path = $this->outputDir . '/' . $filename;
            
            try {
                $generator->generate($path);
                $this->results[] = [
                    'success' => true,
                    'filename' => $filename,
                    'path' => $path
                ];
            } catch (\Exception $e) {
                $this->results[] = [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $this->results;
    }

    public function generateReport(string $title, array $data): string
    {
        $filename = 'report_' . date('Y-m-d_His') . '.pdf';
        $path = $this->outputDir . '/' . $filename;
        
        $pdf = new \TCPDF('P', 'mm', 'A4');
        $pdf->SetCreator('PHP PDF Automation Demo');
        $pdf->SetTitle($title);
        $pdf->AddPage();
        
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->Cell(0, 15, $title, 0, 1, 'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('helvetica', '', 11);
        foreach ($data as $key => $value) {
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(60, 7, $key . ':', 0, 0);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 7, $value, 0, 1);
        }
        
        $pdf->Output($path, 'F');
        return $path;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
