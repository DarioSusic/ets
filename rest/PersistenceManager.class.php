<?php

class PersistenceManager {
    private $pdo;

    public function __construct($params) {
        $this->pdo = new PDO('mysql:host='.$params['host'].';dbname='.$params['scheme'], $params['username'], $params['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    
    /* BUDGET */
    public function delete_budget($id){
        $query = "DELETE FROM budget WHERE budget_id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);
    }

    public function get_all_budgets($user_id){
    	$query = "SELECT * FROM budget WHERE user_id = ?";
    	$statement = $this->pdo->prepare($query);
        $statement->execute([$user_id]);
        return $statement->fetch();
    }

    public function edit_budget($input){
    	$query = "UPDATE budget 
				SET budget_name = :budget_name, 
					budget_description = :budget_description, 
					amount = :amount, 
					start_date = :start_date, 
					end_date = :end_date,
					category_id = :category_id, 
					budget_edited = NOW()
				WHERE budget_id = :budget_id";
		$statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }

    public function create_budget($input){
    	$query = "INSERT INTO budget (budget_name, 
                                        budget_description, 
                                        amount, 
                                        start_date, 
                                        end_date, 
                                        created_by, 
                                        created_date, 
                                        category_id, 
                                        user_id)
    				VALUES (:budget_name, 
                            :budget_description, 
                            :amount, 
                            :start_date, 
                            :end_date, 
                            :created_by, 
                            NOW(), 
                            :category_id, 
                            :user_id)";
		$statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }


    /* TRANSACTIONS */
        /* EXPENSES */
    public function edit_expense($input){
        $query = "UPDATE expenses 
                SET amount = :amount, 
                    expense_date = :expense_date, 
                    description = :description, 
                    category_id = :category_id, 
                    expense_time_edit = NOW()
                WHERE expense_id = :expense_id";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }

    public function create_expense($input){
        $query = "INSERT INTO expenses (amount, expense_date, expense_time_creation, description, category_id)
                    VALUES (:amount, :expense_date, NOW(), :description, :category_id)";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }

    public function delete_expense($id){
        $query = "DELETE FROM expenses WHERE expense_id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);
    }
    
        /* EXPENSES */
    public function edit_income($input){
        $query = "UPDATE income 
                SET amount = :amount, 
                    income_date = :income_date, 
                    description = :description, 
                    category_id = :category_id, 
                    income_time_edit = NOW()
                WHERE income_id = :income_id";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }

    public function create_income($input){
        $query = "INSERT INTO income (amount, income_date, income_time_creation, description, category_id)
                    VALUES (:amount, :income_date, NOW(), :description, :category_id)";
        $statement = $this->pdo->prepare($query);
        $statement->execute($input);
    }

    public function delete_income($id){
        $query = "DELETE FROM income WHERE income_id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);
    }


    
    /* OCCURANCES */
    public function get_all_occurances(){
    	$query = "SELECT * FROM recurring_type";
        return $this->pdo->query($query)->fetchAll();
    }

    /* CATEGORIES */
    public function get_all_categories(){
        $query = "SELECT * FROM categories";
        return $this->pdo->query($query)->fetchAll();
    }
}

?>