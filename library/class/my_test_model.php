<?php

	/**
	* @ 
	* @Date -  28/09/2010
	**/
	
	class MyTestModel
	{
		var $QueryTool; // to operate extended properties
		 
		
		# constructor of class
		function MyTestModel()
		{
			$this->QueryTool = new MyTestDataBasic(DB_NAME);
		}
		
			 
		function getMyData()
		{
			$query = " SHOW TABLES; ";
			$this->QueryTool->get_query_data($query);

			return "getMyData";
		}
		
	}# End of class
	

	# extends the methods of data basic
	class MyTestDataBasic extends dataBasic
	{
		// set primary table
		var $tableName  = "";
		var $primaryCol = "";
	}