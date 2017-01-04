<?php

	/* @Package : dataBasic 
	*  The package defines all the common operations of database like
	*  SELECT, INSERT, UPDATE, DELETE. It inherites data_manager.class for
	*  basic db operations.
	*  @Date : 09/10/2008
	*/

	class dataBasic
	{
	
		var $QueryTool; // to operate extended properties
		var $table_name;
		var $join_type;
		var $primary;
		var $order;
		var $stripSlash;
		# constructor of class
		function dataBasic()
		{
			$this->QueryTool = new dataBasic_DataManager(DB_NAME);
			# default order
			$this->order = ' ASC ';
			# default join type
			$this->join_type = ' AND ';
		}

		# Method to set the table_name
		function set_table($table_name)
		{
			if (empty($table_name))
			{
				return false;
			}
			$this->table_name = $table_name;
		}
		function get_table()
		{
			return $this->table_name;
		}
		# Method to set the primary
		function set_primary($primary_key)
		{
			$this->primary = $primary_key;
		}
		
		# Method to set the join type (AND/OR)
		function set_join_type($join_type)
		{
			$this->join_type = $join_type;
		}
		function set_order($order)
		{
			$this->order = $order;
		}
		
		function set_strip($status='Y')
		{
			$this->stripSlash = $status;
		}
		
		/** Method to insert record
		**  Before using this function it is recommended to call the set_table method
		**
		**  Example - Insert the record with values Arun, Active
		**  $data = Array ('field_name' => 'value', 'field_name' => 'value');
		**  $obj_bacis->set_table('xx');
		**  $obj_bacis->set_join_type(' AND '); /OR
		**  $obj_basic->update_data($data, $condition);
		**/
		function insert_data ($data)
		{
		 
			if(empty($this->table_name))
			{
				return false;
			}
			 
			$this->QueryTool->setTable($this->table_name);
			$inserted_id = $this->QueryTool->save ($data);
			if($inserted_id == 0)
			{
				return 0;
			}
			else
			{
				return $inserted_id;
			}
		}
		
		/** Method to update record
		**  Before using this function it is recommended to call set_table, set_join_type
		**  method
		**  @Param - $data : Array, $condition: Array
		**
		**  Example - Update the record where member_id is 1 with name=arun and status=active
		**  $data = Array ('name' => 'Arun', 'status' => 'Active');
		**  $condition = Array ('member_id' => 1);
		**  $obj_bacis->set_table('xx');
		**  $obj_bacis->set_join_type(' AND '); /OR
		**  $obj_basic->update_data($data, $condition);
		**/
		function update_data ($data, $condition)
		{
			if(empty($this->table_name))
			{
				return false;
			}
			if(empty($this->join_type))
			{
				$this->join_type = ' AND ';
			}
			$this->QueryTool->setTable($this->table_name);
			$this->QueryTool->setJoinType($this->join_type);
			$status = $this->QueryTool->save($data, $condition);
			if($status)
			{
				return true;
				
			}
			else
			{
				return false;
			}
		}
		
		/** delete the record from the table
		**  $condition - 'member_id=1' (Example)
		**/
		function delete_record($condition)
		{
			if(empty($this->table_name))
			{
				return false;
			}
			
			$this->QueryTool->setTable($this->table_name);
			$status = $this->QueryTool->remove($condition);
			if($status)
			{
				return true;
				
			}
			else
			{
				return false;
			}
		}
		
		/**
		** Method to get the data from only one table
		** @param: field_array - list of fields and their values to make the where clause,
		** $fileds - list of fields which need to be fetch, $start, $limit.
		** before using this function we need to call set_table()
		** set_primary()
		** @Return type - null/array
		**/

		function get_data($field_array, $fields='*', $start=null, $limit=null)
		{
			$where = "1=1 ";
			if(!empty($field_array))
			{
				foreach($field_array as $key=>$value)
				{
					if(!is_null($value))
						$where .= $this->join_type." ".trim($key)."='".trim(mysql_escape_string($value))."'";
				}
			}
			$this->QueryTool->setWhere($where);
			$this->QueryTool->setTable($this->table_name);
			$this->QueryTool->setPrimary($this->primary);
			$this->QueryTool->setOrder($this->order); #
			if(!is_null($start) && !is_null($limit))
			{
				$limit = $start.", ".$limit;
				$this->QueryTool->setLimit($limit);
			}
			
			$data = $this->QueryTool->get($fields);
			if(empty($data))
			{
				return null;
			}
			else
			{
				return $data;
			}
		}

		/**
		** Method to get the count of records from the table
		** @param: field_array - list of fields and their values to make the where clause,
		** before using this function we need to call set_table(), set_join_type() - AND/OR,
		** set_primary()
		** $field_array means condition array
		** @Return type - null/array
		**/

		function get_count($field_array)
		{
			
			
			$where = "1=1 ";
			if(!empty($field_array))
			{
				foreach($field_array as $key=>$value)
				{
					$where .= $this->join_type." ".trim($key)."='".trim(mysql_escape_string($value))."'";
				}
			
			}
					
			$sql = " SELECT COUNT(".$this->primary.") as record_count FROM ".$this->table_name." WHERE ".$where;
			$data = $this->QueryTool->getQueryData($sql);
			if(empty($data))
			{
				return null;
			}
			else
			{
				return $data[0]['record_count'];
			}
		}
		/**
		*  @Description : method to execute any query.
		*  @Parameter   : query as string, queryType as string
		*  @returnType  : true/false
		*/

		function run_query($query, $query_type)
		{
			
			return $this->QueryTool->runQuery($query, $query_type);
		}

		/**
		*  @Description : method to execute any query and get data.
		*  @Parameter   : query as string
		*  @returnType  : null/data
		*/
		function get_query_data($query)
		{
			
			return $this->QueryTool->getQueryData($query);
		}
		
		# chech the size of image
		function check_size($image_name, $image_size, $uploadType)
		{
				
				//2 mb
				$max_file_size = 1024*1024*2;
								
				if($image_name != "")
				{

					$size = $image_size;

					if($size > $max_file_size)
					{
						return false;
					} 
					else
					{
						return true;
					}
				}
				else
				{
					return true;
				}
		}
		function get_country($language)
		{
			$where = "default_lang='".$language."'";
			$this->QueryTool->setWhere($where);
			$this->QueryTool->setTable('site_language');
			$this->QueryTool->setPrimary('country_code');
			$this->QueryTool->setOrder('ASC');

			
			$data = $this->QueryTool->getAll();
			return $data;
		}

		/**
		$param = Array (
			'changed_by' =>
			'changer_id' =>
			'info_changed_of' =>
			'info_changed_of_id'=>
			'event' =>
			'carried_by_ip' => 
			'log_date' => 
			);
			
		if (getenv('HTTP_X_FORWARDED_FOR')) {
				$ip_address = getenv('HTTP_X_FORWARDED_FOR');
			} else {
				$ip_address = getenv('REMOTE_ADDR');
			}
		**/
		function update_history($data ,$condition, $param)
		{
			/*if($_SESSION['LoginType'] == 'Partner' || $_SESSION['LoginType'] == 'Admin')
			{
				return false;
			}*/
			$mult_current_data = $this->get_data($condition, '*');
			$current_data = $this->convert_to_one_dimensional($mult_current_data);
			if(is_null($current_data))
			{
				return;
			}
			$history = '';
			$history_id = '';
			$rollback_data = array();
			$table_name = $this->get_table();
			$i = 0;
			foreach($data as $key=>$value)
			{
				$key = strtolower($key);
				if(array_key_exists($key, $current_data))
				{
					if(strtolower($data[$key]) != strtolower($current_data[$key]))
					{
						$history .= tr('common', $key)." - <br />";
						
						$old_value = $current_data[$key];
						$new_value = $data[$key];
						$stat_arr = Array( '1','0','y','n');
						if($key == 'role_id')
						{
							$old_value = tr('common',$old_value."_role");
							$new_value = tr('common',$new_value."_role");
						}
						else if($key == 'order_line_status')
						{
							$old_value = $this->get_order_processed_translation($old_value);
							$new_value = $this->get_order_processed_translation($new_value);
						}
						else if($key == 'payment_method')
						{
							$old_value = $this->get_payment_method_translation($old_value);
							$new_value = $this->get_payment_method_translation($new_value);
						}
						else if(in_array($old_value , $stat_arr))
						{
							$old_value = tr('common',$old_value);
							$new_value = tr('common',$new_value);
						}
						else if($key == 'due_date_preference')
						{
							$old_value = $current_data[$key];
							$new_value = $data[$key];
						}

						if($key == 'customer_type_id')
						{
							$old_value = tr('customer_type', $current_data[$key]);
							$new_value = tr('customer_type', $data[$key]);
						}

						$history .= "\t\t ".tr('common','old_value')." ".$old_value."<br />";
						$history .= "\t\t ".tr('common','new_value')." ".$new_value."<br />";
						$history .= "<br />";

						#for rollback 
						$rollback_data[$i]['field_name'] = $key;
						$rollback_data[$i]['old_value'] = $current_data[$key];
						$rollback_data[$i]['new_value'] = $data[$key];
						$i++;						
					}
				}
			}

			if(!empty($history))
			{
				$param['information'] = $history;
				$partner_id = partner_preference();
			  $param['partner_id'] = $partner_id;
			  
				$this->set_table('history');
			  $this->set_primary('history_id');
				$history_id = $this->insert_data($param);
			}
			//$rollback_data[]['history_id'] = $history_id;
			//$rollback_data[]['table_name'] = $table_name;
			//print_r($rollback_data);
			$param = array();
			for($j=0;$j<count($rollback_data);$j++)
			{
				$param = array('history_id'=>$history_id,'table_name'=> $table_name,'field_name'=>$rollback_data[$j]['field_name'],'old_value'=>$rollback_data[$j]['old_value'],'new_value'=>$rollback_data[$j]['new_value']);
				$this->set_table('history_fields');
				$this->set_primary('log_id');
				$log_id = $this->insert_data($param);
			}
			
			
		}
		
		
		function convert_to_one_dimensional($ary)
		{
			# Strip the time part from date fields.
						

			foreach ($ary as $key => $value)
			{
				$sql = "SELECT ".$key." FROM ".$this->QueryTool->tableName." LIMIT 0, 1";
				$result = mysql_query($sql);
				$data_type = mysql_field_type($result, 0);
				if(is_array($ary[$key]))
				{		
				   $this->convert_to_one_dimensional($ary[$key]);
				}
				else
				{
				   if($data_type == 'datetime')
				   {
					 $value = split(" ", $value);
					 $value =  $value[0];
				   }
				   $this->one_dim[$key] = $value;
				}
			}
			return $this->one_dim;
		}
		
		/**
		*
		**/
		function return_history_array($info_changed_of,$info_changed_of_id,$event)
		{
			if (getenv('HTTP_X_FORWARDED_FOR')) 
			{
				$ip_address = getenv('HTTP_X_FORWARDED_FOR');
			} 
			else 
			{
				$ip_address = getenv('REMOTE_ADDR');
			}
			$changer_id = '';
			$type = $_SESSION['LoginType']; 
			#Admin session
			if($type == 'Admin')
			{
				$changer_id =$_SESSION['Member']['AdminId'] ;
			}
			else if($type == 'Partner') ## partner session 
			{ 
			   $changer_id = $_SESSION['Member']['PartnerContactId'];
			} ## customer session 
			else if($type == 'Customer')
			{
				$changer_id = $_SESSION['Member']['contact_id'];
			}
			$param = Array(
				'changed_by' => $type,
				'changer_id' => $changer_id,
				'info_changed_of' => $info_changed_of,
				'info_changed_of_id'=> $info_changed_of_id,
				'event' => $event,
				'carried_by_ip' => $ip_address,
				'log_date' =>  date('Y-m-d H:i:s'),
			);
			return $param;
		}
		
		function add_history($history, $param)
		{	
			$param['information'] = $history;
		  $param['partner_id'] = partner_preference();
		 
			$this->set_table('history');
		  $this->set_primary('history_id');
			$history_id = $this->insert_data($param);
		}
		
		function get_order_processed_translation($order_status)
		{
			$order_status_arr = Array(
			'1' => tr('common','not_processed'),
			'2' => tr('common','processing'),
			'3' => tr('common','declined'),
			'4' => tr('common','awaiting'),
			'5' => tr('common','notacceptedbycustomer'),
			'6' => tr('common','processed'),
			'7' => tr('common','failed'),
			'8' => tr('common','cancelled'),
			'9' => tr('common','expired'),
			);
			foreach($order_status_arr as $key => $value)
			{
				if($key == $order_status)
				{
					return $value;
				}
			}
		}

		function get_payment_method_translation($payment_method)
		{
			$partner_id = partner_preference();
			$sql = "select product_number from products where partner_id='$partner_id' AND product_type='Invoice_method' AND product_id='".$payment_method."'";
			$payment_arr = $this->get_query_data($sql);
			if(empty($payment_arr))
				return;

			$payment_method_arr = Array(
			'inv_postal' => tr('invoice_method','postal_invoice'),
			'inv_email' => tr('invoice_method','email_invoice'),
			'inv_credit' => tr('invoice_method','credit_card'),
			);

			foreach($payment_method_arr as $key => $value)
			{
				if($key == $payment_arr[0]['product_number'])
				{
					return $value;
				}
			}
		}

		/**
		** Date : 11/25/2009
		** Fetch mailing details on partner bases
		**/
		function get_partner_mailing_details($mail_requried)
		{
			$partner_id = $mail_requried['partner_id'];
			$mail = $mail_requried['mail'];
			$language = $mail_requried['language'];

			/*
			$sql = "SELECT files_name, mail_from, mail_type, ".$language."_mail_subject AS subject
					FROM cart_mails cm 
					LEFT JOIN mails m ON cm.mail = m.mail
					WHERE cm.partner_id='$partner_id' AND cm.mail='$mail' ";
			*/

			# @ 04/07/2011 by dipendra, Replace the user of cart_mail by partner_mails as cart_mails table is deleted.
			$sql = "SELECT files_name, mail_from, mail_type, ".$language."_mail_subject AS subject
					FROM partner_mails pm 
					LEFT JOIN mails m ON pm.mail = m.mail
					WHERE pm.partner_id='$partner_id' AND pm.mail='$mail' ";
			#####   dipendra   #####

			$mail_data = $this->get_query_data($sql);
			return $mail_data;
		}
		

		function get_set_values($table, $field) 
		{
			if (empty($table) || empty($field)) return false;
			$column = mysql_fetch_array(mysql_query(" SHOW COLUMNS FROM ".$table." LIKE '".$field."' "));

			if (!preg_match('/^enum|set/', $column['Type'])) return false;
			$vals = preg_replace('/(?:^enum|set)|\(|\)/', '', $column['Type']);
			$values = split(',', $vals);
			if (!sizeof($values)) return false;
			for ($i = 0; $i < sizeof($values); $i++) {
				$values[$i] = preg_replace('/^\'|\'$/', '', $values[$i]);
			}
			return $values;
		}


		/**
		* Date : 11/14/2009
		* find interest rate according to partner setting 
		* Formula:  (Outstanding invoice balance * Interest rate * days overdue) / Days per year
		**/
		function get_interest_rate($due_date , $partner_id, $out_standing_balance)
		{
 			$interest_rate = '0';
 
			$sql = "SELECT * 
							FROM partner_interest_rate 
							WHERE (end_date >=  DATE('$due_date') AND ( end_date != '0000-00-00' OR end_date != '' OR end_date IS NOT NULL ) )  OR  
							( ( end_date = '0000-00-00' OR end_date = '' OR end_date IS NULL ) AND CURRENT_DATE() >=  DATE('$due_date') ) 
							AND partner_id='$partner_id' AND start_date IS NOT NULL ";
			$interest_data = $this->get_query_data($sql);
			$int_cnt = count($interest_data);
			$reminder_date = date('Y-m-d');
			$year = date('Y');

			$num_day_per_year = '365';
			if(($year%4) == 0)
				$num_day_per_year = '366';
			
			for($i=0;$i<$int_cnt;$i++)
			{
				if(strtotime($due_date)>strtotime($interest_data[$i]['start_date']))
				{
					$interest_data[$i]['start_date'] = date('Y-m-d', strtotime($due_date));
				}
				if(strtotime($reminder_date) <=strtotime($interest_data[$i]['end_date']) || $interest_data[$i]['end_date'] == '0000-00-00')
				{
					$interest_data[$i]['end_date'] = $reminder_date;
				}
				list($dy,$dm, $dd) = split('-', $interest_data[$i]['start_date']);
				list($d1y,$d1m, $d1d) = split('-', $interest_data[$i]['end_date']);

				$start_date=gregoriantojd($dm, $dd, $dy);
				$end_date=gregoriantojd($d1m, $d1d, $d1y);
				$num_days = $end_date - $start_date;
			 
				$interest_rate += ($out_standing_balance*($interest_data[$i]['interest_rate']/100)*$num_days)/$num_day_per_year;
			}
			if($interest_rate < 0) $interest_rate = 0;
			$interest_rate = number_format(round($interest_rate, 2), 2,'.','');
			return $interest_rate;
		}
		 
		function convert_into_standard_format($input_number)
		{
 			$format = $this->get_number_format();
			if(empty($format))
				$format = ',== ';
 			list($d, $t) = split('==', $format);
			if(empty($d))
				$d = ',';
			if(empty($t))
				$t = ' ';
			
			$input_number = str_ireplace($t, "", $input_number);
			$input_number = str_ireplace($d, ".", $input_number);
			$input_number = str_ireplace(" ", "", $input_number);
			if(!is_numeric($input_number))
			{
				return '0';
			}
			return number_format(round($input_number,2), 2,'.','');
		}

		function get_number_format()
		{
			$language =  isset($_SESSION['language'])?$_SESSION['language']:'nb';
			$last_lang = isset($_SESSION['num_last_lang'])?$_SESSION['num_last_lang']:'nb';
			
			if(isset($_SESSION['language_number_format']) && $last_lang == $language)
				return $_SESSION['language_number_format'];
			

			$language_data = $this->get_country($language);
			
			$sql = "SELECT number_format FROM  site_validation_rule WHERE country_code='".$language_data[0]['country_code']."'";
			$formats = $this->get_query_data($sql);

			if(!empty($formats))
			{
				$_SESSION['num_last_lang'] = $language;
				$_SESSION['language_number_format'] = $formats[0]['number_format'];
				return $_SESSION['language_number_format'];
			}
			else
				return ',== ';
		}

		function convert_into_local_format($number)
		{
			 $num_format = $this->get_number_format();
			 @list($decimal_separator, $thousand_separator) = split('==', $num_format);
			  if(empty($decimal_separator))
					$decimal_separator=',';

			  if(empty($thousand_separator))
				  $thousand_separator=' ';
			  else if($thousand_separator == '.')# Changed by pankaj 
				  $thousand_separator = '.';
				  
			$number = number_format(round($number, 2), 2,$decimal_separator,$thousand_separator);
			return $number;
		}

		function begin_transaction()
		{
			/**
			** Set autocommit to 0 and start transaction.
			**/
			$sql = "SET autocommit=0";
			$this->run_query($sql, 'update');
			$sql = " BEGIN ";
			$this->run_query($sql, 'update');
		}

		function verify_inv_tran($journal_id)
		{
			$sql = "SELECT ROUND(SUM(Dr)-SUM(Cr), 2) as bal FROM journal_lines WHERE journal_id='".$journal_id."'";
			$jrn_status = $this->get_query_data($sql);
			return $jrn_status[0]['bal'];
		}

		function rollback_transaction()
		{
			$sql = " ROLLBACK ";
			$this->run_query($sql, 'update');
		}
		function commit_transaction()
		{
			$sql = " COMMIT ";
			$this->run_query($sql, 'update');
		}
		function off_autocommit()
		{
			$sql = "SET autocommit=1";
			$this->run_query($sql, 'update');
		}
		
		/**
		** Date :  
		** Vat enable or not 
		**/
		function is_vat_enabled($ein, $partner_id)
		{
			$sql = "SELECT vat_enabled
					FROM partner_setting 
					WHERE partner_id='$partner_id' ";
			$vat_enabled = $this->get_query_data($sql);
			if(empty($vat_enabled))
			 return $ein;
			
			if($vat_enabled[0]['vat_enabled'] == 'y')
			  return $ein." "."MVA";
			  
			return $ein;			 
		}
		
		function set_placeholder_into_email($input_arr = Array())
		{
			global $smarty, $obj_user;		
			$result_arr = Array();
			$partner_id = partner_preference();
			if(isset($input_arr['partner_id']) && !empty($input_arr['partner_id']))
				$partner_id = $input_arr['partner_id'];
			
			$sql = "SELECT partner_name, partner_phone, partner_address1, partner_address2, partner_country, partner_fax,
							partner_zip, partner_city, partner_website, partner_email, norid_hs_email as domain_form_email, partner_directory, partner_signature			
							FROM partner p 
							LEFT JOIN partner_setting ps ON p.partner_id = ps.partner_id
							WHERE p.partner_id='$partner_id' ";
			$partner = $this->get_query_data($sql);
			if(!empty($partner))
			{
				$partner = $partner[0];
				$block = $partner['partner_address1'];
				if(!empty($partner['partner_address2']))
					$block .= "<br />".$partner['partner_address2'];
				if(!empty($partner['partner_zip']))
					$block .= "<br />".$partner['partner_zip'].", ";
				if(!empty($partner['partner_city']))
					$block .= $partner['partner_city'];
				
				$partner['partner_address'] = $block;
				$customer_url = BASE_URL.strtolower($partner['partner_directory']).'/customer/';
				$result_arr['customer_url'] = $customer_url;	
				$partner['partner_signature'] = nl2br($partner['partner_signature']);
				$result_arr = array_merge($result_arr, $partner);
			}
			
			if(isset($input_arr['customer_id']) && !empty($input_arr['customer_id']))
			{
				$sql = "SELECT company_name as customer_name
								FROM customer 
								WHERE customer_id='".$input_arr['customer_id']."'";
				$customer = $this->get_query_data($sql);
				if(!empty($customer))
				{
					$result_arr = array_merge($result_arr, $customer[0]);
				}
			}		
			
			if(isset($input_arr['contact_id']) && !empty($input_arr['contact_id']))
			{
				$sql = "SELECT  TRIM(CONCAT(first_name, ' ', last_name)) AS contact_name
								FROM contact_person 
								WHERE contact_id='".$input_arr['contact_id']."'";
				$contact_person = $this->get_query_data($sql);
				if(!empty($contact_person))
				{
					$result_arr = array_merge($result_arr, $contact_person[0]);
				}
			}
			$language =  isset($_SESSION['language'])?$_SESSION['language']:'nb';
			$sql = "SELECT *
						FROM tos 
						WHERE 1";
			$tos = $this->get_query_data($sql);
			for($i=0;$i<count($tos);$i++)
			{
				 $tos_url = BASE_URL.'tos.php?htl='.base64_encode($tos[$i]['url']).'&lang='.$language;
				 $result_arr[$tos[$i]['name']] = $tos_url;
			}
			
			if(is_array($input_arr))
				$result_arr = array_merge($result_arr, $input_arr);
	
			# @ 17/01/2011 by mohit
			if(isset($_SESSION['LoginType']) && strtolower($_SESSION['LoginType']) == 'admin')
			{
				$c_id = isset($_SESSION['Member']['AdminId'])?$_SESSION['Member']['AdminId']:'';
				if(!empty($c_id))
				{
					$condition = " id='".mysql_escape_string($c_id)."' ";
					$partner_contact = $obj_user->get_partner_contact_persons_list($condition, " signature");
					if(!empty($partner_contact))
						$smarty->assign('contact_signature', nl2br($partner_contact[0]['signature']));
				}
			}
			else if(isset($_SESSION['LoginType']) && strtolower($_SESSION['LoginType']) == 'partner')
			{
				$c_id = isset($_SESSION['Member']['PartnerContactId'])?$_SESSION['Member']['PartnerContactId']:'';
				if(!empty($c_id))
				{
					$condition = " id='".mysql_escape_string($c_id)."' ";
					$partner_contact = $obj_user->get_partner_contact_persons_list($condition, " signature");
					if(!empty($partner_contact))
						$smarty->assign('contact_signature', nl2br($partner_contact[0]['signature']));
				}
			}
			
			foreach($result_arr as $key => $value)
			{
				$smarty->assign($key, $value);
			}
		}
		
	function get_place_holder()
	{
		$place_holder = Array(
			'partner_name' => tr('common', 'partner_name'),
			'partner_address' => tr('common', 'partner_address'),
			'partner_phone' => tr('common', 'partner_phone_number'),			
			'partner_fax' => tr('common', 'partner_fax'),
			'partner_website' => tr('common', 'partner_website'),
			'partner_email' => tr('common', 'partner_email'),
			'domain_form_email' => tr('common', 'domain_form_email'),
			'customer_name' => tr('common', 'customer_name'),
			'order_number' => tr('common', 'order_number'),
			'domain_name' => tr('common', 'domain_name'),
			'customer_url' => tr('common', 'customer_url'),
			'partner_signature' => tr('common', 'email_partner_signature'),
			'contact_signature' => tr('common', 'email_contact_signature'),
		);
		
		$sql = "SELECT name
						FROM tos 
						WHERE 1";
		$tos = $this->get_query_data($sql);
		for($i=0;$i<count($tos);$i++)
		{
			$place_holder[$tos[$i]['name']] = tr('tos', $tos[$i]['name']);
		}
		return $place_holder;		
	}
	
	/*
	 * function to get all allowed upload extention from db
	 * 24 Aug 2011
	 */
	 function get_allowed_extention($page_name = '')
	 {
	   
		$where = '';
		if($page_name != '')	 
			$where .= ' WHERE page_name = "'.$page_name.'"';
		
		$sql = "SELECT distinct(allowed_extention) FROM manage_file_extentions ".$where;
		$tos = $this->get_query_data($sql);
		
		$page_extention = array();
		for($i=0;$i<count($tos);$i++)
		{
			$page_extention[] = '.'.$tos[$i]['allowed_extention'];
		}
		
		return $page_extention;		
	 	 
	 }
	 
	 /*
	 * function to get letter reminder fee
	 * 29 Aug 2011
	 */
	function get_letter_fee($invoice_id)
	{
		global $obj_basic;
		
		$sql = "SELECT reminder_fee FROM customer_reminders cr LEFT JOIN customer_reminder_lines crl ON cr.reminder_id=
		crl.reminder_id WHERE crl.invoice_id='$invoice_id' AND cr.level_id='3' ORDER BY cr.rem_due_date DESC LIMIT 0,1";
		$data = $obj_basic->get_query_data($sql);
		if(is_null($data)) return 0;
		else return $data[0]['reminder_fee'];
	}
	
	/*
	 * function to get collection reminder fee
	 * 29 Aug 2011
	 */
	function get_collection_fee($invoice_id)
	{
		global $obj_basic;
		
		$sql = "SELECT reminder_fee FROM customer_reminders cr LEFT JOIN customer_reminder_lines crl ON cr.reminder_id=
		crl.reminder_id WHERE crl.invoice_id='$invoice_id' AND cr.level_id='5' ORDER BY cr.rem_due_date DESC LIMIT 0,1";
		$data = $obj_basic->get_query_data($sql);
		if(is_null($data)) return 0;
		else return $data[0]['reminder_fee'];
	}

	
		
	} # End of class

	# extends the methods of data manager (database access)
	class dataBasic_DataManager extends DataManager
	{
		// set primary table
		var $tableName  = "";
		var $primaryCol = "";
	}