<?
$_GET['debug'] = 1;

# Class to manage connections.
class Database {
	
	#-------------------------#
	# Variables and Constants #
	#-------------------------#
	
	// Hold the database connection.
	private $Database;
	
	// Errors that may happen
	private $Errors;
	
	// The connection status: Alive, Died, Duration, NumOfQueries.
	private $ConnectionStatus;
	private $QueriesPerformed;
	
	// Return for $QueriesPerformed
	public function getNumOfQueries() { return $this->ConnectionStatus['NumOfQueries']; }
	public function getConnectionDuration() { return $this->ConnectionStatus['Duration']; }
	public function getQueries() { return $this->QueriesPerformed; }
	
	// Message Constants
	const ERROR_CANNOT_CONNECT_TO_DATABASE = '[Code: 1] Cannot connect to the database. (PV/NC has been informed)';
	const ERROR_SQL_SYNAX_INCORRECT = '[Code: 2] SQL synax error. (PV/NC has been informed)';
	const ERROR_INCORRECT_KEY_PARSE = '[Code: 3] Invalid KEY data. (PV/NC has been informed)';
	const ERROR_DATA_ENTRY_ISSUE = '[Code: 4] There was an error inserting new data. (PV/NC has been informed)';
	
	#----------------------#
	# Connection and Setup #
	#----------------------#
	
	// Connect to the database, requires a key login.
	public function __construct($Key) {
		
		// Parse key data
		$Key = unserialize(gzuncompress(base64_decode($Key)));	
		
		// Check key data
		if (empty($Key['db'])) {
			
			// Failed key parse
			$this->Errors = self::ERROR_INCORRECT_KEY_PARSE; 
			return false;
		}
		else
		{
			// Attempt to connect
			try {
			
				$port = '3306'; /* standard mysql port */
				if (!empty($Key['port']))
					$port = $Key['port'];
				
				// Successfull connection sets database variable to hold connection
				$this->Database = new PDO('mysql:host='. trim($Key['db']) .';port='.$port.';dbname='. trim($Key['table']) .'', trim($Key['user']), trim($Key['pass']));
				
				// Configuration for prepared statements
				$this->Database->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$this->Database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				// Set the time the connection became alive.	
				$this->ConnectionStatus['Alive'] = microtime(true);
				//print_r('host:'. trim($Key['db']) .';port='.$port.';dbname='. trim($Key['table']) .'user='. trim($Key['user']));
				return true;
				
			// Failed connection attempt
			} catch (PDOException $e) {
				//print_r('ERROR: host:'. trim($Key['db']) .';port='.$port.';dbname='. trim($Key['table']) .'user='. trim($Key['user']));
				
				// If debug is set, we can spit out the exact error to help developers track the issue
				if ($_GET['debug']) { 
				
					// Set error variable to the PDO Exception error
					$this->Errors = $e->getMessage();
					return false;
				
				} else { 
				
					// Set error to a generic error we can print to our users
					$this->Errors = self::ERROR_CANNOT_CONNECT_TO_DATABASE; 
					return false;
				}
				die;
			}
		}
	}
	
	// Deconstruction.
	public function __destruct() {
		$this->Disconnect();
	}
	
	// Disconnect from database.
	public function Disconnect() {
		
		// Set the time of when the connection died
		$this->ConnectionStatus['Died'] = microtime(true);
		
		// Set the duration it was alive in seconds.
		$this->ConnectionStatus['Duration'] = ($this->ConnectionStatus['Died'] - $this->ConnectionStatus['Alive']);
		
		// Sort just to make it look nice
		ksort($this->ConnectionStatus);
		
		// Close the database
		$this->Database = NULL;
	}
	
	// Returns the errors.
	public function getErrors() {
		return $this->Errors;	
	}
	
	#-----------------#
	# Main query call #
	#-----------------#
	
	// Method to fetch data via a raw query
	private function Query($sql, $type, $all = false) {
		
		// Add Debugging statuses.
		$this->ConnectionStatus['NumOfQueries'] = $this->ConnectionStatus['NumOfQueries'] + 1;
		$this->QueriesPerformed[microtime(true)][] = $sql;
		
		// Set utf names
		$Query = $this->Database->prepare("set names 'utf8'");
		$Query->execute();

		try {		
			// Run Query
			$Query = $this->Database->prepare($sql);
			$Query->execute();
			
			// Error Checking
			$Errors = $Query->errorInfo();
			if ($Errors[2])
			{
				// If we are debugging, print errors more easier to read.	
				if ($_GET['debug']) {
					$this->Errors = array($Errors[2], $sql);
					return false;
				} else {
					$this->Errors = self::ERROR_SQL_SYNAX_INCORRECT; 
					return false;
				}
			}
			else
			{
				
				// Return based on type
				if ($type == 'get') {
					
					// Get results
					if ($all)
						$Data = $Query->fetchAll(PDO::FETCH_ASSOC);
					else
						$Data = $Query->fetch(PDO::FETCH_ASSOC);
					
					// If successful, return the insertion id.
					return array(
						"rows" => $Query->rowCount(),
						"data" => $Data
					);
				}
				else if ($type == 'insert')
				{
					// return inserted id
					return array("ID" => $this->Database->lastInsertId());
				}
				else if ($type == 'raw')
				{
					return $Query->fetchAll(PDO::FETCH_ASSOC);
				}
				else if ($type == 'IU') // insert on dup key update 
				{
					return array("ID" => $this->Database->lastInsertId()); //$Query->fetchAll(PDO::FETCH_ASSOC);
				}
			}
		} 
		// If an error caught
		catch(PDOException $e)
		{
			// If debug is set, we can spit out the exact error to help developers track the issue
			if ($_GET['debug']) { 
			
				// Set error variable to the PDO Exception error
				echo $e->getMessage();
				
				die;
			
			} else { 
			
				// Set error to a generic error we can print to our users
				$this->Errors = self::ERROR_DATA_ENTRY_ISSUE; 
				return false;
			}
			
		}
	}
	
	#---------#
	# Methods #
	#---------#
	
	/*
		Method to fetch data, returns an array: [Rows]Numer of rows, [Data]The data.
		If there is an error, returns false.
		Default: "select * (where = NULL) order by AUTO asc limit(0,10) (fetch 1)"
		
		Example:
		GetData('table', 
				array('column1', 'column2', 'column3'), 
				true,
				array('column1' => 'data'),
				'AND'
				column,
				COLUMN,
				DESC,
				array(Start, Length);
				
		Limit can be set to false to ignore.
	*/
	public function Get($Table, $Fields = "*", $All = FALSE, $Where = NULL, $WhereOperator = NULL, $GroupBy = NULL, $Order = NULL, $Direction = NULL, $Limit = NULL, $NoFilter = false) {
				
		// We query by constructing an SQL query string.
		$sql = 'SELECT ';
		
		// Get the columns from the field array.
		if (is_array($Fields)) { $sql .= implode(', ', $Fields) .' '; } else { $sql .= $Fields .' '; } 
		
		// Append from table
		$sql .= 'FROM '. $Table .' ';
		
		// Append where statement (if one exists)
		if ($WhereOperator == NULL) { $WhereOperator = ' AND '; }
		if ($Where) { $sql .= 'WHERE '. implode(' '. $WhereOperator .' ', $Where) .' '; }
		
		// Append groupby statement (if one exists)
		if ($GroupBy) { $sql .= 'GROUP BY '. $GroupBy .' '; }
		
		// Append order (if one exists)
		if ($Order && !$NoFilter) { $sql .= 'ORDER BY '. $Order .' '. $Direction .' '; }

		// Append limit (if one exists)
		if ($Limit) { $sql .= 'LIMIT '. $Limit[0] .','. $Limit[1] .''; }

		// Query
		return $this->Query($sql, 'get', $All);
		
	}
					//GetData($Table, $Fields, $All, $Where = NULL, $Order = "AUTO", $Direction = "ASC", $Limit = NULL, $NoFilter = false)
	// Support for old PDO
	public function GetData($Table, $Fields, $All, $Where = NULL, $Order = "AUTO", $Direction = "ASC", $Limit = NULL, $NoFilter = false, $debug = false) {
		if (is_array($Fields))
			$FieldsDisplay = implode("|", $Fields);
		else
			$FieldsDisplay = $Fields;
		
		$Start = microtime();
		$this->Areas[] = 'Table: '. $Table .' (Fields: '. $FieldsDisplay .') (All? '.  $All .') (Where: '. $Where .') (Limit: '. $Limit[1] .')';
		
		// Create fields into comma string if an array
		if (is_array($Fields))
			$field_values = implode(",", $Fields);
		else
			$field_values = $Fields;
			
		// Set WHERE clause
		if (is_array($Where))
			$where_conditions = implode(" AND ", $Where);
		else
			$where_conditions = $Where;
		
		// If WHERE conditions set, create string condition	
		if (isset($where_conditions))
			$where_conditions = "WHERE ". $where_conditions;
			
		// Set Limit
		if (isset($Limit))
			$limit_conditions = "LIMIT ". implode(",", $Limit);
			
		//Show($limit_conditions);
		
		$OrderBy = "";
		if(!$NoFilter)
			$OrderBy = "ORDER BY ". $Order ." ". $Direction;
		
		if($debug)
			print_r("SELECT ". $field_values ." FROM ". $Table ." ". $where_conditions ." ". $OrderBy ." ". $limit_conditions ."");
		
		return $this->Query("SELECT ". $field_values ." FROM ". $Table ." ". $where_conditions ." ". $OrderBy ." ". $limit_conditions ."", 'get', $All);
	}
	
	// Allows free sql input (restricted to hardcoded input only)
	public function SQL($sql) {
		return $this->Query($sql, 'raw');
	}
	
	// Inert on duplicate key update statements
	public function IUSQL($sql) {
		return $this->Query($sql, 'IU');
	}
	
	public function RawQuery($sql) {
		return $this->Query($sql, 'raw');
	}
	
	// Method to insert data into a table.
	public function Insert($Table, $Data) {
		
		// We query by constructing an SQL query string.
		$sql = 'INSERT INTO ';
		
		// Append table name.
		$sql .= $Table .' ';
		
		// implode keys of $array...
		$sql .= "(`".implode("`, `", array_keys($Data))."`) ";
		
		// implode values of $array...
		$sql .= "VALUES ('".implode("', '", $Data)."') ";
		
		// Query
		return $this->Query($sql, 'insert');
	}
	
	// Method to update the data into a table.
	public function Update($Table, $Data, $Where) {
		
		// We query by constructing an SQL query string.
		$sql = 'UPDATE ';
		
		// Append table name.
		$sql .= $Table .' ';
   
		// Append data.
		$sql .= 'SET '. implode(",", $Data) .' ';
		
		// Append where clause.
		$sql .= 'WHERE '. implode(" AND ", $Where);
		
		// Query
		return $this->Query($sql, 'update');
	}

	// Method to remove data from a table.
	public function Remove($Table, $Where) {
		
		// We query by constructing an SQL query string.
		$sql = 'DELETE FROM ';
		
		// Append table name.
		$sql .= $Table .' ';
		
		// Append where clause.
		$sql .= 'WHERE '. implode(" AND ", $Where);
		
		// Query
		return $this->Query($sql, 'remove');
	}
	
	#------------------------#
	# Predefined query calls #
	#------------------------#
	
	// Get a specific value based on a mysql function
	public function GetValue($Table, $Function, $Additional = NULL, $Where = NULL) {
		
		// We query by constructing an SQL query string.
		$sql = 'SELECT ';
		
		// Case on the $Function to determine the data return
		switch($Function)
		{
			case 'count': $sql .= 'count(AUTO) as COUNT '; break;
			case 'sum': $sql .= 'sum('. $Additional .') as SUM '; break;
			case 'avg': $sql .= 'avg('. $Additional .') as AVG '; break;	
			case 'min': $sql .= 'min('. $Additional .') as MIN '; break;	
			case 'max': $sql .= 'max('. $Additional .') as MAX '; break;
		}
		
		// Append table name.
		$sql .= 'FROM '. $Table .' ';
		
		// Append where clause.
		if ($Where) { $sql .= 'WHERE '. implode(" AND ", $Where); }
		
		// Query
		return $this->Query($sql, 'raw');
		
	}
	
	// Gets the position of a table
	public function GetPosition($Table, $Field, $Value) {
		
		// We query by constructing an SQL query string.
		$sql = 'SELECT ';
		
		// Apend selection.
		$sql .= 'count(AUTO) as Position ';
		
		// Append table name.
		$sql .= 'FROM '. $Table .' ';
		
		// Append where clause.
		$sql .= 'WHERE '. $Field .' >= '. $Value .' ';
		
		// Query
		return $this->Query($sql, 'raw');
			
	}
	
	// Gets the position of a table
	public function GetColumns($Table) {		
		$Query = $this->Database->prepare("DESCRIBE ".$Table);
		$Query->execute();
		return $Query->fetchAll(PDO::FETCH_COLUMN);
		
	}
	
	
}	
?>