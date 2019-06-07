<?php

class PersistenceManager {
    private $pdo;

    public function __construct($params) {
        $this->pdo = new PDO('mysql:host='.$params['host'].';dbname='.$params['scheme'], $params['username'], $params['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }


    public function delete_budget($id){
        $query = "DELETE FROM budget WHERE budget_id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);
    }

    public function check_reservation($input) {
        $query = "UPDATE Reservations
                  SET city_id = :city_id, address = :address, for_date = :for_date, from_hour = :from_hour, to_hour = :to_hour, sidenote = :sidenote, is_accepted = :is_accepted
                  WHERE id = :reservation_id";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }

    /*provjeriti kako se radi */
    public function edit_budget($input, $budget_id){
    	$query = "UPDATE budget 
				SET budget_name = :budget_name, budget_description = :budget_description, 
					amount = :amount, start_date = :start_date, 
					category_id = :category_id, budget_edited = :budget_edited
				WHERE budget_id = ?";
		$statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }
    


    public function get_all_categories(){
        $query = "SELECT * FROM categories";
        return $this->pdo->query($query)->fetchAll();
    }
}

?>