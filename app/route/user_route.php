<?php

use App\Model\UserModel;
use App\Model\EmployeeModel;


$app->group('/employees/', function () {
    
    $this->get('get', function ($request, $response, $args) {
        $url = $this->router->pathFor('viewDetail', ['id'=>'444']);
        //$url = $request->getUri()->withPath($this->router->pathFor('viewDetail'));
        $response->write($url);
        return $response;
    });

    $this->get('searchRange/{min}/{max}', function ($req, $res, $args) {
        $um = new EmployeeModel();
        //if($args['email']) echo "---".$args['email'];
        $url = $this->router->pathFor('viewDetail', ['id'=>'444']);
       
        $a_employees = $um->GetRange('salary',$args['min'], $args['max']);
        

        //function defination to convert array to xml
        function array_to_xml($array, &$xml_user_info) {
            foreach($array as $key => $value) {
                if(is_array($value)) {
                    if(!is_numeric($key)){
                        $subnode = $xml_user_info->addChild("$key");
                        array_to_xml($value, $subnode);
                    }else{
                        $subnode = $xml_user_info->addChild("item$key");
                        array_to_xml($value, $subnode);
                    }
                }else {
                    $xml_user_info->addChild("$key",htmlspecialchars("$value"));
                }
            }
        }

        //creating object of SimpleXMLElement
        $xml_user_info = new SimpleXMLElement("<?xml version=\"1.0\"?><employees></employees>");

        //function call to convert array to xml
        array_to_xml($a_employees,$xml_user_info);

        return $res
           ->withHeader('Content-type', 'text/xml')
           ->getBody()
           ->write(
              $xml_user_info->asXML()
        );
    });

    $this->get('getAll[/{email}]', function ($req, $res, $args) {
        $um = new EmployeeModel();
        //if($args['email']) echo "---".$args['email'];
        $url = $this->router->pathFor('viewDetail', ['id'=>'444']);
       
        $a_employees = $um->GetAll('email',$args['email']);
        //print_r($a_employees);
        return $this->renderer->render($res, 'listar_empleados.phtml', array('a_employees'=>$a_employees, 'ruta'=> $this->router));
    });

     $this->post('getAll', function ($req, $res, $args) {
        $data = $req->getParsedBody();
        $um = new EmployeeModel();

        //print_r($data);
        $a_employees = $um->GetAll('email',$data['email']);
        //print_r($a_employees);
        return $this->renderer->render($res, 'listar_empleados.phtml', array('a_employees'=>$a_employees, 'email'=>$data['email'], 'ruta'=> $this->router));

        /*$app->Render(
            "litar_empleados.phtml",array('a_$employees'=>$a_employees)
        );*/
    });

     $this->get('viewDetail/{id}', function ($req, $res, $args) {
        
        $um = new EmployeeModel();
        //print_r($data);
        $a_employees = $um->GetAll('id',$args['id']);
        //print_r($a_employees);
        return $this->renderer->render($res, 'detalle_empleado.phtml', array('a_employees'=>$a_employees));

        /*$app->Render(
            "litar_empleados.phtml",array('a_$employees'=>$a_employees)
        );*/
    })->setName('viewDetail');
});

$app->group('/user/', function () {

    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello Users');
    });
    $this->get('getAll', function ($req, $res, $args) {
        $um = new UserModel();

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->GetAll()
            )
        );
    });

    $this->get('get/{id}', function ($req, $res, $args) {
        $um = new UserModel();

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Get($args['id'])
            )
        );
    });

    $this->post('save', function ($req, $res) {
        $um = new UserModel();

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->InsertOrUpdate(
                    $req->getParsedBody()
                )
            )
        );
    });

    $this->post('delete/{id}', function ($req, $res, $args) {
        $um = new UserModel();

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $um->Delete($args['id'])
            )
        );
    });
});
?>
