<?php

namespace Objects;

use \PDO;
use \Exception;
use \stdClass;
use \SplSubject;
use \SplObserver;

class Plan implements SplSubject
{
    /** @var PDO database connection */
    private $conn;
    private $tableName = "plans";
    private $associationTableName = "plans_to_days";
    private $userTableName = "users";

    // object properties
    public $id;
    public $name;
    public $description;
    public $days;
    public $users;
    public $created;
    public $modified;

    protected $observers = [];

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function attach(SplObserver $observer): void
    {
        $observerKey = spl_object_hash($observer);
        $this->observers[$observerKey] = $observer;
    }

    /**
     * @param SplObserver $observer
     *
     * @return void
     */
    public function detach(SplObserver $observer): void
    {
        $observerKey = spl_object_hash($observer);
        unset($this->observers[$observerKey]);
    }

    /**
     * @return void
     */
    public function notify(): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function createWithAssociations(): bool
    {
        try {
            $this->conn->beginTransaction();

            $this->create();

            $this->id = $this->conn->lastInsertId();

            //Intentionally made in a loop to keep it more readable
            //Expected amount of days < 10 so it should be ok
            //If >10 it's better to implement PDO's child with multiPrepare method
            //https://www.daniweb.com/programming/web-development/code/495371/insert-multiple-records-with-pdo-prepared-statement
            foreach ($this->days as $day) {
                $this->createAssociation($day);
            }

            return $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollBack();

            return false;
        }
    }

    public function read(): array
    {
        $query = "SELECT * FROM " . $this->tableName . " ORDER BY plan_created DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $plansArr = [];

        if ($stmt->rowCount() > 0) {

            $plansArr["records"] = [];

            // fetch() is a bit slower but but require less memory than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $planItem = [
                    "id" => $row['plan_id'],
                    "name" => $row['plan_name'],
                    "description" => html_entity_decode($row['plan_description'])
                ];

                array_push($plansArr["records"], $planItem);
            }
        }

        return $plansArr;
    }

    public function readOne(): Plan
    {
        $query = "SELECT * FROM " . $this->tableName . " WHERE plan_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {

            $this->name = $row['plan_name'];
            $this->description = $row['plan_description'];
            $this->days = $this->readAssociations();
            $this->users = $this->readUsers();
        }

        return $this;
    }

    public function upgrade(): bool
    {
        $query = "UPDATE " . $this->tableName . "
            SET
                plan_name = :name,
                plan_description = :description
            WHERE
                plan_id = :id";

        $stmt = $this->conn->prepare($query);

        // bind sanitized values
        $stmt->bindParam(':name', htmlspecialchars(strip_tags($this->name)));
        $stmt->bindParam(':description', htmlspecialchars(strip_tags($this->description)));
        $stmt->bindParam(':id', htmlspecialchars(strip_tags($this->id)));

        $stmt->execute();

        $result = $stmt->rowCount() === 1;

        if($result){
            $this->notify();
        }

        return $result;
    }

    public function delete(): bool
    {
        $query = "DELETE FROM " . $this->tableName . " WHERE plan_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, htmlspecialchars(strip_tags($this->id)));

        return $stmt->execute();
    }

    private function create(): bool
    {
        $query = "INSERT INTO " . $this->tableName . "
                SET plan_name = :name, plan_description = :description, plan_created = :created";

        $stmt = $this->conn->prepare($query);

        // bind sanitized values
        $stmt->bindParam(":name", htmlspecialchars(strip_tags($this->name)));
        $stmt->bindParam(":description", htmlspecialchars(strip_tags($this->description)));
        $stmt->bindParam(":created", htmlspecialchars(strip_tags($this->created)));

        return $stmt->execute();
    }

    private function createAssociation(stdClass $day): bool
    {
        $query = "INSERT INTO " . $this->associationTableName . "
                    SET plan_id = :plan_id, day_id = :day_id, day_index = :day_index";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":plan_id", $this->id);
        $stmt->bindParam(":day_id", $day->id);
        $stmt->bindParam(":day_index", $day->index);

        return $stmt->execute();
    }

    private function readAssociations(): array
    {
        $query = "SELECT p_d.day_id, p_d.day_index, d.day_name, d.day_description 
                  FROM " . $this->associationTableName . " p_d
                  LEFT JOIN days d ON p_d.day_id = d.day_id
                  WHERE p_d.plan_id = ?
                  ORDER BY p_d.day_index ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $plansAssociationArr = [];

        if ($stmt->rowCount() > 0) {
            // fetch() is a bit slower but but require less memory than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $planAssociation = [
                    'day_id' => $row['day_id'],
                    'day_index' => $row['day_index'],
                    'day_name' => $row['day_name'],
                    'day_description' => $row['day_description'],
                ];

                array_push($plansAssociationArr, $planAssociation);
            }
        }

        return $plansAssociationArr;
    }

    public function readUsers(): array
    {
        $query = "SELECT * FROM " . $this->userTableName . " 
                  WHERE plan_id = ?
                  ORDER BY user_id ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $userArr = [];

        if ($stmt->rowCount() > 0) {
            // fetch() is a bit slower but but require less memory than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $user = [
                    'user_id' => $row['user_id'],
                ];

                array_push($userArr, $user);
            }
        }

        return $userArr;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "days" => $this->days,
            "users" => $this->users,
        ];
    }
}