<?php

require_once 'Spreadsheet/Excel/Writer.php';
include_once 'controlador.php';

if(!(isset($_GET['idproymacro']) && isset($_GET['idmodulo']))){
    exit();
}
$idproymacro=$_GET['idproymacro'];
$idmodulo=$_GET['idmodulo'];

//echo $_SERVER['DOCUMENT_ROOT'];

$cx=new controlador();
$mod = $cx->buscar_modulo($idmodulo);
$proymacro = $cx->buscar_proymacro($idproymacro);
$params=$cx->listar_parametros($idproymacro, $idmodulo);
$proys = $cx->listar_proyectos($idproymacro, $idmodulo);

$workbook = new Spreadsheet_Excel_Writer();
$archivo = "export_" .$proymacro[0]['NOMBREPROYMACRO']."_".$mod[0]['NOMBREMODULO'].".xls";
//$workbook = new Spreadsheet_Excel_Writer($archivo);
// sending HTTP headers
$workbook->send($archivo);

// Creating a worksheet
$worksheet =& $workbook->addWorksheet($mod[0]['NOMBREMODULO']);
//$workbook->setTempDir('/opt/estadisticas/web/sisproyred/export/');
// The actual data
//$worksheet->write(0, 0, 'Name');

$fila=0;
$col=0;
$worksheet->write($fila,0,"IDPROYECTO");
$worksheet->write($fila,1,"COD_PROYECTO");
$worksheet->write($fila,2,"NOMBRE_PROYECTO");
$col+=3;
foreach($params as $p){
    $worksheet->write($fila, $col, $p['NOMBREPARAM']);
    $col++;
}

$fila++;
$col=3;
foreach($proys as $py){
    $worksheet->write($fila, 0, $py['IDPROYECTO']);
    $worksheet->writeString($fila, 1, $py['CODPROYECTO']);
    $worksheet->write($fila, 2, $py['NOMBREPROY']);
    $col=3;
    foreach ($params as $p){
        $v=$cx->getValor($py['IDPROYECTO'], $p['IDPARAMETRO']);
        $worksheet->write($fila, $col, $v);
        $col++;
    }
    $fila++;
}

// Let's send the file
$workbook->close();
?>
