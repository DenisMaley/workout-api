<?php

namespace Objects;

use \PDO;
use \SplObserver;
use \SplSubject;

class User implements SplObserver
{
    /** @var PDO database connection */
    private $conn;
    private $tableName = "users";

    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $plan_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function update(SplSubject $publisher): void
    {
        $to = $this->email;
        $subject = "Workout plan was changed";
        $txt = "Please, check your new plan";
        $headers = "From: notification@virtuagym.com";

        mail($to,$subject,$txt,$headers);
    }

    public function read(): array
    {
        $query = "SELECT * FROM " . $this->tableName . " ORDER BY user_lastname DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $usersArr = [];

        if ($stmt->rowCount() > 0) {

            $usersArr["records"] = [];

            // fetch() is a bit slower but but require less memory than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $userItem = [
                    'id' => $row['user_id'],
                    'lastname' => $row['user_lastname'],
                    'firstname' => $row['user_firstname'],
                    'email' => $row['user_email'],
                    'plan_id' => $row['plan_id'],
                ];

                array_push($usersArr["records"], $userItem);
            }
        }

        return $usersArr;
    }

    public function readOne(): User
    {
        $query = "SELECT * FROM " . $this->tableName . " WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {
            $this->id = $row['user_id'];
            $this->lastname = $row['user_lastname'];
            $this->firstname = $row['user_firstname'];
            $this->email = $row['user_email'];
            $this->plan_id = $row['plan_id'];
        }

        return $this;
    }

    public function upgrade(): bool
    {
        $query = "UPDATE " . $this->tableName . "
            SET
                user_lastname = :lastname,
                user_firstname = :firstname,
                user_email = :email,
                plan_id = :plan_id
            WHERE
                user_id = :id";

        $stmt = $this->conn->prepare($query);

        // bind sanitized values
        $stmt->bindParam(':lastname', htmlspecialchars(strip_tags($this->lastname)));
        $stmt->bindParam(':firstname', htmlspecialchars(strip_tags($this->firstname)));
        $stmt->bindParam(':email', htmlspecialchars(strip_tags($this->email)));
        $stmt->bindParam(':plan_id', htmlspecialchars(strip_tags($this->plan_id)));
        $stmt->bindParam(':id', htmlspecialchars(strip_tags($this->id)));

        $stmt->execute();

        return $stmt->rowCount() === 1;
    }

    public function delete(): bool
    {
        $query = "DELETE FROM " . $this->tableName . " WHERE user_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, htmlspecialchars(strip_tags($this->id)));

        return $stmt->execute();
    }

    public function create(): bool
    {
        $query = "INSERT INTO " . $this->tableName . "
                 SET user_lastname = :lastname, user_firstname = :firstname, user_email = :email, plan_id = :plan_id";

        $stmt = $this->conn->prepare($query);

        // bind sanitized values
        $stmt->bindParam(':lastname', htmlspecialchars(strip_tags($this->lastname)));
        $stmt->bindParam(':firstname', htmlspecialchars(strip_tags($this->firstname)));
        $stmt->bindParam(':email', htmlspecialchars(strip_tags($this->email)));
        $stmt->bindParam(':plan_id', htmlspecialchars(strip_tags($this->plan_id)));

        return $stmt->execute();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'email' => $this->email,
            'plan_id' => $this->plan_id,
        ];
    }
}