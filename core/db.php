<?php 
require_once ('config.php');

function connect($config) 
{
	try {
		$conn = new PDO('mysql:host=' . $config['host'] . '; dbname=' . $config['dbname'],
						$config['user_name'],
						$config['password']);

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;

	} catch (PDOException $e) {
		return false;
	}
}

/*
* $conn = $conn
* $query = ex: "SELECT * FROM TableName WHERE id = :myid AND user = :user"
* $bindings = ex: array('myid' => 4, 'user' => 'tanvir')
**/

function query($conn, $query, $bindings = NULL)
{
	try {
		$stmt = $conn->prepare($query);
		$stmt->execute($bindings);
		$results = $stmt->fetchAll();

		return ($results)
				? $results
				: false;

	} catch (PDOException $e) {
		return false;
	}
}

function query_insert($conn, $query, $bindings = NULL)
{
	try {
		$stmt = $conn->prepare($query);
		$stmt->execute($bindings);
		$results = $stmt->rowCount();

		return ($results > 0)
				? $conn->lastInsertId()
				: false;

	} catch (PDOException $e) {
		return false;
	}
}

function query_update($conn, $query, $bindings = NULL)
{
	try {
		$stmt = $conn->prepare($query);
		$stmt->execute($bindings);
		$results = $stmt->rowCount();

		return ($results > 0)
				? true
				: false;

	} catch (PDOException $e) {
		return false;
	}
}

function query_delete($conn, $query, $bindings = NULL)
{
	try {
		$stmt = $conn->prepare($query);
		$stmt->execute($bindings);
		$results = $stmt->rowCount();

		return ($results > 0)
			? true
			: false;

	} catch (PDOException $e) {
		return false;
	}
}






/*
|------------------------------------------------------------------------------------------------
| 2. Ariful Islam
|------------------------------------------------------------------------------------------------
*/
//the following functions are added by Arif
//checks if any row id found and return the result @ Arif
function query_rowcount_result($conn, $query, $bindings = NULL)
{
	try {
		$stmt = $conn->prepare($query);
		$stmt->execute($bindings);
		$rowCount = $stmt->rowCount();
		$results = $stmt->fetchAll();
		if ($rowCount > 0) {
			return $results;
		} else {
			return false;
		}


	} catch (PDOException $e) {
		return false;
	}
}

//checks if any row is found
function check_single_row($conn, $query, $bindings = NULL)
{
	try {
		$stmt = $conn->prepare($query);
		$stmt->execute($bindings);
		$rowCount = $stmt->rowCount();
		if ($rowCount > 0) {
			return true;
		} else {
			return false;
		}


	} catch (PDOException $e) {
		return false;
	}
}
//checks how many rows are found
function count_total_row($conn, $query, $bindings = NULL)
{
	try {
		$stmt = $conn->prepare($query);
		$stmt->execute($bindings);
		$rowCount = $stmt->rowCount();

		return $rowCount;

	} catch (PDOException $e) {
		return false;
	}
}