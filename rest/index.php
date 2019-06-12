<?php
require '../vendor/autoload.php';
require_once 'PersistenceManager.class.php';
require_once 'Config.class.php';

use \Firebase\JWT\JWT;

Flight::register('pm', 'PersistenceManager', [Config::DB]);

//Flight::register('db', 'PDO', array('mysql:host=localhost:3306;dbname=expense_tracking_system','root',''));

Flight::route('/', function(){
    echo 'hello world!';
    //$cars = Flight::db()->query('SHOW tables', PDO::FETCH_ASSOC)->fetchAll();
    //Flight::json($cars);
});

/* CRUD for User */
Flight::route('POST /create_user', function () {
    $request = Flight::request();
    $input = array(
        "name" => $request->data->name,
        "surname" => $request->data->surname,
        "email" => $request->data->email,
        "password" => $request->data->password,
    );
    Flight::pm()->create_user($input);
});

Flight::route('POST /login', function(){
    $request = Flight::request();
    $db_user = Flight::pm()->get_user_by_email($request->data->email);
    print_r($db_user['password']);
    if ($db_user){
        if ($db_user['password'] == $request->data->password){
            unset($db_user['password']);
            $token = ["user" => $db_user, "iat" => time(), "exp" => time() + 3600];
            $jwt = JWT::encode($token, Config::JWT_SECRET);
            $db_user['token'] = $jwt;
            Flight::json($db_user);
        }else{
            Flight::halt(400, Flight::json(['message' => 'Invalid password for email address '. $request->data->email]));
        }
    }else{
        Flight::halt(400, Flight::json(['message' => 'Invalid email address']));
    }
});

/*CRUD for budget*/

Flight::route('GET /budget', function(){
    $budget = Flight::pm()->get_budgets();
    Flight::json($budget);
});

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
        //"category_id" => $request->data->category_id,
        "category_id" => 48,        
        "user_id" => 10
      );
    Flight::pm()->create_budget($input);
    Flight::json(['message' => "Budget has been successfully created"]);
  }
});


/*CRUD for Expenses*/

Flight::route('POST /expense', function(){
  $request = Flight::request();
  $expense = Flight::request()->data->expense;
  $id = Flight::request()->data->expense_id;
  $user_id = 8;
  //print_r($request);
  if ($id != ''){
    $input = array(
      "expense_id" => $id,
      "amount" => $request->data->amount,
      "expense_date" => $request->data->expense_date,
      "description" => $request->data->description,
      "category_id" => $request->data->category_id
    );
    Flight::pm()->edit_expense($input);
    Flight::pm()->edit_transaction($id);
    Flight::json(['message' => "Transaction has been successfully edited"]);
  }else{
    $input = array(
      "amount" => $request->data->amount,
      "expense_date" => $request->data->expense_date,
      "description" => $request->data->description,
      "category_id" => $request->data->category_id
      );
    $dataArray = Flight::pm()->create_expense($input, $user_id);
    Flight::pm()->create_transaction($dataArray);
    Flight::json(['message' => "Transaction has been successfully created"]);
  }
});


Flight::route('DELETE /delete_expense/@id', function($id){
  Flight::pm()->delete_expense($id);
  Flight::pm()->delete_transaction($id);
  Flight::json(['message' => "Transaction id: {$id} has been deleted successfully"]);
});


/*CRUD for Income*/

Flight::route('POST /income', function(){
  $request = Flight::request();
  $income = Flight::request()->data->income;
  $id = Flight::request()->data->income_id;
  $user_id = 8;
  print_r($request);
  if ($id != ''){
    $input = array(
      "income_id" => $id,
      "amount" => $request->data->amount,
      "income_date" => $request->data->income_date,
      "description" => $request->data->description,
      "category_id" => $request->data->category_id
    );
    Flight::pm()->edit_income($input);
    Flight::pm()->edit_transaction($id);
    Flight::json(['message' => "Transaction has been successfully edited"]);
  }else{
    $input = array(
      "amount" => $request->data->amount,
      "income_date" => $request->data->income_date,
      "description" => $request->data->description,
      "category_id" => $request->data->category_id
      );
    $dataArray = Flight::pm()->create_income($input, $user_id);
    Flight::pm()->create_transaction($dataArray);
    Flight::json(['message' => "Transaction has been successfully created"]);
  }
});


Flight::route('DELETE /delete_income/@id', function($id){
  Flight::pm()->delete_income($id);
  Flight::pm()->delete_transaction($id);
  Flight::json(['message' => "Transaction id:{$id} has been deleted successfully"]);
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
