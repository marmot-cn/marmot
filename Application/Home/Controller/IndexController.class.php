<?php
namespace Home\Controller;
use System\Classes\Controller;

class IndexController extends Controller{

	public function index(){
        $swagger = \Swagger\scan('/var/www/html/marmot/Application/User');
        header('Content-Type: application/json');
        echo $swagger;
    
		return true;		
	}
}
?>
