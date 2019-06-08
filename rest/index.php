<?php
require '../vendor/autoload.php';
require_once 'PersistenceManager.class.php';
require_once 'Config.class.php';

Flight::register('pm', 'PersistenceManager', [Config::DB]);

//Flight::register('db', 'PDO', array('mysql:host=localhost:3306;dbname=expense_tracking_system','root',''));

Flight::route('/', function(){
    echo 'hello world!';
    //$cars = Flight::db()->query('SHOW tables', PDO::FETCH_ASSOC)->fetchAll();
    Flight::json($cars);
});

/*All for budget*/

Flight::route('GET /budget/@id', function($id){
    $budget = Flight::pm()->get_all_budgets($id);
    Flight::json($budget);
});

Flight::route('DELETE /delete_budget/@id', function($id){
  Flight::pm()->delete_budget($id);
  Flight::json(['message' => "Budget {$id} has been deleted successfully"]);
});


Flight::route('POST /budget', function(){
  $request = Flight::request();
  $budget = Flight::request()->data->budget;
  $id = Flight::request()->data->id;
  if ($id != ''){
    $input = array(
        "budget_id" => $id,
        "budget_name" => $request->data->budget_name,
        "budget_description" => $request->data->budget_description,
        "start_date" => $request->data->start_date,
        "end_date" => $request->data->end_date,
        "category_id" => $request->data->category_id,
        "amount" => $request->data->amount
      );
    Flight::pm()->edit_budget($input);
    Flight::json(['message' => "Budget has been successfully edited"]);
  }else{
    $input = array(
        "budget_name" => $request->data->budget_name,
        "budget_description" => $request->data->budget_description,
        "amount" => $request->data->amount,
        "start_date" => $request->data->start_date,
        "end_date" => $request->data->end_date,
        "created_by" => "IME USERA POKUPITI",
        "category_id" => $request->data->category_id,        
        "user_id" => 10
      );
    Flight::pm()->create_budget($input);
    Flight::json(['message' => "Budget has been successfully created"]);
  }
});

/*All for categories*/

Flight::route('GET /categories', function() {
    Flight::json(Flight::pm()->get_all_categories());
});

Flight::route('GET /occurances', function() {
    Flight::json(Flight::pm()->get_all_occurances());
});


Flight::route('POST /cars', function(){
    $request = Flight::request()->data->getData();
    $insert = "INSERT INTO cars (name, power, year, fuel, ccm) VALUES(:name, :power, :year, :fuel, :ccm)";
    $stmt= Flight::db()->prepare($insert);
    $stmt->execute($request);
});

Flight::start();
?>
