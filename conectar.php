<?php
/*
Modelo de conexion
*/
class Conectar
{
    private $bd;
    private $historico;
    function __construct()
    {
        $this->bd = new mysqli('localhost','root','','futbol');
        $this->historico= array();
    }

/*Muestra las estadisticas del historico de estrellas
$count_star es la cantidad de estrellas por historico
$count_regular es la cantidad de no estrellas por historico

*/
    public function getHistorico(){
        $consulta = $this->bd->query("Select (select count(h.star) from historico as h where h.star = 1) as count_star, (select count(j.star) from historico as j where j.star = 0) as count_regular from historico limit 1;");
        while ($filas=$consulta->fetch_assoc()) {
            $this->historico['count_star'] = intval($filas['count_star']);
            $this->historico['count_regular'] = intval($filas['count_regular']);
            $this->historico['ratio'] = floatval($filas['count_star'] / $filas['count_regular']);
        }
        return $this->historico;
    }

/*Guarda el historico de estrellas
$history es el json enviado a la api
$star es la respuesta generada en el sistema
*/
    public function saveHistorico($history,$star){
        if($consulta = $this->bd->query("INSERT INTO `historico` (`historico`, `star`) VALUES ('$history', '$star');")){
            return true;
        }
        return false;
    }
}
?>