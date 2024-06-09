<?php

class Booking
{
    private $dbh;
    private $bookingsTableName = 'bookings';

    public function __construct($database, $host, $databaseUsername, $databaseUserPassword)
    {
        try {
            $this->dbh = new PDO(sprintf('mysql:host=%s;dbname=%s', $host, $database), $databaseUsername, $databaseUserPassword);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function index()
    {
        $statement = $this->dbh->query('SELECT * FROM ' . $this->bookingsTableName);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(DateTimeImmutable $bookingDate, $timeSlot, $included, $firstName, $lastName, $contactNo, $email, $deposit_amount, $balance_amount, $amount, $status)
    {
        $statement = $this->dbh->prepare(
            'INSERT INTO ' . $this->bookingsTableName . ' (booking_date, time_slot, included, firstName, lastName, contactNo, email, deposit_amount, balance_amount, amount, status) VALUES (:bookingDate, :timeSlot, :included, :firstName, :lastName, :contactNo, :email, :deposit_amount, :balance_amount, :amount, :status)'
        );

        if (false === $statement) {
            throw new Exception('Invalid prepare statement');
        }

        if (false === $statement->execute([
                ':bookingDate' => $bookingDate->format('Y-m-d H:i:s'),
                ':timeSlot' => $timeSlot,
                ':included' => $included,
                ':firstName' => $firstName,
                ':lastName' => $lastName,
                ':contactNo' => $contactNo,
                ':email' => $email,
                ':deposit_amount' => $deposit_amount,
                ':balance_amount' => $balance_amount,
                ':amount' => $amount,
                'status' => $status,

            ])) {
            throw new Exception(implode(' ', $statement->errorInfo()));
        }
    }

    public function delete($id)
    {
        $statement = $this->dbh->prepare(
            'DELETE from ' . $this->bookingsTableName . ' WHERE id = :id'
        );
        if (false === $statement) {
            throw new Exception('Invalid prepare statement');
        }
        if (false === $statement->execute([':id' => $id])) {
            throw new Exception(implode(' ', $statement->errorInfo()));
        }
    }
}

?>

<?php
