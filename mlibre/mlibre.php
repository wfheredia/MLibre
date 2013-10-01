<?php
/** ************************************************************************************************
 * 
 * 
 *  Autor: Wilian F. Heredia 
 * **************************************************************************************************
 **/

class MLibre {
	// Url de api 
	private $sitioAPI ="https://api.mercadolibre.com";	
	// Pais de la aplicacion por defecto es Argentina
	private $siteId = "MLA";	
	// Se guarda la informacion del sitio 
	private $infoSitio;		
	// Se guarda la categoria del sitio consultada
	// para ser re utilizada si es consultada
	private $categoria = array();	
	// si lo que vamos a devolver es como arreglo o como objeto JSON
	private $comoArreglo = false;
	
	// Cabecera a enviar con la petición 
	private $headerSend = array(
							  'http'=>array(
								'method'=>"GET",
								'header'=> "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1\r\n
								Accept: text/html,application/xhtml+xml,application/xml,application/json;q=0.9,*/*;q=0.8\r\n
								Accept-Language: es-es,es;q=0.8,en-us;q=0.5,en;q=0.3\r\n
								Content-Type: application/json; charset=UTF-8
								Connection: keep-alive\r\n"
							  )
							);	
							
	
	
	public function __construct($idSitio = null,$salidaComoArreglo = false) {
		$this->comoArreglo = $salidaComoArreglo;
		$this->Inicializar($idSitio);
	}
	
	/** Inicializa las variables por defecto en el constructor 
		o el cualquier otro método que lo llame */
	private function Inicializar($idSitie){
		if($idSitie != null){
			$this->siteId = $idSitie;			
		}		
		$this->infoSitio = $this->sitio($this->siteId);			
	}
	
	/**  Carga el sitio con el que va a trabajar la clase, si decidimos 
		cambiar el cargo en el constructor */
	public function setSitio($idSitie){
		$this->Inicializar($idSitie);
	}
	
	/** Recupera todos los sitios disponibles para mercado libre 
    * Devuelve un objeto o un arreglo segun sea hayan configurado */    	
	public function sitioAll(){
		$sites = $this->abrirUrl("/sites");	
		return $sites ;
	}
	
	/**  Recupera la información de un sitio (de algún pais) 
	* Si no tiene parámetro toma el seteado por defecto  en la clase 
	* que por defecto es MLA (argentina) */
	public function sitio($id = null){		
		if($id != null){
			return $this->abrirUrl("/sites/".$id);
		} else {
			return $this->infoSitio;
		}
	}
	
	/** Devuelve la principales categoría del sitio, en caso de pasar parámetro el 
	ID del sitio busca las del sitio seleccionado, si no se le pasa parámetro este 
	devuelve el del sitio cargado en la clase  */	
	private function categoriaDelSitio($id = null)
	{
		if($id != null){
			return $this->abrirUrl("/sites/".$id."/categories");
		} else {
			if($this->comoArreglo)
			{
				return $this->infoSitio["categories"];
			}else{
				return $this->infoSitio->categories;
			}			
		}	
	}
	
	
	/** Devuelve la información de la categoría con las subcategorías asociadas si es que tiene 
		Se le pasa el id de una categoría o subcategoría, 
        si no se le pasa un balor este devuelve las categorias generales   */	
	public function categoria($idCategoria = "")
	{
		$long_Categoria = strlen($idCategoria);
		if(($idCategoria != "") OR ($long_Categoria > 4)){ // Devuelve la información de la categoría con las subcategorías
			if(array_key_exists($idCategoria, $this->categoria) != false){  // 4.0.7 
				return $this->categoria[$idCategoria] = $this->abrirUrl("/categories/".$idCategoria);
			} else {		
				return $this->categoria[$idCategoria];
			}
		} else { // devuelve las categorias generales del sitio seteado en la clase
			if(($long_Categoria < 4) and ($idCategoria != ""))
			{
				return $this->categoriaDelSitio($idCategoria);
			}else{
				return $this->categoriaDelSitio();
			}			
		}
	}
	
	
	/** Esta función se encarga de hacer las peticiones (GET) básicas */	
	private function abrirUrl($parametroUrl){
		$contexto = stream_context_create($this->headerSend);
		$contenido = file_get_contents($this->sitioAPI.$parametroUrl, false, $contexto);
		//echo $contenido."\n";
		return json_decode($contenido,$this->comoArreglo);	
	} 
	
	/* a implementar en el futuro 
	public function abrirUrl($parametroUrl){		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_URL, $parametroUrl);
		curl_setopt($ch, CURLOPT_SSLVERSION,3); 
		$result = curl_exec($ch);
		curl_close($ch);
		print_r($result);
		return json_decode($result,$this->comoArreglo);	
	}*/
	
	public function debug($var){
		echo "<PRE>";
		print_r($var);
		echo "</PRE>";		
	}
    
	
}
   

?>