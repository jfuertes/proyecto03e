<?php
    function checkLDAP($usuario, $ldappass){
        //$host     = "172.22.4.145";
    	//$host     = "172.20.1.40";
        //$host     = "172.20.1.41";
        $dominio  = "nextelperu.net";
        $connect  = ldap_connect( $dominio );
        if ( $connect ) {
            # conexion to LDAP successful!
            $ldaprdn   = $usuario . "@" . $dominio;
            $base_dn   = "DC=nextelperu,DC=net";
            $filter    = "(samaccountname=$usuario)";
            $justthese = array('cn', 'mail','samaccountname','description','department', 'telephonenumber', 'mobile', 'title');
            ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
            if ( @ldap_bind($connect, $ldaprdn, $ldappass) ) {
                # LDAP bind successful!
                $read = ldap_search($connect, $base_dn, $filter, $justthese);
                if ( $read ) {
                    $info = ldap_get_entries($connect, $read);
                    if( $info['count'] > 0 ) {
                      $respuesta = array(
                        'count'       => 1,
                        'success'       => 1,
                        'nombres'     => @$info[0]['cn'][0],
                        'mail'        => @$info[0]['mail'][0],
                        'mobile'      => @$info[0]['mobile'][0],
                        'anexo'       => @$info[0]['telephonenumber'][0],
                        'gerencia'    => @$info[0]['department'][0],
                        'descripcion' => @$info[0]['description'][0],
                        'cargo'       => @$info[0]['title'][0]
                        );
                    } else {
                        $respuesta = array('error' => 'LDAP search Failed!. No se realizo la busqueda de informacion del usuario debido a un problema no determinado', 'count' => 0);
                    }
                } else {
                    $respuesta = array('error' => 'No se encontro informacion del usuario en el directorio LDAP.');
                }
            } else {
                $respuesta = array('error' => 'Unable to bind to server: Credenciales incorrectas', 'count' => 0);
            }
            ldap_unbind($connect);
        } else {
            $respuesta = array('error' => 'No se pudo realizar la coneccion hacia LDAP.', 'count' => 0);
        }
        return $respuesta;
    }
?>