<?php





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
							
	
	
	public function __construct($idSitie = null) {
		$this->Inicializar($idSitie);
	}
	
	/** Inicializa las variables por defecto en el constructor 
		o el cualquier otro método que lo llame */
	private function Inicializar($idSitie){
		if($idSitie != null){
			$this->siteId = $idSitie;			
		}		
		$this->infoSitio = $this->sitio($this->siteId);	
		
		$this->debag($this->infoSitio);
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
	* SSi no tiene parámetro toma el seteado por defecto  en la clase 
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
	public function categoriaDelSitio($id = null)
	{
		if($id != null){
			return $this->abrirUrl("/sites/".$id."/categories");
		} else {
			return $this->infoSitio->categories;
		}	
	}
	
	
	/** Devuelve la información de la categoría con las subcategorías asociadas si es que tiene 
		Se le pasa el id de una categoría o subcategoría  */	
	public function categoria($idCategoria)
	{
		if(array_key_exists($idCategoria, $this->categoria) != false){  // 4.0.7 
			return $this->categoria[$idCategoria] = $this->abrirUrl("/categories/".$idCategoria);
		} else {

		}
	}
	
	
	/** Esta función se encarga de hacer las peticiones (GET) básicas */	
	public function abrirUrl($parametroUrl){
	
		$contexto = stream_context_create($this->headerSend);
		$contenido = file_get_contents($this->sitioAPI.$parametroUrl, false, $contexto);
		return json_decode($contenido,$this->comoArreglo);	
	}
	
	public function debag($var){
		echo "<PRE>";
		print_r($var);
		echo "</PRE>";		
	}
    
	
}
   

?>