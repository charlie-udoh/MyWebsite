<?php
/**
 * Created by PhpStorm.
 * User: charlie
 * Date: 28/01/2017
 * Time: 08:43
 */

/**
 * class for mysql database
 */
class MysqlDatabase {
	private $dbHandler;
	private $result;
	private $statement_handler;

	public function __construct() {
		// Create a new PDO class instance
		try {
			$this->dbHandler = new PDO(PDO_DSN, DATABASE_USER, DATABASE_PASS, array(PDO::ATTR_PERSISTENT => DB_PERSISTENCY));
			$this->dbHandler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
// Close the database handler and trigger an error
			$this->Close();
			trigger_error($e->getMessage(), E_USER_ERROR);
		}
	}

	// Clear the PDO class instance
	public function Close() {
		$this->dbHandler = NULL;
	}

	// Wrapper method for PDOStatement::execute()
	public function Execute($sqlQuery, $params = NULL) {
		try {
			$this->statement_handler = $this->dbHandler->prepare($sqlQuery);
			$this->statement_handler->execute($params);
		} catch (PDOException $e) {
			$this->Close();
			trigger_error($e->getMessage(), E_USER_ERROR);
		}

		return true;
	}

	// Wrapper method for PDOStatement::fetchAll()
	public function GetAll($sqlQuery, $params = NULL, $fetchStyle = PDO::FETCH_ASSOC) {
		$result = NULL;
		try {
			$this->statement_handler = $this->dbHandler->prepare($sqlQuery);
			$this->statement_handler->execute($params);
			$this->result = $this->statement_handler->fetchAll($fetchStyle);
		} catch (PDOException $e) {
			$this->Close();
			trigger_error($e->getMessage(), E_USER_ERROR);
		}

		return $this->result;
	}

	// Wrapper method for PDOStatement::fetch()
	public function GetRow($sqlQuery, $params = NULL, $fetchStyle = PDO::FETCH_ASSOC) {
		$result = NULL;
		try {
			$this->statement_handler = $this->dbHandler->prepare($sqlQuery);
			$this->statement_handler->execute($params);
			$this->result = $this->statement_handler->fetch($fetchStyle);
		} catch (PDOException $e) {
			$this->Close();
			trigger_error($e->getMessage(), E_USER_ERROR);
		}

		return $this->result;
	}

	// Return the first column value from a row
	public function GetOne($sqlQuery, $params = NULL) {
		$result = NULL;
		try {
			$this->statement_handler = $this->dbHandler->prepare($sqlQuery);
			$this->statement_handler->execute($params);
			$result = $this->statement_handler->fetch(PDO::FETCH_NUM);
			/* Save the first value of the result set (first column of the first row)
			to $result */
			$this->result = $result[0];
		} catch (PDOException $e) {
			$this->Close();
			trigger_error($e->getMessage(), E_USER_ERROR);
		}

		return $this->result;
	}

	public function InsertId() {
		return $this->dbHandler->lastInsertId();
	}
}

?>