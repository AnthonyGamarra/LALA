<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Style;

class DetalleExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $detalles;
    protected $tipoexcel;

    public function __construct($detalles, $tipoexcel = null)
    {
        $this->detalles = $detalles;
        $this->tipoexcel = $tipoexcel;
    }

    /**
     * Método para devolver los datos a exportar
     */
    private function getValue($value)
    {
        
      return (is_null($value) || $value === '') ? 0 : $value;

    }

    public function array(): array
    {
        $datos_sesion = [];

        $datos_sesion1 = [
            ['','','Órgano Desconcentrado: '. session('redx')],
            ['','','Cod. Centro Gestores: '. session('codesx')],
            ['','','Establecimiento de Salud: '. session('esx')],
            ['','','Actividad: '. session('nameactiodx')],
            ['','','Prioridad: '. session('prioactiodx')],
        ];
        $datos_sesion2 = [
            ['','','CONSOLIDADO POR ESTABLECIMIENTO DE SALUD'],
            ['','','Órgano Desconcentrado: '. session('redx')],
            ['','','Cod. Centro Gestores: '. session('codesx')],
            ['','','Establecimiento de Salud: '. session('esx')],
            ['', ''],
        ];
        $datos_sesion3 = [
            ['','','CONSOLIDADO POR RED'],
            ['','','Órgano Desconcentrado: '. session('redx')],
            ['','','Cod. Centro Gestores: '. session('codesx')],
            ['', ''],
            ['', ''],
        ];
        $datos_sesion9 = [
            ['', ''], // Fila vacía en la 12 para separar las cabeceras de los datos
            ['Fondo F.', 'Cod. PoFi', 'Posición Presupuestaria', 'Tipo Gasto',
            'Estimación P. 2025', 'Enero', 'Febrero', 'Marzo', 'Abril',
            'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
            'Octubre', 'Noviembre', 'Diciembre', 'Total 2026',
            'Proyección 2027','Proyección 2028','Proyección 2029']
        ];

        switch($this->tipoexcel){
            case 1: $datos_sesion = array_merge($datos_sesion1, $datos_sesion9);break;
            case 2: $datos_sesion = array_merge($datos_sesion2, $datos_sesion9);break;
            case 3: $datos_sesion = array_merge($datos_sesion3, $datos_sesion9);break;
            default: $datos_sesion = $datos_sesion9;
        }

        $tipox = '';
        $tabla_datos = $this->detalles->map(function ($row) {
            switch ($row->tipo) {
                case 2:
                    $tipox = "Otro Gasto Operativo";
                    break;
                case 1:
                    $tipox = "Ineludible";
                    break;
                default:
                    $tipox = ""; // Valor por defecto si no coincide con 1 o 2
                    break;
            }
            return [
                $row->financia_codigo,
                $row->pofi_codigo,
                $row->pofi,
                $tipox,
                $this->getValue($row->estimacion),
            $this->getValue($row->enero),
            $this->getValue($row->febrero),
            $this->getValue($row->marzo),
            $this->getValue($row->abril),
            $this->getValue($row->mayo),
            $this->getValue($row->junio),
            $this->getValue($row->julio),
            $this->getValue($row->agosto),
            $this->getValue($row->septiembre),
            $this->getValue($row->octubre),
            $this->getValue($row->noviembre),
            $this->getValue($row->diciembre),
            $this->getValue($row->total2026),
            $this->getValue($row->proy2027),
            $this->getValue($row->proy2028),
            $this->getValue($row->proy2029)
            ];
        });

        // Los datos comenzarán en la fila 12
        return array_merge($datos_sesion, $tabla_datos->toArray());
    }

    /**
     * Encabezados del archivo Excel (fila 11)
     */
    public function headings(): array
    {
        return [
            '', ''
        ];
    }

    /**
     * DATOS A TRABAJAR (solo datos)
     */
    
    public function map($row): array
    {
        return [
            $row->financia_codigo,
            $row->pofi_codigo, 
            $row->pofi,
            $row->tipo,
            $row->estimacion,
            $row->enero,
            $row->febrero,
            $row->marzo,
            $row->abril,
            $row->mayo,
            $row->junio,
            $row->julio,
            $row->agosto,
            $row->septiembre,
            $row->octubre,
            $row->noviembre,
            $row->diciembre,
            $row->total2026,
            $row->proy2027,
            $row->proy2028,
            $row->proy2029
        ];
    }

    /**
     * Aplica estilos a las filas o celdas
     */
    public function styles($sheet)
    {
        //// Obtener el número de la última fila con datos
        $lastRow = $sheet->getHighestRow();

        for ($row = 8; $row <= $lastRow; $row++) {
        for ($col = 5; $col <= 21; $col++) { // Columna E hasta U (5 a 21)
            $cell = $sheet->getCellByColumnAndRow($col, $row);
            if ($cell->getValue() === '' || $cell->getValue() === null) {
                $sheet->setCellValueExplicitByColumnAndRow($col, $row, 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            }
        }
    }
    
        $sheet->getStyle('E8:U' . $lastRow)
        ->getNumberFormat()
        ->setFormatCode('0');  // Esto asegura que los números se muestren, incluyendo los 0

        //borde a todo
        $range = 'A7:U' . $lastRow;
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,  // Borde fino (1px)
                    'color' => ['rgb' => 'cccccc'],  // Color del borde (negro)
                ],
            ],
        ]);


        // Aplicar color amarillo a los encabezados de los datos (fila 11)
        $sheet->getStyle('A7:U7')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFEB9C'],  // Color de fondo amarillo
            ],
            'font' => [
                'bold' => true, 
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,  // Centrar horizontalmente
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,  // Centrar verticalmente
            ],
        ]);

        // gasto corriente
        $sheet->getStyle('A8:U8')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '92b8e5'],  // Color de fondo azul
            ],
        ]);

        //colorear por pofi
        $rowIndex=8;
        foreach ($this->detalles as $row) {
            // Verificamos si el valor de pofi_codigo es alguno de los valores deseados
            if (in_array($row->pofi_codigo, ['2510100000', '2510122000','2510132000','2510200000','2510300000','2510323000','2510322000','2520100000','2520200000','2520224000','2520224010','2520224020','2520261000','2520261100','2520261200','2520238000','2520244000','2520238030','2520225000','2520300000','2520252000','2520252010'])) {
                $sheet->getStyle("A{$rowIndex}:U{$rowIndex}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'e2efff'],  // Color de fondo azul
                    ],
                ]);
            }
            if (in_array($row->pofi_codigo, ['2510000000', '2520000000'])) {
                $sheet->getStyle("A{$rowIndex}:U{$rowIndex}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'bcd4f2'],  // Color de fondo azul
                    ],
                ]);
            }

            $rowIndex++;
        }


    }
}
