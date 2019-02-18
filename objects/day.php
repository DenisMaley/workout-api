<?php

namespace Objects;

use \PDO;

class Day
{
    /** @var PDO database connection */
    private $conn;
    private $tableName = "days";
    private $associationTableName = "days_to_exercises";

    // object properties
    public $id;
    public $name;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read(): array
    {
        $query = "SELECT * FROM " . $this->tableName . " ORDER BY day_name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $daysArr = [];

        if ($stmt->rowCount() > 0) {

            $daysArr["records"] = [];

            // fetch() is a bit slower but but require less memory than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $dayItem = [
                    "id" => $row['day_id'],
                    "name" => $row['day_name'],
                    "description" => html_entity_decode($row['day_description'])
                ];

                array_push($daysArr["records"], $dayItem);
            }
        }

        return $daysArr;
    }

    public function readAssociations(): array
    {
        $query = "SELECT d_e.exercise_id, d_e.exercise_index, d_e.exercise_sets, d_e.exercise_reps, 
                    e.exercise_name, e.exercise_muscle, e.exercise_description
                  FROM " . $this->associationTableName . " d_e
                  LEFT JOIN exercises e ON d_e.exercise_id = e.exercise_id
                  WHERE d_e.day_id = ?
                  ORDER BY d_e.exercise_index ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $daysAssociationArr = [];

        if ($stmt->rowCount() > 0) {
            // fetch() is a bit slower but but require less memory than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($dayAssociation = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($daysAssociationArr, $dayAssociation);
            }
        }

        return $daysAssociationArr;
    }
}