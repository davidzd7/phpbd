<?php
set_time_limit(0);
/**[D] 2017-07-05 
*FUNCIONES RELACIONADAS A LA BASE DE DATOS
*/

//[D]CONEXIÓN MySQL
function conexion()
{
	$conexion = mysqli_connect(_HOST_, _USER_, _PASSWORD_, _DATA_BASE_);
	
	if($conexion === false) { 
	 echo 'Error al establecer la comunicación con MySQL: <br>'.mysqli_connect_error(); 
	} /*else {
	 echo 'Conectado a la base de datos';
	}*/
	
	return $conexion;
}

//[D]CERRAR CONEXIÓN MySQL
function cerrarConexion($conexion)
{
	if( mysqli_close($conexion) )
	{
		//echo "<br>Conexión cerrada con éxito.<br />";
	}
	else
	{
		 echo "<br>Falla al cerrar la conexión.<br />";
		 die( mysqli_error($conexion));
	}
}

//[D]CONSULTA A MySQL QUE DEVUELVE UN ARRAY (ASOCIATIVO | NUMERICO | NULL)
//-----DESCRIPCION DE LAS VARIABLES PARA LA FUNCIÓN
//$tabla       -> NOMBRE DE LA TABLA
//$select_     -> CAMPOS DE LA TABLA (EJEMPLO: *)
//$where_      -> condicion WHERE (EJEMPLO : usuario = 5 AND ...)
//$AoN         -> TIPO DE ARRAY (//ASOCIATIVO : 'A' MAYUSCULA //NUMERICO  : 'N' MAYUSCULA)
function consultaBDRegresaArray($tabla, $select_, $where_ ,$AoN, $imprimirQyR)
{
	$con=conexion();
	$new_array=array();
	
	if($where_=='null' | $where_=="")
	{
		$where_="";
	}
	else
	{
		$var=$where_;
		$where_=" WHERE ".$var;
	}
	
	$query = "SELECT ".$select_. " FROM ".$tabla." ".$where_;
	if($imprimirQyR)
	{
		echo "<p>".$query."</p>";
	}
	
	$result = mysqli_query($con,$query);
	if(!$result)
	{
		echo "<hr>[Error]".mysqli_error($con);
	}else{if($imprimirQyR){echo "[OK]";}}

	//SE DEVUELVE ASOCIATIVO
	if($AoN=="A")
	{
		while( $row = mysqli_fetch_assoc( $result))
		{
			$new_array[] = $row; // Inside while loop
		}	
	}
	//SE DEVUELVE NUMERICO
	if($AoN=="N")
	{
		while( $row = mysqli_fetch_row( $result))
		{
			$new_array[] = $row; // Inside while loop
		}	
	}
	
	if($imprimirQyR)
	{
		echo "<pre>";
		print_r($new_array);
		echo "</pre>";
	}
	
	cerrarConexion($con);
	return $new_array;
}

//[D]INSERT A MySQL
//-----DESCRIPCION DE LAS VARIABLES PARA LA FUNCIÓN
//$tabla      -> NOMBRE DE LA TABLA
//$columnas  -> EJEMPLO: column1, column2, column3, ...
//$valores   -> EJEMPLO: value1, value2, value3, ...
//$imprimir  -> DESPLEGAR INFORMACIÖN EN PANTALLA (true)
function insertarBD($tabla, $columnas, $valores , $imprimir)
{
	$con=conexion();
	$query = "INSERT INTO ".$tabla." (".$columnas.") VALUES (".$valores.")";
	if($imprimir)
	{
		echo $query;
	}
	$result = mysqli_query($con,$query);
	if(!$result)
	{
		echo "<hr>[Error]".mysqli_error($con);
	}else{if($imprimir){echo "[OK]";}}
	cerrarConexion($con);
}

//[D]UPDATE A MySQL
//-----DESCRIPCION DE LAS VARIABLES PARA LA FUNCIÓN
//$tabla            -> NOMBRE DE LA TABLA
//$columnasValores  -> column1 = value1, column2 = value2, ...
//$where_   		-> condicion...(EJEMPLO user=1)
//$imprimir 		-> DESPLEGAR INFORMACIÖN EN PANTALLA (true)
function updateBD($tabla, $columnasValores, $where_ , $imprimir)
{
	$status = false;
	$con=conexion();
	
	if(strlen($where_)<3)
	{
		echo "¡Cuidado, agrega condiciones para el 'WHERE' , [query no ejecutado]";
	}
	else
	{
		$query = "UPDATE ".$tabla."
		SET ".$columnasValores."
		WHERE ".$where_;
		if($imprimir)
		{
			echo $query;
		}
		$result = mysqli_query($con,$query);
		if($result){ $status = true; }
		if(!$result)
		{
			echo "<hr>[Error]".mysqli_error($con);
		}else{if($imprimir){echo "[OK]";}}
	}
	cerrarConexion($con);
	return $status;
}

//[D] 2017-08-14 FUNCIÓN QUE EJECUTA UN STORE PROCEDURE
//EJEMPLO -> callSP("call up_folio(".$folio.")",true);
function callSP($name_store_procedure,$imprimir)
{
	$con=conexion();

	$result = mysqli_query($con,$name_store_procedure);
	if(!$result)
	{
		echo "<hr>[Error SP]".mysqli_error($con);
	}else{if($imprimir){echo "[OK SP]<br>";}}

	cerrarConexion($con);
}

function query_excepcion($query ,$AoN, $imprimirQyR, $regresa)
{
	$con=conexion();
	$new_array=array();
	

	if($imprimirQyR)
	{
		echo "<span>".$query."</span>";
	}
	
	$result = mysqli_query($con,$query);
	if(!$result)
	{
		echo "<hr>[Error]".mysqli_error($con);
	}else{if($imprimirQyR){echo "[OK]";}}

	echo "<br>";
	
	if($regresa=="regresa")
	{
		//SE DEVUELVE ASOCIATIVO
		if($AoN=="A")
		{
			while( $row = mysqli_fetch_assoc( $result))
			{
				$new_array[] = $row; // Inside while loop
			}	
		}
		//SE DEVUELVE NUMERICO
		if($AoN=="N")
		{
			while( $row = mysqli_fetch_row( $result))
			{
				$new_array[] = $row; // Inside while loop
			}	
		}
		
		if($imprimirQyR)
		{
			echo "<pre>";
			print_r($new_array);
			echo "</pre>";
		}
	}
	
	cerrarConexion($con);
	return $new_array;
} 	
