<?php
namespace App\Model;

use App\Lib\Response;

class EmployeeModel
{
    //private $db;
    private $file = 'employees.json';
    private $response;

    public function __CONSTRUCT()
    {
        //$this->db = Database::StartUp();
        //$this->response = new Response();
    }

    public function GetAll($campo = '',$valor = '')
    {
		try
		{
			/*$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table");
			$stm->execute();

			$this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();

            return $this->response;*/

            $dataFromFile = file_get_contents("../app/json/".$this->file);
            $employees = json_decode($dataFromFile, true);

            if($campo && $valor){
                $employeesT = array();
                foreach ($employees as $key => $a_employee) {
                        # code...
                        if($a_employee[$campo] == $valor){
                           $employeesT[] = $a_employee;      
                           break;
                        }
                }  
                $employees = $employeesT;                    
            }  

            return $employees;

            //foreach ($employees as $employee) {
            //    print_r($employee);
            //}
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }
     public function GetRange($campo, $valorMin, $valorMax)
    {
        try
        {
            /*$result = array();

            $stm = $this->db->prepare("SELECT * FROM $this->table");
            $stm->execute();

            $this->response->setResponse(true);
            $this->response->result = $stm->fetchAll();

            return $this->response;*/
            echo "";

            $dataFromFile = file_get_contents("../app/json/".$this->file);
            $employees = json_decode($dataFromFile, true);

            if($campo && $valorMin && $valorMax){
                $employeesT = array();
                foreach ($employees as $key => $a_employee) {
                        $a_employee[$campo] = str_replace(",","",substr($a_employee[$campo] , 1));
                       
                        if($a_employee[$campo] >= $valorMin && $a_employee[$campo] <= $valorMax){
                           $employeesT[] = $a_employee;      
                        }
                }  
                $employees = $employeesT;                    
            }  

            return $employees;

            //foreach ($employees as $employee) {
            //    print_r($employee);
            //}
        }
        catch(Exception $e)
        {
            $this->response->setResponse(false, $e->getMessage());
            return $this->response;
        }
    }
    public function Get($id)
    {
		try
		{
			$result = array();

			$stm = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
			$stm->execute(array($id));

			$this->response->setResponse(true);
            $this->response->result = $stm->fetch();

            return $this->response;
		}
		catch(Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
            return $this->response;
		}
    }

    public function InsertOrUpdate($data)
    {
		try
		{
            if(isset($data['id']))
            {
                $sql = "UPDATE $this->table SET
                            Nombre          = ?,
                            Apellido        = ?,
                            Correo          = ?,
                            Sexo            = ?,
                            Sueldo          = ?,
                            Profesion_id    = ?,
                            FechaNacimiento = ?
                        WHERE id = ?";

                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['Nombre'],
                            $data['Apellido'],
                            $data['Correo'],
                            $data['Sexo'],
                            $data['Sueldo'],
                            $data['Profesion_id'],
                            $data['FechaNacimiento'],
                            $data['id']
                        )
                    );
            }
            else
            {
                $sql = "INSERT INTO $this->table
                            (Nombre, Apellido, Correo, Sexo, Sueldo, Profesion_id, FechaNacimiento, FechaRegistro)
                            VALUES (?,?,?,?,?,?,?,?)";

                $this->db->prepare($sql)
                     ->execute(
                        array(
                            $data['Nombre'],
                            $data['Apellido'],
                            $data['Correo'],
                            $data['Sexo'],
                            $data['Sueldo'],
                            $data['Profesion_id'],
                            $data['FechaNacimiento'],
                            date('Y-m-d')
                        )
                    );
            }

			$this->response->setResponse(true);
            return $this->response;
		}catch (Exception $e)
		{
            $this->response->setResponse(false, $e->getMessage());
		}
    }

    public function Delete($id)
    {
		try
		{
			$stm = $this->db
			            ->prepare("DELETE FROM $this->table WHERE id = ?");

			$stm->execute(array($id));

			$this->response->setResponse(true);
            return $this->response;
		} catch (Exception $e)
		{
			$this->response->setResponse(false, $e->getMessage());
		}
    }
}
