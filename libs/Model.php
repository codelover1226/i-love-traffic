<?php
$currentPage = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($_SERVER["REQUEST_METHOD"] == "GET" && strcmp(basename($currentPage), basename(__FILE__)) == 0) {
    http_response_code(404);
    exit("");
}
if (file_exists("load_classes.php")) {
    require_once "load_classes.php";
} else {
    if (file_exists("../load_classes.php")) {
        require_once "../load_classes.php";
    }
}
class Model extends dataFilter
{
    private $db;
    public function __construct()
    {
        $this->db = dbConnection::getDBInstance();
    }
    protected function countAll($table)
    {
        $query = "SELECT COUNT(*) FROM " . $table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    protected function getAll($table, $limit, $offset, $sort, $column = NULL, $column_value = NULL)
    {
        if ($column_value != NULL && $column != NULL) {
            $query = "SELECT * FROM " . $table . " WHERE " . $column . " = ? ORDER BY id " . $sort . " LIMIT " . $limit . " OFFSET " . $offset;
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->filter($column_value));
            $stmt->execute();
        } else {
            $query = "SELECT * FROM " . $table . " ORDER BY id " . $sort . " LIMIT " . $limit . " OFFSET " . $offset;
            $stmt = $this->db->prepare($query);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function deleteData($table, $column_value, $column = "id")
    {
        $query = "DELETE FROM " . $table . " WHERE " . $column . " = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->filter($column_value));
        return $stmt->execute();
    }
    protected function insertData($table, $datas)
    {
        $data_keys = array_keys($datas);
        $data_values = array_values($datas);
        $query = "INSERT INTO " . $table . " (id ," . implode(", ", $data_keys) . ") VALUES (NULL, " . implode(", ", array_fill(0, count($data_values), "?")) . ")";
        $stmt = $this->db->prepare($query);
        for ($i = 0; $i < count($data_values); $i++) {
            $data_values[$i] = $this->filter($data_values[$i]);
        }
        return $stmt->execute($data_values);
    }
    protected function getSingle($table, $column_name, $columns_value)
    {
        $query = "SELECT * FROM " . $table . " WHERE " . $column_name . " = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->filter($columns_value));
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    protected function countWithCondition($table, $column, $column_value)
    {
        $query = "SELECT COUNT(*) FROM " . $table . " WHERE " . $column . " = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $this->filter($column_value));
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    protected function updateData($table, $condition_column, $condition_column_value, $data)
    {
        $keys = array_keys($data);
        $data = array_values($data);
        $condition_column_value = $this->filter($condition_column_value);
        $query = "UPDATE " . $table . " SET " . implode(" = ?, ", $keys) . " = ? WHERE " . $condition_column . " = '" . $condition_column_value . "'";
        $stmt = $this->db->prepare($query);
        for ($counter = 0; $counter < count($data); $counter++) {
            $stmt->bindValue($counter + 1, $this->filter($data[$counter]));
        }
        return $stmt->execute();
    }
    protected function getDBConnection()
    {
        return $this->db;
    }
}

?>