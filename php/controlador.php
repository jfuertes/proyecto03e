	<?php
class controlador{
    private $cnn;
    
    function __construct() {        
        //include_once dirname(__FILE__) . '/config.php'; 
        
        $servidor="localhost";
        $puerto="1521";
        $usuario="SYSTEM";
        $clave="123456";
        $sid="xe";

        $this->cnn = oci_connect($usuario, $clave, $servidor.'/'.$sid);
        if (!$this->cnn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
    }        
    
    function consultar($csql){
        try{
            $stid = oci_parse($this->cnn, $csql);                  
            oci_execute($stid);
            $res = array();
            $row = array();
            while($row = oci_fetch_array($stid,OCI_ASSOC+OCI_RETURN_NULLS)) {                
                $res[] = $row;                
            }     
            oci_free_statement($stid);
            return $res;            
        }catch(Exception $e){
            $response = array('error' => $e->getMessage(), 'database' => 'Error al intentar conectarse a la base de Datos' );
            echo json_encode($response);
            exit;
        }
    }
    
    function ejecutar($csql){
        try{
            $stid = oci_parse($this->cnn, $csql);                  
            oci_execute($stid);
            $response = array('error' => '', 'database' => 'OK' );            
            return $response;
        }catch(Exception $e){
            $response = array('error' => $e->getMessage(), 'database' => 'Error al intentar conectarse a la base de Datos' );            
            return $response;
        }
    }
    
    function listar_proymacro(){
        $csql="select * from PROYRED.PROYMACRO order by nombreproymacro";     
        return $this->consultar($csql);
    }
    
    function buscar_proymacro($idpm){
        $csql="select * from PROYRED.PROYMACRO where idproymacro=$idpm";     
        return $this->consultar($csql);
    }
    
    function listar_proyecto(){
        $csql="select * from proyred.proyecto";
        return $this->consultar($csql);
    }
    
    function listar_modulos($idpm){
        $csql="select m.idmodulo,nombremodulo from proyred.modulo m
            inner join PROYRED.ACCESO t on m.idmodulo=t.idmodulo
            where t.idproymacro=$idpm";
        return $this->consultar($csql);
    }
    
    function listar_all_modulos(){
        $csql="select m.idmodulo,nombremodulo from proyred.modulo m";
        return $this->consultar($csql);
    }
    
    function buscar_modulo($idmod){
        $csql="select * from proyred.modulo m where idmodulo=$idmod";
        return $this->consultar($csql);
    }
    
    function listar_parametros($idpm,$idmod){
        $csql="select p.idparametro,p.nombreparam ,pm.orden,pm.idproymacro,p.idtipodato,tp.nombretipodato,p.usamaestroparam
            from proyred.pmparametro pm
            inner join proyred.parametro p on pm.idparametro=p.idparametro            
            inner join proyred.tipodato tp on p.idtipodato=tp.idtipodato
            where pm.idproymacro=$idpm and pm.idmodulo=$idmod
            order by pm.orden";
        return $this->consultar($csql);
    }
    
    function listar_parametros_valor_proyecto($idproy,$idmod){
        $csql="select v.idvalor,p.idparametro,p.nombreparam ,pm.orden,pm.idproymacro,p.idtipodato,p.usamaestroparam,
            case
              when p.idtipodato=1 then to_char(v.valornumber,'999,999')
              when p.idtipodato=2 then to_char(v.valornumber,'999,999.999')
              when p.idtipodato=3 then v.valorstr
              when p.idtipodato=4 then to_char(v.valordate,'DD/MM/YYYY')
            end val
            from proyred.pmparametro pm
            inner join proyred.parametro p on pm.idparametro=p.idparametro    
            inner join proyred.valor v on  p.idparametro=v.idparametro
            inner join proyred.proyecto py on v.idproyecto=py.idproyecto
            where py.idproyecto=$idproy and pm.idmodulo=$idmod
            order by pm.orden";
        return $this->consultar($csql);
    }
    
    function getValor($idproy,$idparam){
        $csql="SELECT 
            v.idvalor,
            case 
              when p.idtipodato=1  then to_char(v.valornumber,'999,999')
              when p.idtipodato=2 then to_char(v.valornumber,'999,999.999')
              when p.idtipodato=3 then v.valorstr
              when p.idtipodato=4 then to_char(v.valordate,'DD/MM/YYYY')
            end valor  
            FROM proyred.valor v
            inner join proyred.parametro p on v.idparametro=p.idparametro
            where v.idproyecto=$idproy and v.idparametro=$idparam";
        $val = $this->consultar($csql);
        //echo json_encode($val);
        return trim($val[0]['VALOR']);
    }
    
    function listar_proyectos($idpm,$idmod){
        $csql="select idproyecto,codproyecto,nombreproy from proyred.proyecto
        where idproymacro=$idpm and estadoproy=1
        order by codproyecto";
        $proys = $this->consultar($csql);
        $params = $this->listar_parametros($idpm, $idmod);
        $arr=array();
        $rpta = array();
        $n=0;        
        foreach($proys as $p){            
            $arr['IDPROYECTO'] = $p['IDPROYECTO'];
            $arr['CODPROYECTO'] = $p['CODPROYECTO'];
            $arr['NOMBREPROY'] = $p['NOMBREPROY'];
            foreach($params as $par){
                $arr[$par['NOMBREPARAM']] = $this->getValor($p['IDPROYECTO'],$par['IDPARAMETRO']);
            }
            $arr['selected'] = false;
            $rpta[$n]=$arr;
            $n++;
        }
        return $rpta;
        
    }
    
    function listar_maestro($idpar){
        $csql="select * from proyred.maestro where idparametro=$idpar order by etiqueta";
        return $this->consultar($csql);
    }
    
    function registrar_proyecto($codproy,$nombreproy,$idproymacro,$vals){
        $csql="select count(*)+1 nproys from proyred.proyecto";
        $r=$this->consultar($csql);
        $idproy = $r[0]['NPROYS'];
        $csql="insert into proyred.proyecto(idproyecto,codproyecto,nombreproy,estadoproy,idproymacro)";
        $csql.="values($idproy ,'$codproy','$nombreproy',1,$idproymacro)";
        $this->ejecutar($csql);
        foreach($vals as $key => $value){
            $csql="select * from proyred.parametro where idparametro=".str_replace("idpar","",$key);
            $par = $this->consultar($csql);
            $csql="select max(idvalor)+1 maxval from proyred.valor";
            $r=$this->consultar($csql);
            $nombrecol="";
            if($par[0]['IDTIPODATO']==1 || $par[0]['IDTIPODATO']==2){
                $nombrecol="valornumber";     
                $valor = "$value";
            }else if($par[0]['IDTIPODATO']==3){
                $nombrecol="valorstr";
                $valor = "'$value'";
            }else if($par[0]['IDTIPODATO']==4){
                $nombrecol="valordate";
                $valor = "'$value'";
            }
            $csql="insert into proyred.valor(idvalor,idparametro,idproyecto,$nombrecol) ";
            $csql.="values(".$r[0]['MAXVAL'].",".$par[0]['IDPARAMETRO'].",$idproy,$valor)";
            //print $csql;
            $this->ejecutar($csql);
        }
    }
    
    function actualizar_proyecto($idproy,$codproy,$nombreproy,$vals){
        
        $csql="update proyred.proyecto set  nombreproy = '$nombreproy' where idproyecto=$idproy";        
        $this->ejecutar($csql);
        //$csql="delete from proyred.valor where idproyecto=$idproy";
        //$this->ejecutar($csql);
        foreach($vals as $key => $value){            
            $csql="select v.idvalor,p.idparametro,v.idproyecto,p.idtipodato,
                v.valorstr,
                v.valornumber,
                v.valordate 
                from proyred.valor v
                inner join proyred.parametro p on v.idparametro=p.idparametro where idvalor=".str_replace("idval","",$key);
            $par = $this->consultar($csql);            
            $nombrecol="";
            if($par[0]['IDTIPODATO']==1 || $par[0]['IDTIPODATO']==2){
                $nombrecol="valornumber";     
                $valor = ($value==''?"null":$value);
            }else if($par[0]['IDTIPODATO']==3){
                $nombrecol="valorstr";
                $valor = ($value==''?"null":"'$value'");
            }else if($par[0]['IDTIPODATO']==4){
                $nombrecol="valordate";
                $valor = ($value==''?"null":"'$value'");
            }
            $csql="update proyred.valor set $nombrecol=$valor where idvalor=".$par[0]['IDVALOR'];            
            $this->ejecutar($csql);
        }
    }
    
    function buscar_proyecto_x_codigo($codproy,$idproymacro){
        $csql="select * from proyred.proyecto where upper(codproyecto)=upper('$codproy') and idproymacro=$idproymacro";        
        return $this->consultar($csql);        
    }
    
    function buscar_proyecto_x_id($idproy){
        $csql="select * from proyred.proyecto where idproyecto=$idproy";        
        return $this->consultar($csql);        
    }
    
    
}

$cx=new controlador();

if(isset($_GET['opc']) && isset($_GET['class'])){
    header('Content-Type: application/json');
    
    $opc = $_GET['opc'];
    $class = $_GET['class'];
    if($opc==='list' && $class==='proymacro'){
        echo json_encode($cx->listar_proymacro());    
    }else if($opc === 'list' && $class === 'modxpm'){
        $idproymacro = $_GET['idproymacro'];
        echo json_encode($cx->listar_modulos($idproymacro));
    }else if($opc === 'list' && $class==='param'){
        $idproymacro = $_GET['idproymacro'];
        $idmodulo = $_GET['idmodulo'];        
        echo json_encode($cx->listar_parametros($idproymacro, $idmodulo));       
    }else if($opc === 'list' && $class==='proy'){
        $idproymacro = $_GET['idproymacro'];
        $idmodulo = $_GET['idmodulo'];
        echo json_encode($cx->listar_proyectos($idproymacro, $idmodulo));
    }else if($opc === 'list' && $class==='mod'){
        echo json_encode($cx->listar_all_modulos());
    }
    
}
?>