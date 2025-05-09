<?php
ini_set('display_errors', 1);

require('Libraries/fpdf/fpdf.php');

class PDF extends FPDF
{
    // Función para agregar el logo
    function AddLogo()
    {
        $this->Image('Assets/images/LogoCarso.png', 15, 5, 45);
    }

    // Función para agregar el título del documento
    function titulo()
    {
        $this->Ln(1);
        // Posición para la celda contenedora del título
        $this->SetY(5);
        $this->SetX($this->GetX() + 5); // Mueve la posición hacia la izquierda
        // Celda grande para el título
        $this->Cell(180, 30, '', 1, 1, 'J');

        // Título principal
        $this->SetFont('Arial', 'B', 12);
        $title = utf8_decode('NACEL DE CENTROAMÉRICA S.A');
        $this->Text(70, 19, $title);

        // Subtítulo
        $title2 = utf8_decode('ENTREGA DE EQUIPO DE PROTECCIÓN PERSONAL Y UNIFORME');
        $this->Text(40, 24, $title2);

        // Ficha de registro
        $company = 'Ficha De Registro';
        $this->Text(85, 29, $company);
    }

    // Función para agregar la información personal
    function info_personal($arrData)
    {
        // Celda contenedora de la información personal
        $this->SetX($this->GetX() + 5);
        $this->Cell(180, 40, '', 1, 1, 'J');

        // Título: INFORMACIÓN DEL PERSONAL
        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('INFORMACIÓN DEL PERSONAL');
        $this->Text(80, 40, $title);

        // Subtítulo: ENTREGA
        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('ENTREGA:');
        $this->Text(20, 49, $title);

        // Celda interna para ENTREGA
        $this->SetY(45);
        $this->SetX(38);
        $this->SetFillColor(255, 255, 166);
        $this->Cell(75, 5, '', 1, 1, 'C', true);
        $this->Text(45, 49, $arrData['usuario']['nombre_completo']);


        // Subtítulo: FECHA
        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('FECHA:');
        $this->Text(130, 49, $title);

        // Celda interna para FECHA
        $this->SetY(45);
        $this->SetX(143);
        $this->SetFillColor(255, 255, 166);
        $this->Cell(45, 5, '', 1, 1, 'C', true);
        $this->Text(157, 49, $arrData['uniforme']['fecha_asignacion']);

        // Subtítulo: RECIBE
        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('RECIBE:');
        $this->Text(23, 59, $title);

        $this->SetY(55);
        $this->SetX(38);
        $this->SetFillColor(255, 255, 166);
        $this->Cell(75, 5, '', 1, 1, 'C', true);
        $this->Text(45, 59, $arrData['uniforme']['nombres']);

        // Subtítulo: No.EMPLEADO
        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('No.EMPLEADO:');
        $this->Text(118, 59, $title);

        $this->SetY(55);
        $this->SetX(143);
        $this->SetFillColor(255, 255, 166);
        $this->Cell(45, 5, $arrData['uniforme']['codigo_empleado'], 1, 1, 'C', true);


        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('PUESTO:');
        $this->Text(22, 69, $title);

        $this->SetY(65);
        $this->SetX(38);
        $this->SetFillColor(255, 255, 166);
        $this->Cell(75, 5, $arrData['usuario']['nombre_puesto'], 1, 1, 'C', true);


        // Subtítulo: AREA
        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('DEPARTAMENTO:');
        $this->Text(114, 69, $title);
        $this->SetY(65);
        $this->SetX(143);
        $this->SetFillColor(255, 255, 166);
        $departamento = utf8_decode($arrData['usuario']['nombre_departamento']);
        $this->Cell(45, 5, $departamento, 1, 1, 'C', true);

        $this->Ln(5);
    }

    function info_columns($uniforme)
    {
        // Crear el margen izquierdo
        $this->SetX($this->GetX() + 5);
        $this->Cell(180, 95, '', 1, 1, 'J');
    
        // Título de la tabla
        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('DETALLE DE ARTICULOS ENTREGADOS');
        $this->Text(70, 80, $title);  // Posición fija del título
    
        // Mover a la siguiente línea
        $this->SetY(85);
        $this->SetX(25);
    
        // Establecer el encabezado de la tabla
        $this->SetFont('Arial', 'B', 9); // Negrita para los títulos
    
        // Fondo gris para los encabezados
        $this->SetFillColor(200, 200, 200);
    
        // Ancho de las columnas
        $w_item = 12;
        $w_desc = 103;
        $w_unit = 25;
        $w_qty = 25;
    
        // Encabezados de las columnas
        $this->Cell($w_item, 5, utf8_decode('ITEM'), 1, 0, 'C', true);
        $this->Cell($w_desc, 5, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', true);
        $this->Cell($w_unit, 5, utf8_decode('UNIDAD'), 1, 0, 'C', true);
        $this->Cell($w_qty, 5, utf8_decode('CANTIDAD'), 1, 1, 'C', true);
    
        // Verificar cada campo y rellenar la tabla
        $items = [
            ['desc' => 'Camisas', 'unidad' => 'unidad' ,  'cantidad' => $uniforme['Camisas']],
            ['desc' => 'Pantalones','unidad' => 'unidad' ,   'cantidad' => $uniforme['Pantalones']],
            ['desc' => 'Botas','unidad' => 'par' ,   'cantidad' => $uniforme['Botas']],
        ];
    
        $itemNumber = 1;
    
        foreach ($items as $item) {
            if ($item['cantidad'] > 0) {  // Si la cantidad no es null, generar fila
                $this->SetX(25);
                $this->Cell($w_item, 5, $itemNumber, 1, 0, 'C');  // Número de ítem
                $this->Cell($w_desc, 5, utf8_decode($item['desc']), 1, 0, 'L');  // Descripción
                $this->Cell($w_unit, 5, utf8_decode($item['unidad']), 1, 0, 'C');  // Unidad
                $this->Cell($w_qty, 5, $item['cantidad'], 1, 1, 'C');  // Cantidad
                $itemNumber++;
            }
        }
    }
    


    function info_observ()
    {

        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('OBSERVACIONES');
        $this->Text(85, 175, $title);

        $this->SetY(170);
        $this->SetX($this->GetX() + 5);
        $this->Cell(180, 45, '', 1, 1, 'J');

        $this->SetY(180);
        $this->SetX(25);
        $this->SetFillColor(255, 255, 166); // Color de relleno (amarillo claro)
        $this->Cell(160, 25, '', 1, 1, 'C', true);

    }

    function info_firmas($arrData)
    {
        $this->Ln(10);

        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('ENTREGA, RECEPCIÓN Y AUTORIZACIÓN');
        $this->Text(70, 220, $title);

        $this->SetX($this->GetX() + 5);
        $this->Cell(180, 60, '', 1, 1, 'J');

        // DIVISOR

        $this->SetY(230);
        $this->SetX(25);
        $this->SetFillColor(255, 255, 166);
        $this->Cell(65, 7, '', 1, 1, 'C', true);

        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('RECIBE');
        $this->Text(25, 229, $title);

        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode( 'FIRMA: ' . $arrData['uniforme']['nombres']);
        $this->Text(25, 240, $title);

        // DIVISOR

        $this->SetY(230);
        $this->SetX(118);
        $this->SetFillColor(255, 255, 166);
        $this->Cell(67, 7, '', 1, 1, 'C', true);


        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('ENTREGA');
        $this->Text(118, 229, $title);

        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode( 'FIRMA: ' . $arrData['usuario']['nombre_completo']);
        $this->Text(118, 240, $title);


        // DIVISOR

        $this->SetY(250);
        $this->SetX(118);
        $this->SetFillColor(255, 255, 166);
        $this->Cell(67, 7, '', 1, 1, 'C', true);


        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode('AUTORIZA');
        $this->Text(118, 249, $title);

        $this->SetFont('Arial', 'B', 9);
        $title = utf8_decode( 'FIRMA: ' . $arrData['usuario']['nombre_jefe']);
        $this->Text(118, 260, $title);
    }

    function datos()
    {

        $this->SetFont('Arial', 'B', 8);
        $title = utf8_decode('C.C. RECURSOS HUMANOS');
        $this->Text(25, 265, $title);

        $this->SetFont('Arial', 'B', 8);
        $title = utf8_decode('C.C. COMPRAS');
        $this->Text(25, 270, $title);

        $this->SetFont('Arial', 'B', 8);
        $title = utf8_decode('FR-RH-55');
        $this->Text(180, 269, $title);

        $this->SetFont('Arial', 'B', 8);
        $title = utf8_decode('REV. 00');
        $this->Text(180, 274, $title);

    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);

$nombreArchivo = utf8_decode('Constancia_' . $arrData['uniforme']['id_asignacion'] . '.pdf');

$pdf->AddLogo();
$pdf->titulo();
$pdf->info_personal($arrData);
$pdf->info_columns($uniforme);
$pdf->info_observ();
$pdf->info_firmas($arrData);
$pdf->datos();

// Generar el PDF y enviarlo al navegador
$pdf->Output('I', $nombreArchivo);

exit();
