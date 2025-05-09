<?php
class DBConn
{
    private $host = 'localhost:3307';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'smarthome';
    private $conn;
    private $connectedPort = null;

    public function __construct()
    {
        $this->connectedPort = 3306;
        $this->conn = $this->tryConnect($this->connectedPort);

        if (!$this->conn) {
            $this->connectedPort = 3307;
            $this->conn = $this->tryConnect($this->connectedPort);
        }

        if (!$this->conn) {
            die("Connection failed on both ports 3306 and 3307.");
        }

        $this->conn->set_charset("utf8mb4");
    }
    public function __destruct()
    {
        $this->close();
    }

    private function tryConnect($port)
    {
        $conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname, $port);
        return $conn->connect_error ? null : $conn;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function validate($string)
    {
        return $this->conn->real_escape_string(trim($string));
    }



    public function insert(string $tableName, array $fields, string $types = ''): mixed
    {
        $columns = implode(", ", array_keys($fields));
        $placeholders = implode(", ", array_fill(0, count($fields), '?'));
        $stmt = $this->conn->prepare("INSERT INTO $tableName ($columns) VALUES ($placeholders)");

        if (!$stmt) {
            return false;
        }

        $values = array_values($fields);

        // If types not provided, determine automatically
        if (empty($types)) {
            $types = '';
            foreach ($values as $val) {
                if (is_int($val)) {
                    $types .= 'i';
                } elseif (is_float($val)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
            }
        }

        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    public function selectWhere(
        string $tableName,
        array $conditions = [], // Now optional
        string $orderField = '',
        int $limit = 0,
        string $order = 'DESC',
        string $types = '',
        array $columns = []
    ): array|false {
        // Default to '*' if no specific columns are given
        $columns = empty($columns) ? '*' : implode(',', $columns);

        // Start building the SQL query
        $sql = "SELECT $columns FROM $tableName";

        $values = [];

        // Add WHERE clause if conditions exist
        if (!empty($conditions)) {
            $where = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($conditions)));
            $sql .= " WHERE $where";
            $values = array_values($conditions);

            // Automatically assign types if not passed
            if (empty($types)) {
                $types = '';
                foreach ($values as $val) {
                    if (is_int($val)) {
                        $types .= 'i';
                    } elseif (is_float($val)) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                }
            }
        }

        // Add ORDER BY if specified
        if ($orderField) {
            $sql .= " ORDER BY $orderField $order";
        }

        // Add LIMIT if specified
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        // Bind parameters only if conditions are present
        if (!empty($conditions)) {
            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();

        // Get results and return
        $result = $stmt->get_result();
        return $result && $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : false;
    }



    public function close()
    {
        $this->conn->close();
    }
}
