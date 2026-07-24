<?php

namespace PdfAutomation;

use TCPDF;

class InvoiceGenerator
{
    private TCPDF $pdf;
    private array $invoiceData;

    public function __construct(array $invoiceData)
    {
        $this->invoiceData = $invoiceData;
        $this->pdf = new TCPDF('P', 'mm', 'A4');
    }

    public function generate(string $outputPath): string
    {
        $this->pdf->SetCreator('PHP PDF Automation Demo');
        $this->pdf->SetAuthor('Abdallah Mohamed');
        $this->pdf->SetTitle('Invoice ' . $this->invoiceData['number']);
        $this->pdf->SetAutoPageBreak(true, 20);
        $this->pdf->AddPage();

        $this->renderHeader();
        $this->renderInvoiceInfo();
        $this->renderLineItems();
        $this->renderTotals();
        $this->renderFooter();

        $this->pdf->Output($outputPath, 'F');
        return $outputPath;
    }

    private function renderHeader(): void
    {
        $this->pdf->SetFont('helvetica', 'B', 20);
        $this->pdf->Cell(0, 15, 'INVOICE', 0, 1, 'R');
        
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetY(10);
        $this->pdf->Cell(100, 10, 'From:', 0, 1);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(100, 5, $this->invoiceData['from']['company'], 0, 1);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(100, 5, $this->invoiceData['from']['address'], 0, 1);
        $this->pdf->Cell(100, 5, $this->invoiceData['from']['email'], 0, 1);
    }

    private function renderInvoiceInfo(): void
    {
        $this->pdf->SetY(40);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(90, 5, 'Invoice Number:', 0, 0);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(0, 5, $this->invoiceData['number'], 0, 1);
        
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(90, 5, 'Date:', 0, 0);
        $this->pdf->Cell(0, 5, $this->invoiceData['date'], 0, 1);
        
        $this->pdf->Cell(90, 5, 'Due Date:', 0, 0);
        $this->pdf->Cell(0, 5, $this->invoiceData['due_date'], 0, 1);
        
        $this->pdf->Ln(10);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(90, 5, 'Bill To:', 0, 1);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(90, 5, $this->invoiceData['to']['name'], 0, 1);
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(90, 5, $this->invoiceData['to']['address'], 0, 1);
        $this->pdf->Cell(90, 5, $this->invoiceData['to']['email'], 0, 1);
    }

    private function renderLineItems(): void
    {
        $this->pdf->Ln(15);
        
        $this->pdf->SetFillColor(52, 152, 219);
        $this->pdf->SetTextColor(255);
        $this->pdf->SetFont('helvetica', 'B', 10);
        $this->pdf->Cell(80, 8, 'Description', 1, 0, 'L', true);
        $this->pdf->Cell(30, 8, 'Qty', 1, 0, 'C', true);
        $this->pdf->Cell(30, 8, 'Rate', 1, 0, 'R', true);
        $this->pdf->Cell(40, 8, 'Amount', 1, 1, 'R', true);
        
        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('helvetica', '', 9);
        
        $total = 0;
        foreach ($this->invoiceData['items'] as $item) {
            $amount = $item['qty'] * $item['rate'];
            $total += $amount;
            
            $this->pdf->Cell(80, 7, $item['description'], 1, 0, 'L');
            $this->pdf->Cell(30, 7, $item['qty'], 1, 0, 'C');
            $this->pdf->Cell(30, 7, '$' . number_format($item['rate'], 2), 1, 0, 'R');
            $this->pdf->Cell(40, 7, '$' . number_format($amount, 2), 1, 1, 'R');
        }
    }

    private function renderTotals(): void
    {
        $this->pdf->Ln(5);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(140, 8, 'Subtotal:', 0, 0, 'R');
        $this->pdf->Cell(40, 8, '$' . number_format($this->invoiceData['subtotal'], 2), 0, 1, 'R');
        
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->Cell(140, 8, 'Tax (' . $this->invoiceData['tax_rate'] . '%):', 0, 0, 'R');
        $this->pdf->Cell(40, 8, '$' . number_format($this->invoiceData['tax'], 2), 0, 1, 'R');
        
        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->Cell(140, 10, 'Total:', 0, 0, 'R');
        $this->pdf->Cell(40, 10, '$' . number_format($this->invoiceData['total'], 2), 0, 1, 'R');
    }

    private function renderFooter(): void
    {
        $this->pdf->SetY(-30);
        $this->pdf->SetFont('helvetica', 'I', 9);
        $this->pdf->Cell(0, 5, 'Thank you for your business!', 0, 1, 'C');
        $this->pdf->Cell(0, 5, 'Payment due within 30 days.', 0, 1, 'C');
    }
}
