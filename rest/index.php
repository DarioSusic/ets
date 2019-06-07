<?php
require '../vendor/autoload.php';
require_once 'PersistenceManager.class.php';
require_once 'Config.class.php';

Flight::register('pm', 'PersistenceManager', [Config::DB]);

//Flight::register('db', 'PDO', array('mysql:host=localhost:3306;dbname=expense_tracking_system','root',''));

Flight::route('/', function(){
    echo 'hello world!';
    $cars = Flight::db()->query('SHOW tables', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($cars);
});

/*All for budget*/

Flight::route('GET /budget', function(){
    $cars = Flight::db()->query('SELECT * FROM budget', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($cars);
});

Flight::route('DELETE /delete_budget/@id', function($id){
  Flight::pm()->delete_budget($id);
  Flight::json(['message' => "Budget {$id} has been deleted successfully"]);
});



/*
Flight::route('POST /budget', function($user_id){
  $user = Flight::request()->data->volunteer;
  $query = "INSERT INTO budget (budget_name, amount, start_date, created_by, created_date, category_id, user_id) 
            VALUES (:budget_name, :amount, :start_date, :created_by, NOW(), :category_id, :user_id");
});*/



/*All for categories*/

Flight::route('GET /categories', function() {
    Flight::json(Flight::pm()->get_all_categories());
});


Flight::route('POST /cars', function(){
    $request = Flight::request()->data->getData();
    $insert = "INSERT INTO cars (name, power, year, fuel, ccm) VALUES(:name, :power, :year, :fuel, :ccm)";
    $stmt= Flight::db()->prepare($insert);
    $stmt->execute($request);
});

Flight::start();
?>
