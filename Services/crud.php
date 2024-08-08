<?php
// crud.php

include 'database.php';

// CREATE: Insert a new record into any table
function createRecord($table, $data) {
    $conn = getDbConnection();
    
    // Build the SQL query dynamically
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), "?"));
    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    
    $stmt = $conn->prepare($sql);
    
    // Bind parameters dynamically
    $types = str_repeat('s', count($data)); // Assume all data are strings for simplicity
    $params = array_values($data);
    $stmt->bind_param($types, ...$params);
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    return $result;
}

// READ: Get all records from any table
function readRecords($table) {
    $conn = getDbConnection();
    
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);
    
    $records = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
    }
    
    $conn->close();
    return $records;
}

// READ: Get a single record by ID from any table
function readRecordById($table, $id) {
    $conn = getDbConnection();
    
    $sql = "SELECT * FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $record = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    return $record;
}

// UPDATE: Update a record by ID in any table
function updateRecord($table, $id, $data) {
    $conn = getDbConnection();
    
    // Build the SQL query dynamically
    $setClause = implode(", ", array_map(fn($col) => "$col = ?", array_keys($data)));
    $sql = "UPDATE $table SET $setClause WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    
    // Bind parameters dynamically
    $types = str_repeat('s', count($data)) . 'i'; // Assume all data are strings except ID
    $params = array_values($data);
    $params[] = $id; // Append ID at the end
    $stmt->bind_param($types, ...$params);
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    return $result;
}

// DELETE: Delete a record by ID from any table
function deleteRecord($table, $id) {
    $conn = getDbConnection();
    
    $sql = "DELETE FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    return $result;
}
?>
