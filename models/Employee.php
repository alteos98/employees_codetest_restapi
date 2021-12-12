<?php
class Employee {
    // Parameters
    private $connection;

    public $emp_no;
    public $first_name;
    public $last_name;
    public $gender;
    public $dept_no;
    public $dept_name;
    public $title;
    public $salary;
    public $hire_date;
    public $birth_date;

    public function __construct($connection) {
      $this->connection = $connection;
    }

    /* 
    Get employees with the actual department, actual title and actual salary.
    Ordered by hiring date.
    Limited to 50.
    */
    public function read_all() {
        $query = '
            SELECT * 
            FROM employees e
            LEFT JOIN salaries s ON e.emp_no = s.emp_no AND s.to_date > NOW()
            LEFT JOIN titles t ON e.emp_no = t.emp_no AND t.to_date > NOW()
            LEFT JOIN dept_emp de ON e.emp_no = de.emp_no AND de.to_date > NOW()
            LEFT JOIN departments d ON d.dept_no = de.dept_no
            WHERE s.to_date IS NOT NULL AND t.to_date IS NOT NULL AND de.to_date IS NOT NULL
            ORDER BY e.hire_date DESC
            LIMIT 50
        ';

        // Prepare statement with the query
        $statement = $this->connection->prepare($query);

        // Execute the statement
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_ASSOC);

        $employees = $statement->fetchAll();

        return $employees;
    }

    /* 
    Get one employee with the actual department, actual title and actual salary.
    */
    public function read() {
        $query = '
            SELECT * 
            FROM employees e 
            LEFT JOIN salaries s ON e.emp_no = s.emp_no AND s.to_date > NOW()
            LEFT JOIN titles t ON e.emp_no = t.emp_no AND t.to_date > NOW()
            LEFT JOIN dept_emp de ON e.emp_no = de.emp_no AND de.to_date > NOW()
            LEFT JOIN departments d ON d.dept_no = de.dept_no
            WHERE e.emp_no = :emp_no AND s.to_date IS NOT NULL AND t.to_date IS NOT NULL AND de.to_date IS NOT NULL
        ';

        // Prepare statement with the query
        $statement = $this->connection->prepare($query);

        // Replace :emp_no with the emp_no of the employee that we want to get
        $statement->bindValue(':emp_no', $this->emp_no);

        // Execute the statement
        $statement->execute();

        // Fetch the employee
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $employee = $statement->fetch();

        // Set employee, so then we can access to his info
        $this->emp_no = $employee['emp_no'];
        $this->first_name = $employee['first_name'];
        $this->last_name = $employee['last_name'];
        $this->gender = $employee['gender'];
        $this->dept_name = $employee['dept_name'];
        $this->title = $employee['title'];
        $this->salary = $employee['salary'];
        $this->hire_date = $employee['hire_date'];
        $this->birth_date = $employee['birth_date'];
    }

    /*
    Insert one employee with the following parameters:
     - first_name
     - last_name
     - birth_date
     - gender
     - dept_no
     - title
     - salary
    */
    public function create() {
        // Set employee number
        $this->emp_no = $this->get_max_emp_no() + 1;
        echo $this->emp_no;

        // Clean data
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->birth_date = htmlspecialchars(strip_tags($this->birth_date));
        $this->gender = htmlspecialchars(strip_tags($this->gender));
        $this->dept_no = htmlspecialchars(strip_tags($this->dept_no));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->salary = htmlspecialchars(strip_tags($this->salary));

        return ($this->insert_into_employees() && $this->insert_into_titles() && $this->insert_into_salaries() && $this->insert_into_dept_emp());
    }

    private function get_max_emp_no() {
        $query = '
            SELECT emp_no
            FROM employees
            ORDER BY emp_no DESC
            LIMIT 1
        ';

        // Prepare statement with the query
        $statement = $this->connection->prepare($query);

        // Execute the statement
        $statement->execute();

        // Fetch the employee
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $employee = $statement->fetch();

        return $employee['emp_no'];
    }

    private function insert_into_employees() {
        $query = '
            INSERT INTO employees
            SET
                emp_no = :emp_no,
                first_name = :first_name,
                last_name = :last_name,
                birth_date = :birth_date,
                gender = :gender,
                hire_date = CURDATE()
        ';

        // Prepare statement with the query
        $statement = $this->connection->prepare($query);

        // Replace with the information of the employee that we want to insert
        $statement->bindValue(':emp_no', $this->emp_no);
        $statement->bindValue(':first_name', $this->first_name);
        $statement->bindValue(':last_name', $this->last_name);
        $statement->bindValue(':birth_date', $this->birth_date);
        $statement->bindValue(':gender', $this->gender);

        // Execute the statement
        return $statement->execute();
    }

    private function insert_into_titles() {
        $query = '
            INSERT INTO titles
            SET
                emp_no = :emp_no,
                title = :title,
                from_date = CURDATE(),
                to_date = "9999-01-01"
        ';

        // Prepare statement with the query
        $statement = $this->connection->prepare($query);

        // Replace with the information of the employee that we want to insert
        $statement->bindValue(':emp_no', $this->emp_no);
        $statement->bindValue(':title', $this->title);

        // Execute the statement
        return $statement->execute();
    }

    private function insert_into_salaries() {
        $query = '
            INSERT INTO salaries
            SET
                emp_no = :emp_no,
                salary = :salary,
                from_date = CURDATE(),
                to_date = "9999-01-01"
        ';

        // Prepare statement with the query
        $statement = $this->connection->prepare($query);

        // Replace with the information of the employee that we want to insert
        $statement->bindValue(':emp_no', $this->emp_no);
        $statement->bindValue(':salary', $this->salary);

        // Execute the statement
        return $statement->execute();
    }

    private function insert_into_dept_emp() {
        $query = '
            INSERT INTO dept_emp
            SET
                emp_no = :emp_no,
                dept_no = :dept_no,
                from_date = CURDATE(),
                to_date = "9999-01-01"
        ';

        // Prepare statement with the query
        $statement = $this->connection->prepare($query);

        // Replace with the information of the employee that we want to insert
        $statement->bindValue(':emp_no', $this->emp_no);
        $statement->bindValue(':dept_no', $this->dept_no);

        // Execute the statement
        return $statement->execute();
    }
}