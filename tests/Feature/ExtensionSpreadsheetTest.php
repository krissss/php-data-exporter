<?php

use Kriss\DataExporter\DataExporter;
use Kriss\DataExporter\Writer\Extension\NullSpreadsheetExtend;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class MyExtend extends NullSpreadsheetExtend
{
    /**
     * @inheritDoc
     */
    public function beforeWrite(Spreadsheet $spreadsheet, IWriter $writer): void
    {
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => 'FFFF0000'],
                ],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A2:B4')->applyFromArray($styleArray);
    }
}

beforeEach(function () {
    $this->source = [
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
        ['aa', 'bb', 'cc'],
    ];
    $this->filename = __DIR__ . '/../tmp/test';
});

it("Extension Spreadsheet: set style", function () {
    DataExporter::xlsxSpreadsheet($this->source, [
        'extend' => new MyExtend(),
    ])->saveAs($this->filename);

    // check by person
    expect(true)->toBeTrue();
});
