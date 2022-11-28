<?php

$longCode = 8;
$numCode = 10;
$path = "awards_list.csv";
$pathListCode = "code_list.csv";
$listAwards = loadCSVAwards($path);
$listCode = array();
$numAwards = countDistintAwards($path);

generateCodeAwards($longCode, $numCode, $path);

function countDistintAwards($path){
    
    $lines = explode( "\n", file_get_contents($path));
    
    $numAwards = count($lines);
    $numAwards =  $numAwards-1;

    return $numAwards;
}

function generateCodeAwards($longCode, $numCode, $path){

    global $pathListCode;
    global $listCode;

    for($i=0; $i<$numCode; $i++){
        $idCode = $i;
        $code = generateUniqueCode($longCode);
        $awards = saveAwardCode();

        saveToListCode($idCode,$code,$awards);
    }

    generateCsvAwards($listCode,$pathListCode);
}

function loadCSVAwards($path){

    $lines = explode( "\n", file_get_contents($path) );
    $headers = str_getcsv( array_shift( $lines ) );
    
    foreach ( $lines as $line ) {
        $row = array();
    
        foreach ( str_getcsv( $line ) as $key => $field )
            $row[$headers[$key]] = $field;
    
        $row = array_filter( $row );
    
        $listAwards[] = $row;
    }
    
    return $listAwards;
}

function generateUniqueCode($longCode){

    $code = md5(uniqid(rand(),true));

    if($longCode != ""){
        $code = substr($code, 0, $longCode);
    }

    return $code;
}

function randonIndex($numAwards){
    
    $index = rand(1,$numAwards);
    
    return $index;
}

function saveAwardCode(){
    global $listAwards;
    global $numAwards;
  
    $index = randonIndex($numAwards);

    $countAwards = $listAwards[$index]['cantidad'];
   
    if($countAwards == 0){
        $index++;
        $award = saveAwardCode($index);
    }else{
        $award = $listAwards[$index]['premio'];
        $listAwards[$index]['cantidad'] = $countAwards-1; 
    }

    return $award;
}

function saveToListCode($idCode,$code,$awards){
    global $listCode;

    $listCode[$idCode] = array($idCode, $code, $awards);
}

function generateCsvAwards($listCode, $pathListCode){

    $f = fopen($pathListCode, 'w'); 
 
    fputcsv($f, array("Id","Código","Premio"));
    foreach ($listCode as $row){
        fputcsv($f, $row);
    }

    fclose($f);

    print_r("Fichero Generado");
}

?>