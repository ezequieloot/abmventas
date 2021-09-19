<?php

class Cliente
{
    private $idcliente;
    private $nombre;
    private $cuit;
    private $telefono;
    private $correo;
    private $fecha_nac;
    private $fk_idprovincia;
    private $nombreprovincia;
    private $localidad;
    private $domicilio;

    public function __construct()
    {

    }

    public function __get($atributo)
    {
        return $this->$atributo;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarFormulario($request)
    {
        $this->idcliente = isset($request["id"]) ? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"]) ? $request["txtNombre"] : "";
        $this->cuit = isset($request["txtCuit"]) ? $request["txtCuit"] : "";
        $this->telefono = isset($request["txtTelefono"]) ? $request["txtTelefono"] : "";
        $this->correo = isset($request["txtCorreo"]) ? $request["txtCorreo"] : "";
        $this->fk_idprovincia = isset($request["lstProvincia"]) ? $request["lstProvincia"] : "";
        $this->localidad = isset($request["txtLocalidad"]) ? strtoupper($request["txtLocalidad"]) : "";
        $this->domicilio = isset($request["txtDomicilio"]) ? strtoupper($request["txtDomicilio"]) : "";
        if (isset($request["txtAnioNac"]) && isset($request["txtMesNac"]) && isset($request["txtDiaNac"])) {
            $this->fecha_nac = $request["txtAnioNac"] . "-" . $request["txtMesNac"] . "-" . $request["txtDiaNac"];
        }
    }

    public function insertar()
    {
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        //Arma la query
        $sql = "INSERT INTO clientes (
                    nombre,
                    cuit,
                    telefono,
                    correo,
                    fecha_nac,
                    fk_idprovincia,
                    localidad,
                    domicilio
                ) VALUES (
                    '$this->nombre',
                    '$this->cuit',
                    '$this->telefono',
                    '$this->correo',
                    '$this->fecha_nac',
                    $this->fk_idprovincia,
                    '$this->localidad',
                    '$this->domicilio'
                );";
        // print_r($sql);exit;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        //Obtiene el id generado por la inserción
        $this->idcliente = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }

    public function actualizar()
    {

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "UPDATE clientes SET
                nombre = '" . $this->nombre . "',
                cuit = '" . $this->cuit . "',
                telefono = '" . $this->telefono . "',
                correo = '" . $this->correo . "',
                fecha_nac =  '" . $this->fecha_nac . "',
                fk_idprovincia =  '" . $this->fk_idprovincia . "',
                localidad =  '" . $this->localidad . "',
                domicilio =  '" . $this->domicilio . "'
                WHERE idcliente = " . $this->idcliente;

        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "DELETE FROM clientes WHERE idcliente = " . $this->idcliente;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId()
    {
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT A.idcliente,
                        A.nombre,
                        A.cuit,
                        A.telefono,
                        A.correo,
                        A.fecha_nac,
                        A.fk_idprovincia,
                        A.localidad,
                        A.domicilio,
                        B.idprovincia,
                        B.nombre as nombreprovincia
                FROM clientes A
                INNER JOIN provincias B ON A.fk_idprovincia = B.idprovincia
                WHERE idcliente = $this->idcliente";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if ($fila = $resultado->fetch_assoc()) {
            $this->idcliente = $fila["idcliente"];
            $this->nombre = $fila["nombre"];
            $this->cuit = $fila["cuit"];
            $this->telefono = $fila["telefono"];
            $this->correo = $fila["correo"];
            $this->fecha_nac = $fila["fecha_nac"];
            $this->fk_idprovincia = $fila["fk_idprovincia"];
            $this->nombreprovincia = $fila["nombreprovincia"];
            $this->localidad = $fila["localidad"];
            $this->domicilio = $fila["domicilio"];
        }
        $mysqli->close();

    }

     public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE, Config::BBDD_PORT);
        $sql = "SELECT 
                    idcliente,
                    nombre,
                    cuit,
                    telefono,
                    correo,
                    fecha_nac,
                    fk_idprovincia,
                    localidad,
                    domicilio
                FROM clientes";
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo

            while($fila = $resultado->fetch_assoc()){
                $entidadAux = new Cliente();
                $entidadAux->idcliente = $fila["idcliente"];
                $entidadAux->nombre = $fila["nombre"];
                $entidadAux->cuit = $fila["cuit"];
                $entidadAux->telefono = $fila["telefono"];
                $entidadAux->correo = $fila["correo"];
                $entidadAux->fecha_nac = $fila["fecha_nac"];
                $entidadAux->fk_idprovincia = $fila["fk_idprovincia"];
                $entidadAux->localidad = $fila["localidad"];
                $entidadAux->domicilio = $fila["domicilio"];
                $aResultado[] = $entidadAux;
            }
        }
        return $aResultado;
    }

}
