<?php

class DB
{
    public function __construct($host="localhost", $user="admin", $password="", $database="telemetry")
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;       
    }
    
    protected function connect()
    {
        return new mysqli($this->host, $this->user, $this->password, $this->database);      
    }   
    
    public function storeTemperature($payload)
    {
       
        $db = $this->connect();
        $query = "INSERT INTO temperature (temperature, source) VALUES (?, ?)";
        $stmt = $db->prepare($query);        
        $stmt->bind_param("ss", $temperature, $source);
        $temperature = substr($payload, -5);	        
        $source = substr($payload, 0, 2);    
        $stmt->execute();        
        $stmt->close(); 
    }

    public function getTemperatures() {
        $db = $this->connect();
        $query = "SELECT * FROM temperature ORDER BY id DESC LIMIT 20";
        $result = $db->query($query);   
        while ( $row = $result->fetch_object() ) {
            $results[] = $row;    
        }
        return json_encode($results, JSON_PRETTY_PRINT, 4);
    }
    
    
}

