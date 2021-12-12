<?php
// Headers
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST");
header("Allow: GET, POST");

// Includes
include_once '../../config/Database.php';
include_once '../../models/Employee.php';

// Database connection
$db = new Database();
$connection = $db->connect();

// Employee
$employee = new Employee($connection);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['emp_no'])) {
            $employee->emp_no = $_GET['emp_no'];
            $employee->read();
            $employee_arr = array(
                'emp_no' => $employee->emp_no,
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'gender' => $employee->gender,
                'dept_name' => $employee->dept_name,
                'title' => $employee->title,
                'salary' => $employee->salary,
                'hire_date' => $employee->hire_date,
                'birth_date' => $employee->birth_date,
            );
            http_response_code(200);
            echo json_encode($employee_arr);
        }
        else {
            $employees = $employee->read_all();
            http_response_code(200);
            echo json_encode($employees);
        }
        break;
    case 'POST':
        $new_employee = json_decode(file_get_contents("php://input"));

        $employee->first_name = $new_employee->first_name;
        $employee->last_name = $new_employee->last_name;
        $employee->gender = $new_employee->gender;
        $employee->dept_no = $new_employee->dept_no;
        $employee->title = $new_employee->title;
        $employee->salary = $new_employee->salary;
        $employee->birth_date = $new_employee->birth_date;

        if ($employee->create()) {
            http_response_code(201);
            echo json_encode(
                array(
                    'code' => 'OK',
                    'message' => 'Employee created',
                )
            );
        }
        else {
            http_response_code(400);
            echo json_encode(
                array(
                    'code' => 'ERROR',
                    'message' => 'Employee not created',
                )
            );
        }

        break;
}