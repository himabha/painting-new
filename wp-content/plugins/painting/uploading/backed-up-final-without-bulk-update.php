<!DOCTYPE html>
<html lang="en">
<head>
  <title>Daily INVP tool</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	<script>
	$(document).ready(function(){
		$("#myModal").modal();
	})
	</script>
  </head>
<body>
<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
if(!isset($_POST['submit']))
{
?>

<div class="container">
  <!-- Trigger the modal with a button -->

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
	  <?php if(isset($_GET['tab']))
	  {
		  $params = "?tab=".$_GET['tab'];
		  if(isset($_GET['skip_col']))
		  {
			  $params .= "&skip_col=".$_GET['skip_col'];
		  }
	  ?>
        <div class="modal-body">
          <p><?php echo ($_GET['tab'] == 3) ? "Are you sure you want to generate output csv files?" : "Are you sure you want to run updates to db?" ?></p>
        </div>
        <div class="modal-footer">
		<form action="<?php echo $_SERVER['PHP_SELF'].$params;?>" method="POST">
          <button type="submit" class="btn btn-default" name="submit">Yes</button>
		  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
		  </form>
        </div>
	  <?php
		}
		else
		{
		?>
		<div class="modal-body">
			<p>Url is not correct or tab value is not mentioned.</p>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
        </div>
		<?php
		}
		?>
      </div>

    </div>
  </div>

</div>
<?php } ?>

</body>
</html>

<?php

require_once dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'config.php';

class Invptool
{
	private $conn;
	private $logtype = "";

    public function __construct() {

		//AH: App starts here....

		if(isset($_GET['tab']) && $_GET['tab'] == 3)
		{
			$this->logtype = "tab3_";
		}
		else
		{
			$this->logtype = "tab4_";
		}
		date_default_timezone_set('America/New_York');  //AH:
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_OUTPUT_FOLDER_DAILYINVP."/logs"))
		{
			mkdir($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_OUTPUT_FOLDER_DAILYINVP."/logs", 0777);
		}
		$this->logfp = fopen($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_OUTPUT_FOLDER_DAILYINVP."/logs/".$this->logtype."_log_".date("mdY_His").".log", 'w');
		if(isset($this->logfp))
		{
			fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." -----------------------App Started -----------------------\n\r");

			fwrite($this->logfp, "Connecting to App Environment=" . APPENV_DAILYINVP . ": DB Host=" . DB_HOST . ";dbname=" . DB_DATABASE);    //AH:
		}


		try {
			$this->conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_DATABASE, DB_ROOT, DB_PASSWORD);
			// set the PDO error mode to exception
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
		catch(PDOException $e)
		{
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Connection failed: " . $e->getMessage()."\n\r".date("Y-m-d H:i:s")." ");
			}
		}
    }

	public function checkColumnName($column_name, $config_header_arr){
		$column_index = array_search(strtolower($column_name), array_map("trim", array_map("strtolower", $config_header_arr)));
		if($column_index === false)
		{
			throw new Exception("Column name ".$column_name." not found.");
		}
		return $column_index;
	}

	public function checkDBColumnName($column_name, $config_header_arr){
		$column_index = array_search(strtolower($column_name), array_map("trim", array_map("strtolower", $config_header_arr)));
		if($column_index === false)
		{
			throw new Exception("Column name ".$column_name." not found.");
		}
		return strtolower($column_name);
	}


	public function validateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

    public function run()
    {
	try{
        $row = 1;
		if(isset($this->logfp))
		{
			fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Reading Config file.\n\r");
		}

		//echo "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'dailyinv_config' ORDER BY ORDINAL_POSITION";
		$config_rows1 = array();
		$config_rows = array();
		$stmt = $this->conn->prepare("select * from dailyinv_config");
		if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$config_rows = array_keys($result[0]);
			if(!empty($result))
			{
				foreach($result as $config_data)
				{
					$config_rows1[] = $config_data;
				}
			}
        }

        if (!empty($config_rows1)) {
        //if (($handle = fopen("inv_config.csv", "r")) !== FALSE) {
            /*$config_rows = array();
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Collecting all files from config.\n\r");
			}
            while (($config_data = fgetcsv($handle, 1000, ",")) !== FALSE) {		//AH: assume 1000 lines in config
                if ($config_data[0] != null){
                    $config_rows[] = $config_data;					//AH: all config rows saved in config_rows[]
                }
				else
				{
					continue;
				}
            }
            fclose($handle);*/
			/* if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find Active col index\n\r");
			} */


		//AH:  start - read imp. columns from config	- reading first row of inv_config.csv
            $exceptions = array();


			//Get Active
			try
			{
				$active_col_index = $this->checkDBColumnName("active", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			/* if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find sku col index\n\r");
			} */

            //Get Sku Column
			try
			{
				$sku_col_index = $this->checkDBColumnName("sku_col", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			/* if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find sku upc col index\n\r");
			} */

			//Get Sku upc
			try
			{
				$sku_upc_col_index = $this->checkDBColumnName("sku_upc", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			/* if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find stock col index\n\r");
			} */

			//Get stock column
			try
			{
				$stock_col_index = $this->checkDBColumnName("stock_col", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			//Get stock column values
			try
			{
				$stock_col_value_index = $this->checkDBColumnName("stock_col_values", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}


			//Get status column values
			try
			{
				$status_col_value_index = $this->checkDBColumnName("status_col_values", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			//Get eta column values
			try
			{
				$eta_col_value_index = $this->checkDBColumnName("ETA_value", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			/* if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find status col index\n\r");
			} */

			//Get status column
			try
			{
				$status_col_index = $this->checkDBColumnName("status_col", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			/* if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find eta col index\n\r");
			} */

			//Get eta column
			try
			{
				$eta_col_index = $this->checkDBColumnName("ETA", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			/* if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find supid col index\n\r");
			} */

			//Get supid column
			try
			{
				$supid_col_index = $this->checkDBColumnName("supid", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			/* if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find prefix col index\n\r");
			} */

			//Get prefix to add column
			try
			{
				$prefix_col_index = $this->checkDBColumnName("prefixtoadd", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			/* if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find supplier filename col index\n\r");
			} */

			//Get supplier filename
			try
			{
				$supplier_filename_col_index = $this->checkDBColumnName("supplier_filename", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			//Get Conn Method
			try
			{
				$conn_method_col_index = $this->checkDBColumnName("conn_method", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}


			//Get Vendor
			try
			{
				$vendor_col_index = $this->checkDBColumnName("vendor", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			//Get n_rows
			try
			{
				$n_rows_index = $this->checkDBColumnName("n_rows", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}
			//Get filename pattern
			try
			{
				$file_pattern_index = $this->checkDBColumnName("filename_pattern", $config_rows);
			}
			catch(Exception $e)
			{
				if (isset($this->logfp)) {
					fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
				}
				$exceptions[] = $e->getMessage();
			}

			if(!empty($exceptions))
			{
				exit;
			}

		//AH:  end - read imp. columns from config	- reading first row of inv_config.csv

            $i = 0;
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Process all config file rows.\n\r");
			}
		//check if latest_toupld folder does not exist
		if(!file_exists($_SERVER['DOCUMENT_ROOT'] . "/" . ROOT_DIRECTORY_DAILYINVP . "/" . VENDOR_OUTPUT_FOLDER_DAILYINVP . "/latest_toupld")) {
			if (isset($this->logfp)) {
				fwrite($this->logfp, "\n\r". date("Y-m-d H:i:s") ." Creating latest_toupld folder. ");
			}
			if (mkdir($_SERVER['DOCUMENT_ROOT'] . "/" . ROOT_DIRECTORY_DAILYINVP . "/" . VENDOR_OUTPUT_FOLDER_DAILYINVP . "/latest_toupld", 0777)) {}
		}
		else {
			foreach(new DirectoryIterator($_SERVER['DOCUMENT_ROOT'] . "/" . ROOT_DIRECTORY_DAILYINVP . "/" . VENDOR_OUTPUT_FOLDER_DAILYINVP . "/latest_toupld") as $fileInfo) {
				if ($fileInfo->isDot()) continue;
				$filewithinfo = pathinfo($fileInfo->getFileName());
				if (isset($filewithinfo['extension'])) {
					unlink($_SERVER['DOCUMENT_ROOT'] . "/" . ROOT_DIRECTORY_DAILYINVP . "/" . VENDOR_OUTPUT_FOLDER_DAILYINVP . "/latest_toupld/".$filewithinfo['basename']);
				}
			}
		}
		//AH: start ------------- now loop in inv_config.csv to read all vendors configured
            foreach ($config_rows1 as $row) {
				$row = array_change_key_case($row, CASE_LOWER);
				if(isset($this->logfp))
				{
					fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Processing config file row No. ".($i+1).".\n\r");
				}
				if($row[$sku_col_index] == null)
				{
					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." SKU column is found blank for ".$row[$supid_col_index]."\n\r");
					}
				}


                if (($row[$sku_col_index]!= null && ($row[$stock_col_index] !=null || $row[$status_col_index] !=null)) && strtolower($row[$active_col_index]) == 'y' ) {
					if (isset($this->logfp)) {
						fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . " Reading file for vendor " . $row[$vendor_col_index] . " with email id " . $row[$conn_method_col_index]."\n\r");
					}

			// reading current vendor row and saving the names of the columns for each vendor
                    $sku_col = $row[$sku_col_index];

					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Found sku column value ". $sku_col ."\n\r");
					}

                    $sku_upc_value = trim($row[$sku_upc_col_index]);

					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Found sku upc column value ". $sku_upc_value ."\n\r");
					}

                    $stock_col = $row[$stock_col_index];

					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Found stock column value ". $stock_col ."\n\r");
					}

                    $status_col = $row[$status_col_index];

					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Found status column value ". $status_col ."\n\r");
					}

                    $eta_col = trim($row[$eta_col_index]);

					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Found eta column value ". $eta_col ."\n\r");
					}

                    $supplier_filename = $row[$supplier_filename_col_index];

					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Found supplier filename column value ". $supplier_filename ."\n\r");
					}
                    //$file = new SplFileInfo($supplier_filename);
                    //$ext = $file->getExtension();

					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Find file extension\n\r");
					}
                  /*  $file = pathinfo($supplier_filename);
					$ext=$file['extension'];
                    $exp_filename = explode(".", $row[$supplier_filename_col_index]);
					$filename=$exp_filename[0];
					//Checking for inventory file for vendor
					$supplier_filename = $row[$prefix_col_index] . "___".$filename ."." . $ext;*/

          if($row[$n_rows_index] == 1)
          {
            $patterns = explode("|", $row[$file_pattern_index]);
            if(count($patterns) > 0)
            {
                foreach($patterns as $key => $pattern)
                {
                  if($key == 0)
                  {
                    $supplier_rows = array();
                  }
                }

            }

          }
          else {
              $supplier_rows = array();
          }
          $vendor_file_data = array();
					//AH: check if current vendor file exists and then open it
					if(file_exists($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_SRC_FOLDER_DAILYINVP."/".$row[$prefix_col_index]))
					{
          $filecount = 0;
					foreach (new DirectoryIterator($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_SRC_FOLDER_DAILYINVP."/".$row[$prefix_col_index]) as $fileInfo) {


						if($fileInfo->isDot()) continue;

						$filewithinfo = pathinfo($fileInfo->getFileName());
						if(isset($filewithinfo['extension']))
						{
							$supplier_filename = $filewithinfo['basename'];
					$filecount++;
					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Opening file: ".$supplier_filename."\n\r");
					}
					$supplierfileinfo = pathinfo($supplier_filename);
					$handle1 = @fopen("vend_src_invfiles/".$row[$prefix_col_index]."/".$supplier_filename, "r");
					if ($supplierfileinfo['extension'] == "csv" && $handle1 !== FALSE) {
                        //$supplier_rows = array();

			//AH: save all vendor file rows in supplier_rows[]
                        while (($supplier_data = fgetcsv($handle1, 1000, ",")) !== FALSE) {		//AH: 1000 does not control # of rows to read (vendor may have many K rows). Ignore
							if ($supplier_data[0] != null)  {
                                $supplier_rows[] = $supplier_data;
                            }
							else
							{
								continue;
							}
                        }

                        fclose($handle1);
						if(!empty($supplier_rows)){

						if(isset($this->logfp))
						{
							fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Reading all rows from file: ".$supplier_filename."\n\r");
						}

                        $sku_index = null;
                        $stock_index = null;

						/* if(isset($this->logfp))
						{
							fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Get sku value index\n\r");
						} */


			//AH: Now read column index for current vendor for sku, stock_col,  etc.

                        if($sku_col != '' && !is_numeric($sku_col)){
                            //Get sku column in supplier file
							try
							{
								$sku_index = $this->checkColumnName($sku_col, $supplier_rows[0]);
							}
							catch(Exception $e)
							{
								if (isset($this->logfp)) {
									fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
								}
								$exceptions[] = $e->getMessage();
							}
                        }
						else
						{
							$sku_index = $sku_col;
						}

						/* if(isset($this->logfp))
						{
							fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Get stock value index\n\r");
						} */

                        if($stock_col != '' && !is_numeric($stock_col)){
                            //Get stock column in supplier file
							try
							{
								$stock_index = $this->checkColumnName($stock_col, $supplier_rows[0]);
							}
							catch(Exception $e)
							{
								if (isset($this->logfp)) {
									fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
								}
								$exceptions[] = $e->getMessage();
							}
                        }
						else
						{
							$stock_index = $stock_col;
						}

						/* if(isset($this->logfp))
						{
							fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Get status value index\n\r");
						} */

						if($status_col != '' && !is_numeric($status_col)){
                            //Get sku column in supplier file
							try
							{
								$status_index = $this->checkColumnName($status_col, $supplier_rows[0]);		//AH: to get status e.g. discontinued etc.
							}
							catch(Exception $e)
							{
								if (isset($this->logfp)) {
									fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
								}
								$exceptions[] = $e->getMessage();
							}
                        }
						else
						{
							$status_index = $status_col;
						}

						/* if(isset($this->logfp))
						{
							fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Get eta value index\n\r");
						} */

						if($eta_col != '' && !is_numeric($eta_col)){

							//Get eta column in supplier file
							try
							{
								$eta_index = $this->checkColumnName($eta_col, $supplier_rows[0]);
							}
							catch(Exception $e)
							{
								if (isset($this->logfp)) {
									fwrite($this->logfp, "\n\r" . date("Y-m-d H:i:s") . "\n\r Serious: ".$e->getMessage()."\n\r");
								}
								$exceptions[] = $e->getMessage();
							}
                        }
						else
						{
							$eta_index = $eta_col;
						}



						//AH: Now actual loop in the current vendor file and read actual values for each row using the indexes found above
                        $j = 0;
						$output = array();
                        		//AH: to store file rows data one vendor at a time then resets for next vendor
                        $vendor_db_data = array();		//AH: to store db data data one vendor ,,, ,,,
                        $olddb_rows = array();
						if(isset($this->logfp))
						{
							fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Now Process all supplier rows\n\r");
						}
						$stock_column_values = explode("|", trim($row[$stock_col_value_index]));
						$status_column_values = explode("|", trim($row[$status_col_value_index]));
						foreach ($supplier_rows as $index => $s_row) {
                            if ($j > 0 && ($s_row[$sku_index]!= null && ($s_row[$stock_index] !=null || $s_row[$status_index] != null))) {

								$sku_value = trim($s_row[$sku_index]);
								if(in_array($row[$prefix_col_index],array('ow', 'nrsn')))
								{
									$sku_value = (int)$sku_value;
								}
                                $stock_value = trim($s_row[$stock_index]);
								$eta_value = "NA";
								$v_disc = "NA";

								if(isset($row[$status_col_index]) && !empty($row[$status_col_index]))
								{
									//$v_disc = 0;
								}

								/* if(isset($this->logfp))
								{
									fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Get stock value for supplier: ".$row[$supid_col_index]."\n\r");
								} */
								//Handling stock-col-values column. If new vendors use different words for in stock, out stock etc. add those to the appropriate case groups.

								if(trim($row[$stock_col_value_index]) != "")
								{
									if (in_array(strtolower(trim($s_row[$stock_index])), array_map("strtolower", $stock_column_values)))
									{
										switch (strtoupper(trim($s_row[$stock_index])))
										{
											case strtoupper("IN-STOCK"):
											case strtoupper("IN STOCK"):
											$stock_value = 10;
											break;
											case strtoupper("OUT-STOCK"):
											case strtoupper("OUT OF STOCK"):
											case strtoupper("LOW"):
											case strtoupper("0"):
											$stock_value = 0;
											break;
											case strtoupper("DISCONTINUED"):
											case strtoupper("DROPPED"):
											case strtoupper("OBSOLETE"):
											$v_disc = 1;
											break;
											default:
											if(isset($this->logfp))
											{
												if($row[$stoc])
												fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: Row ".($j+1)." having value ".$s_row[$stock_index]." with stock-col-values mismatched. \n\r");
											}
										}
									}
									else{
										if(isset($this->logfp))
										{
											//fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: Row ".($j+1)." having value ".$s_row[$stock_index]." with stock-col-values mismatched. \n\r");
										}
									}
								}

								// Handling status_col_values column
								if(trim($row[$status_col_value_index]) != ""){
									if (in_array(strtolower(trim($s_row[$status_index])), array_map("strtolower", $status_column_values)))
									{
										switch (strtoupper(trim($s_row[$status_index])))
										{
											case strtoupper("Y"):
											case strtoupper("YES"):
											case strtoupper("DISCONTINUED"):
											case strtoupper("DROPPED"):
											case strtoupper("OBSOLETE"):
											case strtoupper("IN STOCK- PLANNED TO DISCONTINUE"):
											case strtoupper("CL"):
											$v_disc = 1;
											break;
											case strtoupper("ACTIVE"):
											case strtoupper("N"):
											case strtoupper("NO"):
											$v_disc = 0;
											break;
											case strtoupper("OUT-OF-STOCK"):
											$v_disc = 0;
											$stock_value = 0;
											break;
											default:
											if(isset($this->logfp))
											{
												fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: Row ".($j+1)." having value ".$s_row[$status_index]." with status-col-values mismatched. \n\r");
											}
										}
									}
									else{
										if(isset($this->logfp))
										{
											//fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: Row ".($j+1)." having value ".$s_row[$status_index]." with status-col-values mismatched. \n\r");
										}
									}
								}

								// Handling ETA-value column
								$eta_column_value = trim($row[$eta_col_value_index]);
								switch ($eta_column_value)
								{
									case "mm/dd/yy":
									if($this->validateDate(trim($s_row[$eta_index]), 'm/d/y') || $this->validateDate(trim($s_row[$eta_index]), 'n/j/y') || $this->validateDate(trim($s_row[$eta_index]), 'm/j/y') || $this->validateDate(trim($s_row[$eta_index]), 'n/d/y'))
									{
										$date=date_create(trim($s_row[$eta_index]));
										$eta_value = date_format($date,"m/d/y");
									}
									else{
										if(isset($this->logfp))
										{
											fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: Row ".($j+1)." having value ".trim($s_row[$eta_index])." with Eta-Value mismatched. \n\r");
										}
									}
									break;
									case "days":
									if($this->validateDate(trim($s_row[$eta_index]), 'd') || $this->validateDate(trim($s_row[$eta_index]), 'j'))
									{
										$date=date('Y-m-d H:i:s');
										date_add($date,date_interval_create_from_date_string(trim($s_row[$eta_index])." days"));
										$eta_value = date_format($date,"m/d/y");
									}
									else{
										if(isset($this->logfp))
										{
											fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: Row ".($j+1)." having value ".$s_row[$eta_index]." with Eta-Value mismatched. \n\r");
										}
									}
									break;
									case "mm/dd/yyyy":
									if($this->validateDate(trim($s_row[$eta_index]), 'm/d/Y') || $this->validateDate(trim($s_row[$eta_index]), 'n/j/Y') || $this->validateDate(trim($s_row[$eta_index]), 'm/j/Y') || $this->validateDate(trim($s_row[$eta_index]), 'n/d/Y'))
									{
										$date=date_create(trim($s_row[$eta_index]));
										$eta_value = date_format($date,"m/d/y");
									}
									else{
										if(isset($this->logfp))
										{
											fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: Row ".($j+1)." having value ".trim($s_row[$eta_index])." with Eta-Value mismatched. \n\r");
										}
									}
									break;
									case "yyyymmdd":
									if($this->validateDate(trim($s_row[$eta_index]), 'Ymd') || $this->validateDate(trim($s_row[$eta_index]), 'Ymj') || $this->validateDate(trim($s_row[$eta_index]), 'Ynj') || $this->validateDate(trim($s_row[$eta_index]), 'Ynd'))
									{
										$date=date_create(trim($s_row[$eta_index]));
										$eta_value = date_format($date,"m/d/y");
									}
									else{
										if(isset($this->logfp))
										{
											fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: Row ".($j+1)." having value ".trim($s_row[$eta_index])." with Eta-Value mismatched. \n\r");
										}
									}
									break;
									default:
								}


								//AH: Add all column values found for current vendor row into the array
								$vendor_file_data[$j] = array(
								"sku"=> $sku_value,
								"stock"=> $stock_value,
								"supid" => $row[$supid_col_index],
								"sku_upc" => $sku_upc_value,
								"eta" => $eta_value);

								if(isset($v_disc))
								{
									$vendor_file_data[$j]["v_disc"] = $v_disc;
								}
								$output[$j] = $vendor_file_data[$j];


								if(isset($_GET['skip_col']) && $_GET['skip_col'] == 'Y')
								{
									unset($output[$j]['v_disc']);
									unset($output[$j]['eta']);
								}



								/* if(isset($this->logfp))
								{
									fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Prepare file output array.\n\r");
								} */

                            }
							$j++;
                        }
							/*if(!isset($output[1]['discontinued']))
							{
								$output = array_map(function($val){
									$val['discontinued'] = "NA";
									return $val;
								}, $output);
							}*/


							if(isset($this->logfp))
							{
								fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Prepare db array with all active products.\n\r");
							}

							$supplier_output_file = pathinfo($supplier_filename);
							$supplier_output_ext = $supplier_output_file['extension'];
							$supplier_output_filename =  $supplier_output_file['filename']."_dpupdt".".".$supplier_output_ext;

							if(isset($_GET['tab']) && $_GET['tab'] == 4)
							{
								//AH: Get all rows from DB for current vendor
								$stmt = $this->conn->prepare("select SKU, UPC, SupID, Stock, v_disc, ProductETA, Active, isDeleted from products where SupID=:SupID");
								$stmt->bindParam(':SupID', $row[$supid_col_index], PDO::PARAM_INT);
								//$stmt->bindValue(':Active', 1, PDO::PARAM_INT);
								$results = $stmt->execute();

								$stmt->setFetchMode(PDO::FETCH_ASSOC);
								$olddbkey = 0;
								while($db_row = $stmt->fetch()) {
									$vendor_db_data[] = $db_row;
									$olddb_rows['skus'][$olddbkey] = trim($db_row['SKU']);
									$olddb_rows['stocks'][$olddbkey] = trim($db_row['Stock']);
									$olddb_rows['v_disc'][$olddbkey] = trim($db_row['v_disc']);
									$olddb_rows['productETA'][$olddbkey] = trim($db_row['ProductETA']);
									$olddb_rows['Active'][$olddbkey] = trim($db_row['Active']);
									$olddb_rows['isDeleted'][$olddbkey] = trim($db_row['isDeleted']);
									$olddbkey++;
								}
								$matched_pattern = NULL;
								if($row[$n_rows_index] == 1)
								{
									$patterns = explode("|", $row[$file_pattern_index]);
									foreach($patterns as $pattern)
									{
										if(strpos($supplier_output_filename, trim($pattern)) !== false)
										{
											$matched_pattern = $pattern;
											break;
										}
									}
								}

								if(isset($_GET['skip_col']) && $_GET['skip_col'] == 'Y')
								{
									$file_db_out = $this->compareFile_DB_Rows_With_Skipped_Columns($sku_upc_value, $supplier_output_filename, $vendor_file_data, $olddb_rows, $matched_pattern);
								}
								else
								{
									$file_db_out = $this->compareFile_DB_Rows($sku_upc_value, $supplier_output_filename, $vendor_file_data, $olddb_rows, $matched_pattern);
								}

								if(isset($file_db_out))
								{
									$inv_tots = array();
									foreach($file_db_out as $sup_key=>$out_file)
									{
										if(!isset($inv_tots[$sup_key]))
										{
											$inv_tots[$sup_key] =array();
										}

										foreach($out_file as $sku_key => $o)
										{
											if(!array_key_exists($o['List Name'], $inv_tots[$sup_key]))
											{
												$inv_tots[$sup_key][$o['List Name']] = 1;
											}
											else
											{
												$inv_tots[$sup_key][$o['List Name']] = $inv_tots[$sup_key][$o['List Name']] + 1;
											}
										}

									}
								}

								//AH: save summary count into inv_tots
								if(isset($inv_tots) && !empty($inv_tots))
								{
									foreach($inv_tots as $s_key=>$inv)
									{
										$stmt = $this->conn->prepare("insert into inv_tots(currdate, supid, tot_in_file, tot_in_db, bad_file_rows, infile_not_db, indb_notfile, phasing_out) values(:curdate, :SupID, :totinFile, :totinDb, 0, :infile_notDB, :indb_notfile, :phasing_out)");
										$date = date('Y-m-d H:i:s');
										$vendor_file_data_count = count($vendor_file_data);
										$vendor_db_data_count = count($vendor_db_data);
										$stmt->bindParam(':curdate', $date, PDO::PARAM_STR);
										$stmt->bindParam(':SupID', $s_key, PDO::PARAM_INT);
										$stmt->bindParam(':totinFile', $vendor_file_data_count, PDO::PARAM_INT);
										$stmt->bindParam(':totinDb', $vendor_db_data_count, PDO::PARAM_INT);

										if(isset($inv['infile_not_db']) && $inv['infile_not_db'] > 0)
										{
											$infile_not_db = $inv['infile_not_db'];
										}
										else{
											$infile_not_db = 0;
										}

										$stmt->bindParam(':infile_notDB', $infile_not_db, PDO::PARAM_INT);

										if(isset($inv['indb_notfile']) && $inv['indb_notfile'] > 0)
										{
											$indb_notfile = $inv['indb_notfile'];
										}
										else{
											$indb_notfile = 0;
										}


										$stmt->bindParam(':indb_notfile', $indb_notfile, PDO::PARAM_INT);

										if(isset($inv['phasing_out']) && $inv['phasing_out'] > 0)
										{
											$phasing_out = $inv['phasing_out'];
										}
										else{
											$phasing_out = 0;
										}

										$stmt->bindParam(':phasing_out', $phasing_out, PDO::PARAM_INT);
										if($stmt->execute())
										{
											$message = "\n\r".date('Y-m-d H:i:s')." Table inv_tots is for supplier id <b>".$s_key."</b> updated successfully.\n\r";
											if(isset($this->logfp))
											{
												fwrite($this->logfp, $message);
											}
										}
									}
								}
								if(isset($this->logfp))
								{
									fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Moving src file to archive folder.\n\r");
								}
								if(file_exists($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_SRC_FOLDER_DAILYINVP."/".$row[$prefix_col_index]."/archive"))
								{
									foreach (new DirectoryIterator($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_SRC_FOLDER_DAILYINVP."/".$row[$prefix_col_index]) as $fileInfo) {

										if($fileInfo->isDot()) continue;
										$filewithinfo = pathinfo($fileInfo->getFileName());
										if(isset($filewithinfo['extension']))
										{
											//AH: move to archive
											if(isset($this->logfp))
											{
												fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." File ".$filewithinfo['basename']." is moved to archive folder. \n\r");
											}
											try{
												rename($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_SRC_FOLDER_DAILYINVP."/".$row[$prefix_col_index]."/".$filewithinfo['basename'], $_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_SRC_FOLDER_DAILYINVP."/".$row[$prefix_col_index]."/archive/".$filewithinfo['basename']);
											}
											catch(Exception $e)
											{
												if(isset($this->logfp))
												{
													fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s").$e->getMessage()." \n\r");
												}
											}
										}
									}
								}
							}

							if(isset($_GET['tab']) && $_GET['tab'] == 3)
							{
								if(isset($this->logfp))
								{
									fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Moving rows to output file and Updating all row values to file: ".$supplier_output_filename."\n\r");
								}
								//AH: Save output[] into a file for archive and reference (all rows already updated in DB in above loops)
                if($row[$n_rows_index] == 1){
                  $patterns = explode("|", $row[$file_pattern_index]);
                                if($filecount == count($patterns))
                                {
							$supplier_output_filename =  $row[$prefix_col_index]."_combined_".date('mdY_His', strtotime(date('Y-m-d H:i:s')))."_dpupdt".".csv";
                $this->outputCsv($row[$prefix_col_index], $supplier_output_filename, $output);
                                }
                }
                else {
                  $this->outputCsv($row[$prefix_col_index], $supplier_output_filename, $output);
                }


						}
					}
					else
					{
						if(isset($this->logfp))
						{
							fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: Inv File ".$filewithinfo['filename']." for vendor ".$row[$vendor_col_index]." is empty. Please check file content. \n\r");
						}
					}
						}
					}
					else{
							if(isset($this->logfp))
							{
								fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Serious: File ". $filewithinfo['filename']." is found without extension or its a folder. \n\r");
							}
						}

                    }
					}

                }
                $i++;
            }
	//AH: end ------------- now loop in inv_config.csv to read all vendors configured

			if(isset($file_db_out) && !empty($file_db_out))
			{
				$date = date("MdYHis");

				if(isset($this->logfp))
				{
					fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." compare all rows from file and db and message type in file: "."File_vs_DB_issues_".$date.".csv"."\n\r");
				}

				$this->outputCsv("file_vs_db", "File_vs_DB_issues_".$date.".csv", $file_db_out);
			}

        }
		if(isset($this->logfp))
		{
			fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Process completed. \n\r");
		}

    }
	catch(Exception $e)
	{
		if(isset($this->logfp))
		{
			fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s").$e->getMessage()."\n\r");
		}
	}

	}

	public function compareFile_DB_Rows($sku_upc_value, $supplier_output_filename, $file_rows, $olddb_rows, $pattern =  NULL)
	{
		try{

		$file_db = array();
		//Case3
		//Comparision file with DB

		$stmt = $this->conn->prepare("update products set Stock = :Stock where SupID=:SupID");
		$stmt->bindParam(':SupID', $file_rows[1]['supid'], PDO::PARAM_INT);
		$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
		if($stmt->execute())
		{
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Reset stock to 0 for supplier id ".$file_rows[1]['supid'].".\n\r");
			}
		}

		if(isset($this->logfp))
		{
			fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." First Pass: Processing arrays to update db\n\rCompare all file rows with each db rows\n\r");
		}

		$p_stock_0_aft = 0;
		$p_stock_gt_aft = 0;
		$p_vd_0_aft = 0;
		$p_vd_1_aft = 0;
		$p_prodeta_null_aft = 0;
		$p_prodeta_notnull_aft = 0;
		$rowmatch = 0;
		$stock_0_count_bef = 0;
		$stock_gt_count_bef = 0;
		$v_disc_0_count_bef = 0;
		$v_disc_1_count_bef = 0;
		$eta_null_count_bef = 0;
		$eta_not_null_count_bef = 0;
		$d_stock_0 = 0;
		$d_stock_gt = 0;
		$d_vd_0 = 0;
		$d_vd_1 = 0;
		$d_prodeta_null = 0;
		$d_prodeta_notnull = 0;
		$matchedSKUs = array();
		$p_skucnt = 0;
		$mergedLog = "";
		foreach($file_rows as $f_count=> $f_row)
		{
		//foreach($db_rows as $d_count => $d_row)

			//if(trim($f_row['sku']) == trim($d_row['SKU']))

			if(in_array(trim($f_row['sku']), $olddb_rows['skus']))
			{
				$searchkey = array_search(trim($f_row['sku']), $olddb_rows['skus']);
				if($olddb_rows['stocks'][$searchkey] == 0)
				{
					$stock_0_count_bef++;
				}
				if($olddb_rows['stocks'][$searchkey] > 0)
				{
					$stock_gt_count_bef++;
				}
				if($olddb_rows['v_disc'][$searchkey] == 0)
				{
					$v_disc_0_count_bef++;
				}
				if($olddb_rows['v_disc'][$searchkey] == 1)
				{
					$v_disc_1_count_bef++;
				}
				if($olddb_rows['productETA'][$searchkey] == "")
				{
					$eta_null_count_bef++;
				}
				if($olddb_rows['productETA'][$searchkey] != "")
				{
					$eta_not_null_count_bef++;
				}
				$matchedSKUs[] = "'".$f_row['sku']."'";
				if(isset($f_row['stock']) && $f_row['stock'] == 0)
				{
					$d_stock_0++;
				}
				if(isset($f_row['stock']) && $f_row['stock'] > 0)
				{
					$d_stock_gt++;
				}
				if(isset($f_row['v_disc']) && $f_row['v_disc'] == 0)
				{
					$d_vd_0++;
				}
				if(isset($f_row['v_disc']) && $f_row['v_disc'] == 1)
				{
					$d_vd_1++;
				}
				if(isset($f_row['eta']) && trim($f_row['eta']) == "NA")
				{
					$d_prodeta_null++;
				}
				if(isset($f_row['eta']) && trim($f_row['eta']) != "NA")
				{
					$d_prodeta_notnull++;
				}
				$rowmatch++;
				$mergedLog .= "\n\r".date("Y-m-d H:i:s").": File sku ".$f_row['sku']." matched DB SKU; ";
				if($sku_upc_value == 1)
				{
					$string = "UPC = :UPC";
				}
				else if($sku_upc_value == 0)
				{
					$string = "SKU = :SKU";
				}
				if(trim($f_row['v_disc']) == "NA" && (trim($f_row['stock']) == 0 || trim($f_row['stock']) < 0))
				{
					$mergedLog .= "Found record with no v_disc column and stock 0; row= ".($f_count+1)."; supid= ".trim($f_row['supid'])."; ";
					$eta = "";
					if(isset($f_row['eta']) && $f_row['eta'] != "NA")
					{
						//$eta = " ProductETA = :ProductETA, isDeleted = :isDeleted, ";
						$eta = " ProductETA = :ProductETA, ";
					}

					//AH: Actual DB update
					$stmt = $this->conn->prepare("update products set Stock = :Stock,".$eta." Active = :Active, isDeleted = :isDeleted where ". $string);
					$sku = trim($f_row['sku']);
					$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 0, PDO::PARAM_INT);
					if(isset($f_row['eta']) && $f_row['eta'] != "NA")
					{
						$eta_date=date_create(trim($f_row['eta']));
						$eta_value = date_format($eta_date,"Y-m-d H:i:s");
						$stmt->bindParam(':ProductETA', $eta_value, PDO::PARAM_STR);
						// Check if isDeleted should be set. //Atiq Hashmi
						//$stmt->bindValue(':isDeleted', 2, PDO::PARAM_INT);
					}
					$stmt->bindValue(':isDeleted', 0, PDO::PARAM_INT);
					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}
					if($stmt->execute())
					{
						$p_stock_0_aft++;
						if(isset($f_row['eta']) && $f_row['eta'] != "NA")
						{
							$mergedLog .= ($olddb_rows['productETA'][$searchkey] != $eta_value) ? "chged: eta '".$olddb_rows['productETA'][$searchkey]."' to ".$eta_value."; " : "";
							$p_prodeta_notnull_aft++;
						}
						$mergedLog .= "Not chged: v_disc ".$olddb_rows['v_disc'][$searchkey]."; ";
						$mergedLog .= "Not chged: isDeleted ".$olddb_rows['isDeleted'][$searchkey]."; ";
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != 0) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to 0; " : "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 0) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 0; " : "";
						$mergedLog .= "\n\r";
					}
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "in_file_and_db", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}
				else if(trim($f_row['v_disc']) == "NA" && trim($f_row['stock']) > 0)
				{
					$mergedLog .= "Found record with no v_disc column and stock > 0; row= ".($f_count+1)."; supid= ".trim($f_row['supid'])."; ";
					$eta = "";
					if(isset($f_row['eta']) && $f_row['eta'] != "NA")
					{
						//$eta = " ProductETA = :ProductETA, isDeleted = :isDeleted, ";
						$eta = " ProductETA = :ProductETA, ";
					}
					$stock = (int)trim($f_row['stock']);
					$stmt = $this->conn->prepare("update products set Stock = :Stock,".$eta." Active = :Active, isDeleted = :isDeleted where ". $string);
					$sku = trim($f_row['sku']);
					$stock = trim($f_row['stock']);
					$stmt->bindValue(':Stock', $stock, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 1, PDO::PARAM_INT);
					if(isset($f_row['eta']) && $f_row['eta'] != "NA")
					{
						$eta_date=date_create(trim($f_row['eta']));
						$eta_value = date_format($eta_date,"Y-m-d H:i:s");
						$stmt->bindParam(':ProductETA', $eta_value, PDO::PARAM_STR);
						// Check if isDeleted should be set. //Atiq Hashmi
						//$stmt->bindValue(':isDeleted', 2, PDO::PARAM_INT);
					}
					$stmt->bindValue(':isDeleted', 0, PDO::PARAM_INT);
					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}

					if($stmt->execute())
					{
						$p_stock_gt_aft++;
						if(isset($f_row['eta']) && $f_row['eta'] != "NA")
						{
							$mergedLog .= ($olddb_rows['productETA'][$searchkey] != $eta_value) ? "chged: eta '".$olddb_rows['productETA'][$searchkey]."' to ".$eta_value."; " : "";
							$p_prodeta_notnull_aft++;
						}
						$mergedLog .= "Not chged: v_disc ".$olddb_rows['v_disc'][$searchkey]."; ";
						$mergedLog .= "Not chged: isDeleted ".$olddb_rows['isDeleted'][$searchkey]."; ";
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != trim($f_row['stock'])) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to ".trim($f_row['stock'])."; " : "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 1) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 1; " : "";
						$mergedLog .= "\n\r";
					}
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "in_file_and_db", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}
				else if(trim($f_row['v_disc']) == 1 && (trim($f_row['stock']) == 0 || trim($f_row['stock']) == "" || trim($f_row['stock']) < 0))
				{
					$mergedLog .= "Found record with v_disc 1 and stock 0; ";
					$stmt = $this->conn->prepare("update products set Stock = :Stock, v_disc = :v_disc, Active = :Active, isDeleted = :isDeleted, ProductETA = :ProductETA where ". $string);
					$sku = trim($f_row['sku']);
					$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
					$stmt->bindValue(':v_disc', 1, PDO::PARAM_INT);
					//$stmt->bindValue(':Discontinued', 1, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 0, PDO::PARAM_INT);
					$stmt->bindValue(':isDeleted', 3, PDO::PARAM_INT);
					$stmt->bindValue(':ProductETA', "", PDO::PARAM_STR);

					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}

					if($stmt->execute())
					{
						$p_stock_0_aft++;
						$p_vd_1_aft++;
						$p_prodeta_null_aft++;
						$mergedLog .= "row= ".($f_count+1)."; ";
						$mergedLog .= "supid= ".trim($f_row['supid'])."; ";
						$mergedLog .= ($olddb_rows['v_disc'][$searchkey] != 1) ? "chged: v_disc ".$olddb_rows['v_disc'][$searchkey]." to 1; " : "";
						$mergedLog .= ($olddb_rows['productETA'][$searchkey] != '') ? "chged: eta '".$olddb_rows['productETA'][$searchkey]."' to ''; " : "";
						$mergedLog .= ($olddb_rows['isDeleted'][$searchkey] != 3) ? "chged: isDeleted ".$olddb_rows['isDeleted'][$searchkey]." to 3; " : "";
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != 0) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to 0; " : "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 0) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 0; " : "";
						$mergedLog .= "\n\r";
					}
				}
				else if(trim($f_row['v_disc']) == 1 && trim($f_row['stock']) > 0)
				{
					$mergedLog .= "Found record with v_disc 1 and stock > 0; ";
					$stmt = $this->conn->prepare("update products set Stock = :Stock, v_disc = :v_disc, Active = :Active, ProductETA = :ProductETA, isDeleted = :isDeleted where ". $string);
					$stock = trim($f_row['stock']);
					$sku = trim($f_row['sku']);
					$stmt->bindParam(':Stock', $stock, PDO::PARAM_INT);
					$stmt->bindValue(':v_disc', 1, PDO::PARAM_INT);
					//$stmt->bindValue(':Discontinued', 1, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 1, PDO::PARAM_INT);
					$stmt->bindValue(':ProductETA', "", PDO::PARAM_STR);
					$stmt->bindValue(':isDeleted', 1, PDO::PARAM_INT);
					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}
					if($stmt->execute())
					{
						$p_stock_gt_aft++;
						$p_vd_1_aft++;
						$p_prodeta_null_aft++;
						$mergedLog .= "row= ".($f_count+1)."; ";
						$mergedLog .= "supid= ".trim($f_row['supid'])."; ";
						$mergedLog .= ($olddb_rows['v_disc'][$searchkey] != 1) ? "chged: v_disc ".$olddb_rows['v_disc'][$searchkey]." to 1; " : "";
						$mergedLog .= ($olddb_rows['productETA'][$searchkey] != '') ? "chged: eta ".$olddb_rows['productETA'][$searchkey]." to ''; " : "";
						$mergedLog .= ($olddb_rows['isDeleted'][$searchkey] != 1) ? "chged: isDeleted ".$olddb_rows['isDeleted'][$searchkey]." to 1; " : "";
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != trim($f_row['stock'])) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to ".trim($f_row['stock'])."; ": "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 1) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 1; " : "";
						$mergedLog .= "\n\r";
					}
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "phasing_out", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}
				else if((trim($f_row['v_disc']) == 0 || trim($f_row['v_disc']) < 0) && trim($f_row['stock']) > 0)
				{
					$mergedLog .= "Found record with v_disc 0 and stock > 0; ";
					$stmt = $this->conn->prepare("update products set Stock = :Stock, v_disc = :v_disc, Active = :Active, ProductETA = :ProductETA, isDeleted = :isDeleted where ". $string);
					$stock = trim($f_row['stock']);
					$sku = trim($f_row['sku']);
					$stmt->bindParam(':Stock', $stock, PDO::PARAM_INT);
					$stmt->bindValue(':v_disc', 0, PDO::PARAM_INT);
					//$stmt->bindValue(':discontinued', 0, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 1, PDO::PARAM_INT);
					$stmt->bindValue(':ProductETA', "", PDO::PARAM_STR);
					$stmt->bindValue(':isDeleted', 0, PDO::PARAM_INT);
					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}
					if($stmt->execute())
					{
						$p_stock_gt_aft++;
						$p_vd_0_aft++;
						$p_prodeta_null_aft++;
						$mergedLog .= "row= ".($f_count+1)."; ";
						$mergedLog .= "supid= ".trim($f_row['supid'])."; ";
						$mergedLog .= ($olddb_rows['v_disc'][$searchkey] != 0) ? "chged: v_disc ".$olddb_rows['v_disc'][$searchkey]." to 0; " : "";
						$mergedLog .= ($olddb_rows['productETA'][$searchkey] != '') ? "chged: eta ".$olddb_rows['productETA'][$searchkey]." to ''; " : "";
						$mergedLog .= ($olddb_rows['isDeleted'][$searchkey] != 0) ? "chged: isDeleted ".$olddb_rows['isDeleted'][$searchkey]." to 0; " : "";
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != trim($f_row['stock'])) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to ".trim($f_row['stock'])."; ": "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 1) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 1; " : "";
						$mergedLog .= "\n\r";
					}
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "phasing_out", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}
				else if((trim($f_row['v_disc']) == 0 || trim($f_row['v_disc']) < 0) && (trim($f_row['stock']) == 0 || trim($f_row['stock']) < 0))
				{
					$mergedLog .= "Found record with v_disc 0 and stock 0; row= ".($f_count+1)."; supid= ".trim($f_row['supid'])."; ";
					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Also update ETA if available.\n\r");
					}

					if(isset($f_row['eta']) && $f_row['eta'] != "NA")
					{
						$eta = " ProductETA = :ProductETA, ";
					}
					else{
						$eta = " ProductETA = '', ";
					}

					$stmt = $this->conn->prepare("update products set Stock = :Stock, Active = :Active, v_disc = :v_disc, ".$eta." isDeleted = :isDeleted where ". $string);
					$sku = trim($f_row['sku']);
					$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 0, PDO::PARAM_INT);
					$stmt->bindValue(':v_disc', 0, PDO::PARAM_INT);
					/* Leave original value as it is for  now
					$stmt->bindValue(':v_disc', 0, PDO::PARAM_INT);
					*/
					if(isset($f_row['eta']) && $f_row['eta'] != "NA")
					{
						$eta_date=date_create(trim($f_row['eta']));
						$eta_value = date_format($eta_date,"Y-m-d H:i:s");
						$stmt->bindParam(':ProductETA', $eta_value, PDO::PARAM_STR);
					}

					$stmt->bindValue(':isDeleted', 2, PDO::PARAM_INT);
					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}
					if($stmt->execute())
					{
						$p_stock_0_aft++;
						$p_vd_0_aft++;
						if(isset($f_row['eta']) && $f_row['eta'] != "NA")
						{
							$mergedLog .= ($olddb_rows['productETA'][$searchkey] != $eta_value) ? "chged: eta ".$olddb_rows['productETA'][$searchkey]." to ".$eta_value."; " : "";
							$p_prodeta_notnull_aft++;
						}
						else{
							$mergedLog .= ($olddb_rows['productETA'][$searchkey] != '') ? " chged: eta ".$olddb_rows['productETA'][$searchkey]." to ''; " : "";
							$p_prodeta_null_aft++;
						}
						$mergedLog .= ($olddb_rows['v_disc'][$searchkey] != 0) ? "chged: v_disc ".$olddb_rows['v_disc'][$searchkey]." to 0; " : "";
						$mergedLog .= ($olddb_rows['isDeleted'][$searchkey] != 2) ? "chged: isDeleted ".$olddb_rows['isDeleted'][$searchkey]." to 2; " : "";
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != 0) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to 0; " : "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 0) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 0; " : "";
						$mergedLog .= "\n\r";
					}

					/*else
					{
						$stmt = $this->conn->prepare("update products set Stock = :Stock, active = :Active, v_disc = :v_disc, ProductETA = :ProductETA, isDeleted = :isDeleted where ". $string);
						$sku = trim($f_row['sku']);
						$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
						$stmt->bindValue(':Active', 0, PDO::PARAM_INT);
						$stmt->bindValue(':isDeleted', 2, PDO::PARAM_INT);
						$stmt->bindValue(':v_disc', 0, PDO::PARAM_INT);
						$stmt->bindValue(':ProductETA', "", PDO::PARAM_STR);
						if($sku_upc_value == 1)
						{
							$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
						}
						else if($sku_upc_value == 0)
						{
							$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
						}
						if($stmt->execute())
						{
							$p_stock_0_aft++;
							$p_vd_0_aft++;
							$p_prodeta_null_aft++;
							$message = "\n\r".date('Y-m-d H:i:s')." Row ".($f_count+1)." Record with sku <b>".trim($f_row['sku'])."</b> and supplier <b>".trim($f_row['supid'])."</b> is updated successfully. \n\r";
							if(isset($this->logfp)){
								fwrite($this->logfp, strip_tags($message));
							}
						}

					}*/
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "db_disctd_file_not", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}
				$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "in_file_and_db", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				//break;
			}
			else
			{
				$p_skucnt++;
				$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "infile_not_db", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
			}
		}

		if(isset($mergedLog))
		{
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".$mergedLog);
			}
		}

		$pattenrcond = "";
		if($pattern != NULL)
		{
			$pattenrcond = " and pattern LIKE :pattern";
		}

		/*
			Commented: required when update will be enabled @2019-07-09

		$stmt = $this->conn->prepare("select count(*) as count from dailyinvupdt_test where supplier=:SupID". $pattenrcond);
		$stmt->bindParam(':SupID', $file_rows[1]['supid'], PDO::PARAM_INT);
		if($pattern != NULL)
		{
			$stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
		}

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);*/

		/*
			Commented: as we want only insertion for now @2019-07-09

		while($db_row = $stmt->fetch()) {
			if($db_row['count'] > 0){
				$stmtupdate = $this->conn->prepare("update dailyinvupdt_test set
				currdate = :currdate,
				fileskucnt = :fileskucnt,
				p_stock_0_bef = :stock_0_count_bef,
				p_stock_gt_bef = :stock_gt_count_bef,
				p_vd_0_bef = :v_disc_0_count_bef,
				p_vd_1_bef = :v_disc_1_count_bef,
				p_prodeta_null_bef = :eta_null_count_bef,
				p_prodeta_notnull_bef = :eta_not_null_count_bef,
				p_stock_0_aft = :p_stock_0_aft,
				d_stock_0 = :d_stock_0,
				p_stock_gt_aft = :p_stock_gt_aft,
				d_stock_gt = :d_stock_gt,
				p_vd_0_aft = :p_vd_0_aft,
				d_vd_0 = :d_vd_0,
				p_vd_1_aft = :p_vd_1_aft,
				d_vd_1 = :d_vd_1,
				p_prodeta_null_aft = :p_prodeta_null_aft,
				d_prodeta_null = :d_prodeta_null,
				p_prodeta_notnull_aft = :p_prodeta_notnull_aft,
				d_prodeta_notnull = :d_prodeta_notnull,
				matchedskus = :matchedskus,
				p_skucnt = :p_skucnt
				where
				supplier=:SupID".$pattenrcond);
				$stmtupdate->bindParam(':SupID', $file_rows[1]['supid'], PDO::PARAM_INT);
				$stmtupdate->bindValue(':currdate', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmtupdate->bindParam(':fileskucnt', count($file_rows), PDO::PARAM_INT);
				$stmtupdate->bindParam(':stock_0_count_bef', $stock_0_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':stock_gt_count_bef', $stock_gt_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':v_disc_0_count_bef', $v_disc_0_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':v_disc_1_count_bef', $v_disc_1_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':eta_null_count_bef', $eta_null_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':eta_not_null_count_bef', $eta_not_null_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_stock_0_aft', $p_stock_0_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_stock_0', $d_stock_0, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_stock_gt_aft', $p_stock_gt_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_stock_gt', $d_stock_gt, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_vd_0_aft', $p_vd_0_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_vd_0', $d_vd_0, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_vd_1_aft', $p_vd_1_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_vd_1', $d_vd_1, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_prodeta_null_aft', $p_prodeta_null_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_prodeta_null', $d_prodeta_null, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_prodeta_notnull_aft', $p_prodeta_notnull_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_prodeta_notnull', $d_prodeta_notnull, PDO::PARAM_INT);
				$stmtupdate->bindParam(':matchedskus', $rowmatch, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_skucnt', $p_skucnt, PDO::PARAM_INT);
				if($pattern != NULL)
				{
					$stmtupdate->bindParam(':pattern', $pattern, PDO::PARAM_STR);
				}
				if($results = $stmtupdate->execute())
				{
					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")."Product table counts after database update for supllier id ".$file_rows[1]['supid'].".\n\r");
					}
				}
			}
			else*/
			{
				$patternparam = "NULL,";
				if($pattern != NULL)
				{
					$patternparam = ":pattern,";
				}
				$stmtinsert = $this->conn->prepare("Insert into dailyinvupdt_test values(
				'',
				:SupID,
				:currdate,
				:supplier_out_file,
				".$patternparam."
				:fileskucnt,
				(Select count(*) as count from products where SupID=:SupID ),
				:matchedskus,
				:stock_0_count_bef,
				:d_stock_0,
				:p_stock_0_aft,
				:stock_gt_count_bef,
				:d_stock_gt,
				:p_stock_gt_aft,
				:v_disc_0_count_bef,
				:d_vd_0,
				:p_vd_0_aft,
				:v_disc_1_count_bef,
				:d_vd_1,
				:p_vd_1_aft,
				:eta_null_count_bef,
				:d_prodeta_null,
				:p_prodeta_null_aft,
				:eta_not_null_count_bef,
				:d_prodeta_notnull,
				:p_prodeta_notnull_aft
				)");
				$stmtinsert->bindParam(':SupID', $file_rows[1]['supid'], PDO::PARAM_INT);
				$stmtinsert->bindValue(':currdate', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmtinsert->bindParam(':stock_0_count_bef', $stock_0_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':stock_gt_count_bef', $stock_gt_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':v_disc_0_count_bef', $v_disc_0_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':v_disc_1_count_bef', $v_disc_1_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':eta_null_count_bef', $eta_null_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':eta_not_null_count_bef', $eta_not_null_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':supplier_out_file', $supplier_output_filename, PDO::PARAM_STR);
				$stmtinsert->bindParam(':matchedskus', $rowmatch, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_stock_0', $d_stock_0, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_stock_0_aft', $p_stock_0_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_stock_gt', $d_stock_gt, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_stock_gt_aft', $p_stock_gt_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_vd_0', $d_vd_0, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_vd_0_aft', $p_vd_0_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_vd_1', $d_vd_1, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_vd_1_aft', $p_vd_1_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_prodeta_null', $d_prodeta_null, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_prodeta_null_aft', $p_prodeta_null_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_prodeta_notnull', $d_prodeta_notnull, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_prodeta_notnull_aft', $p_prodeta_notnull_aft, PDO::PARAM_INT);

				if($pattern != NULL)
				{
					$stmtinsert->bindParam(':pattern', $pattern, PDO::PARAM_STR);
				}
				$stmtinsert->bindParam(':fileskucnt', count($file_rows), PDO::PARAM_INT);
				$stmtinsert->bindValue(':p_skucnt', $p_skucnt, PDO::PARAM_INT);
				if($stmtinsert->execute())
				{
					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Initial product table counts are inserted for sup id ".$file_rows[1]['supid'].".\n\r");
					}
				}
			}
		//}
		//Comparision db with file

		foreach($file_rows as $f_row)
		{
			$f_skus[] = trim($f_row['sku']);
		}

		//indb_notfile is not coming because db to file comparison is commented
/* 		if(isset($this->logfp))
		{
			fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")."Second pass: Running into reverse loop now.\n\r");
		}
		foreach($db_rows as $d_count => $d_row)
		{
			if(!in_array(trim($d_row['SKU']), $f_skus))
			{
				if(isset($this->logfp))
				{
					fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Updating all records in db which are not found in file rows array.\n\r");
				}
				$stmt = $this->conn->prepare("update products set Stock = :Stock, v_disc = :v_disc, Discontinued = :Discontinued, active = :Active, isDeleted = :isDeleted where SKU = :SKU");
				$sku = trim($d_row['SKU']);
				$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
				$stmt->bindValue(':v_disc', 1, PDO::PARAM_INT);
				$stmt->bindValue(':Discontinued', 1, PDO::PARAM_INT);
				$stmt->bindValue(':Active', 0, PDO::PARAM_INT);
				$stmt->bindValue(':isDeleted', 3, PDO::PARAM_INT);
				$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
				if($stmt->execute())
				{
					echo $message = "\n\r".date('Y-m-d H:i:s')." Row ".($d_count+1)." Record with sku <b>".trim($d_row['SKU'])."</b> and supplier <b>".trim($d_row['SupID'])."</b> is updated successfully.\n\r";
					if(isset($this->logfp)){
						fwrite($this->logfp, strip_tags($message));
					}
				}
				$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "indb_notfile", "sup_id" => $f_row['supid'], "SKU" => $f_row['sku']);
			}
			else if($d_row['v_disc'] == 1 && $d_row['isDeleted'] == 3)
			{
				if(isset($this->logfp))
				{
					fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." If v_disc = 1 and isDeleted = 3 then mark record as inDBdisc_butalsoinfile\n\r");
				}
				$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "inDBdisc_butalsoinfile", "sup_id" => $f_row['supid'], "SKU" => $f_row['sku']);
			}

		} */

		return $file_db;
	}
		catch(\Exception $e)
		{
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s"). $e->getMessage(). "\n\r");
			}

		}
	}

	public function compareFile_DB_Rows_With_Skipped_Columns($sku_upc_value, $supplier_output_filename, $file_rows, $olddb_rows, $pattern =  NULL)
	{

		try{

		$file_db = array();
		//Case3
		//Comparision file with DB

		$stmt = $this->conn->prepare("update products set Stock = :Stock where SupID=:SupID");
		$stmt->bindParam(':SupID', $file_rows[1]['supid'], PDO::PARAM_INT);
		$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
		if($stmt->execute())
		{
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Reset stock to 0 for supplier id ".$file_rows[1]['supid'].".\n\r");
			}
		}

		if(isset($this->logfp))
		{
			fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." First Pass: Processing arrays to update db\n\rCompare all file rows with each db rows\n\r");
		}

		$p_stock_0_aft = 0;
		$p_stock_gt_aft = 0;
		$p_vd_0_aft = 0;
		$p_vd_1_aft = 0;
		$p_prodeta_null_aft = 0;
		$p_prodeta_notnull_aft = 0;
		$rowmatch = 0;
		$stock_0_count_bef = 0;
		$stock_gt_count_bef = 0;
		$v_disc_0_count_bef = 0;
		$v_disc_1_count_bef = 0;
		$eta_null_count_bef = 0;
		$eta_not_null_count_bef = 0;
		$d_stock_0 = 0;
		$d_stock_gt = 0;
		$d_vd_0 = 0;
		$d_vd_1 = 0;
		$d_prodeta_null = 0;
		$d_prodeta_notnull = 0;
		$matchedSKUs = array();
		$p_skucnt = 0;
		$mergedLog = "";
		foreach($file_rows as $f_count=> $f_row)
		{
		//foreach($db_rows as $d_count => $d_row)

			//if(trim($f_row['sku']) == trim($d_row['SKU']))

			if(in_array(trim($f_row['sku']), $olddb_rows['skus']))
			{
				$searchkey = array_search(trim($f_row['sku']), $olddb_rows['skus']);
				if($olddb_rows['stocks'][$searchkey] == 0)
				{
					$stock_0_count_bef++;
				}
				if($olddb_rows['stocks'][$searchkey] > 0)
				{
					$stock_gt_count_bef++;
				}

				$matchedSKUs[] = "'".$f_row['sku']."'";
				if(isset($f_row['stock']) && $f_row['stock'] == 0)
				{
					$d_stock_0++;
				}
				if(isset($f_row['stock']) && $f_row['stock'] > 0)
				{
					$d_stock_gt++;
				}
				if(isset($f_row['v_disc']) && $f_row['v_disc'] == 0)
				{
					$d_vd_0++;
				}
				if(isset($f_row['v_disc']) && $f_row['v_disc'] == 1)
				{
					$d_vd_1++;
				}
				if(isset($f_row['eta']) && trim($f_row['eta']) == "NA")
				{
					$d_prodeta_null++;
				}
				if(isset($f_row['eta']) && trim($f_row['eta']) != "NA")
				{
					$d_prodeta_notnull++;
				}
				$rowmatch++;
				$mergedLog .= "\n\r".date("Y-m-d H:i:s").": File sku ".$f_row['sku']." matched DB SKU; ";
				if($sku_upc_value == 1)
				{
					$string = "UPC = :UPC";
				}
				else if($sku_upc_value == 0)
				{
					$string = "SKU = :SKU";
				}
				if(trim($f_row['v_disc']) == "NA" && (trim($f_row['stock']) == 0 || trim($f_row['stock']) < 0))
				{
					$mergedLog .= "Found record with no v_disc column and stock 0; row= ".($f_count+1)."; supid= ".trim($f_row['supid'])."; ";

					//AH: Actual DB update
					$stmt = $this->conn->prepare("update products set Stock = :Stock, Active = :Active where ". $string);
					$sku = trim($f_row['sku']);
					$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 0, PDO::PARAM_INT);

					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}
					if($stmt->execute())
					{
						$p_stock_0_aft++;
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != 0) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to 0; " : "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 0) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 0; " : "";
						$mergedLog .= "\n\r";
					}
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "in_file_and_db", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}
				else if(trim($f_row['v_disc']) == "NA" && trim($f_row['stock']) > 0)
				{
					$mergedLog .= "Found record with no v_disc column and stock > 0; row= ".($f_count+1)."; supid= ".trim($f_row['supid'])."; ";

					$stmt = $this->conn->prepare("update products set Stock = :Stock, Active = :Active where ". $string);
					$sku = trim($f_row['sku']);
					$stock = trim($f_row['stock']);
					$stmt->bindParam(':Stock', $stock, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 1, PDO::PARAM_INT);
					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}

					if($stmt->execute())
					{
						$p_stock_gt_aft++;
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != trim($f_row['stock'])) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to ".trim($f_row['stock'])."; " : "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 1) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 1; " : "";
						$mergedLog .= "\n\r";
					}
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "in_file_and_db", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}

				else if(trim($f_row['v_disc']) == 1 && (trim($f_row['stock']) == 0 || trim($f_row['stock']) == "" || trim($f_row['stock']) < 0))
				{
					$mergedLog .= "Found record with v_disc 1 and stock 0; ";
					$stmt = $this->conn->prepare("update products set Stock = :Stock, Active = :Active where ". $string);
					$sku = trim($f_row['sku']);
					$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 0, PDO::PARAM_INT);

					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}

					if($stmt->execute())
					{
						$p_stock_0_aft++;
						$mergedLog .= "row= ".($f_count+1)."; ";
						$mergedLog .= "supid= ".trim($f_row['supid'])."; ";
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != 0) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to 0; " : "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 0) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 0; " : "";
						$mergedLog .= "\n\r";
					}
				}
				else if(trim($f_row['v_disc']) == 1 && trim($f_row['stock']) > 0)
				{
					$mergedLog .= "Found record with v_disc 1 and stock > 0; ";
					$stmt = $this->conn->prepare("update products set Stock = :Stock, Active = :Active where ". $string);
					$stock = trim($f_row['stock']);
					$sku = trim($f_row['sku']);
					$stmt->bindParam(':Stock', $stock, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 1, PDO::PARAM_INT);
					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}
					if($stmt->execute())
					{
						$p_stock_gt_aft++;
						$mergedLog .= "row= ".($f_count+1)."; ";
						$mergedLog .= "supid= ".trim($f_row['supid'])."; ";
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != trim($f_row['stock'])) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to ".trim($f_row['stock'])."; ": "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 1) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 1; " : "";
						$mergedLog .= "\n\r";
					}
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "phasing_out", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}
				else if((trim($f_row['v_disc']) == 0 || trim($f_row['v_disc']) < 0) && trim($f_row['stock']) > 0)
				{
					$mergedLog .= "Found record with v_disc 0 and stock > 0; ";
					$stmt = $this->conn->prepare("update products set Stock = :Stock, Active = :Active where ". $string);
					$stock = trim($f_row['stock']);
					$sku = trim($f_row['sku']);
					$stmt->bindParam(':Stock', $stock, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 1, PDO::PARAM_INT);
					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}
					if($stmt->execute())
					{
						$p_stock_gt_aft++;
						$mergedLog .= "row= ".($f_count+1)."; ";
						$mergedLog .= "supid= ".trim($f_row['supid'])."; ";
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != trim($f_row['stock'])) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to ".trim($f_row['stock'])."; ": "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 1) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 1; " : "";
						$mergedLog .= "\n\r";
					}
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "phasing_out", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}
				else if((trim($f_row['v_disc']) == 0 || trim($f_row['v_disc']) < 0) && (trim($f_row['stock']) == 0 || trim($f_row['stock']) < 0))
				{
					$mergedLog .= "Found record with v_disc 0 and stock 0; row= ".($f_count+1)."; supid= ".trim($f_row['supid'])."; ";

					$stmt = $this->conn->prepare("update products set Stock = :Stock, Active = :Active where ". $string);
					$sku = trim($f_row['sku']);
					$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
					$stmt->bindValue(':Active', 0, PDO::PARAM_INT);

					if($sku_upc_value == 1)
					{
						$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
					}
					else if($sku_upc_value == 0)
					{
						$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
					}
					if($stmt->execute())
					{
						$p_stock_0_aft++;
						$mergedLog .= ($olddb_rows['stocks'][$searchkey] != 0) ? "chged: Stock ".$olddb_rows['stocks'][$searchkey]." to 0; " : "";
						$mergedLog .= ($olddb_rows['Active'][$searchkey] != 0) ? "chged: Active ".$olddb_rows['Active'][$searchkey]." to 0; " : "";
						$mergedLog .= "\n\r";
					}

					/*else
					{
						$stmt = $this->conn->prepare("update products set Stock = :Stock, active = :Active, v_disc = :v_disc, ProductETA = :ProductETA, isDeleted = :isDeleted where ". $string);
						$sku = trim($f_row['sku']);
						$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
						$stmt->bindValue(':Active', 0, PDO::PARAM_INT);
						$stmt->bindValue(':isDeleted', 2, PDO::PARAM_INT);
						$stmt->bindValue(':v_disc', 0, PDO::PARAM_INT);
						$stmt->bindValue(':ProductETA', "", PDO::PARAM_STR);
						if($sku_upc_value == 1)
						{
							$stmt->bindParam(':UPC', $sku, PDO::PARAM_STR);
						}
						else if($sku_upc_value == 0)
						{
							$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
						}
						if($stmt->execute())
						{
							$p_stock_0_aft++;
							$p_vd_0_aft++;
							$p_prodeta_null_aft++;
							$message = "\n\r".date('Y-m-d H:i:s')." Row ".($f_count+1)." Record with sku <b>".trim($f_row['sku'])."</b> and supplier <b>".trim($f_row['supid'])."</b> is updated successfully. \n\r";
							if(isset($this->logfp)){
								fwrite($this->logfp, strip_tags($message));
							}
						}

					}*/
					$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "db_disctd_file_not", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				}
				$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "in_file_and_db", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
				//break;
			}
			else
			{
				$p_skucnt++;
				$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "infile_not_db", "Supplier ID" => trim($f_row['supid']), "SKU" => trim($f_row['sku']));
			}
		}

		if(isset($mergedLog))
		{
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".$mergedLog);
			}
		}

		$pattenrcond = "";
		if($pattern != NULL)
		{
			$pattenrcond = " and pattern LIKE :pattern";
		}
		/*
			Commented: required when update will be enabled @2019-07-09

		$stmt = $this->conn->prepare("select count(*) as count from dailyinvupdt_test where supplier=:SupID". $pattenrcond);
		$stmt->bindParam(':SupID', $file_rows[1]['supid'], PDO::PARAM_INT);
		if($pattern != NULL)
		{
			$stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
		}

		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_ASSOC);*/

		/*
			Commented: as we want only insertion for now @2019-07-09

		while($db_row = $stmt->fetch()) {
			if($db_row['count'] > 0){
				$stmtupdate = $this->conn->prepare("update dailyinvupdt_test set
				currdate = :currdate,
				fileskucnt = :fileskucnt,
				p_stock_0_bef = :stock_0_count_bef,
				p_stock_gt_bef = :stock_gt_count_bef,
				p_vd_0_bef = :v_disc_0_count_bef,
				p_vd_1_bef = :v_disc_1_count_bef,
				p_prodeta_null_bef = :eta_null_count_bef,
				p_prodeta_notnull_bef = :eta_not_null_count_bef,
				p_stock_0_aft = :p_stock_0_aft,
				d_stock_0 = :d_stock_0,
				p_stock_gt_aft = :p_stock_gt_aft,
				d_stock_gt = :d_stock_gt,
				p_vd_0_aft = :p_vd_0_aft,
				d_vd_0 = :d_vd_0,
				p_vd_1_aft = :p_vd_1_aft,
				d_vd_1 = :d_vd_1,
				p_prodeta_null_aft = :p_prodeta_null_aft,
				d_prodeta_null = :d_prodeta_null,
				p_prodeta_notnull_aft = :p_prodeta_notnull_aft,
				d_prodeta_notnull = :d_prodeta_notnull,
				matchedskus = :matchedskus,
				p_skucnt = :p_skucnt
				where
				supplier=:SupID".$pattenrcond);
				$stmtupdate->bindParam(':SupID', $file_rows[1]['supid'], PDO::PARAM_INT);
				$stmtupdate->bindValue(':currdate', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmtupdate->bindParam(':fileskucnt', count($file_rows), PDO::PARAM_INT);
				$stmtupdate->bindParam(':stock_0_count_bef', $stock_0_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':stock_gt_count_bef', $stock_gt_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':v_disc_0_count_bef', $v_disc_0_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':v_disc_1_count_bef', $v_disc_1_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':eta_null_count_bef', $eta_null_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':eta_not_null_count_bef', $eta_not_null_count_bef, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_stock_0_aft', $p_stock_0_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_stock_0', $d_stock_0, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_stock_gt_aft', $p_stock_gt_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_stock_gt', $d_stock_gt, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_vd_0_aft', $p_vd_0_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_vd_0', $d_vd_0, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_vd_1_aft', $p_vd_1_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_vd_1', $d_vd_1, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_prodeta_null_aft', $p_prodeta_null_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_prodeta_null', $d_prodeta_null, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_prodeta_notnull_aft', $p_prodeta_notnull_aft, PDO::PARAM_INT);
				$stmtupdate->bindParam(':d_prodeta_notnull', $d_prodeta_notnull, PDO::PARAM_INT);
				$stmtupdate->bindParam(':matchedskus', $rowmatch, PDO::PARAM_INT);
				$stmtupdate->bindParam(':p_skucnt', $p_skucnt, PDO::PARAM_INT);
				if($pattern != NULL)
				{
					$stmtupdate->bindParam(':pattern', $pattern, PDO::PARAM_STR);
				}
				if($results = $stmtupdate->execute())
				{
					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")."Product table counts after database update for supllier id ".$file_rows[1]['supid'].".\n\r");
					}
				}
			}
			else*/
			{
				$patternparam = "NULL,";
				if($pattern != NULL)
				{
					$patternparam = ":pattern,";
				}
				$stmtinsert = $this->conn->prepare("Insert into dailyinvupdt_test values(
				'',
				:SupID,
				:currdate,
				:supplier_out_file,
				".$patternparam."
				:fileskucnt,
				(Select count(*) as count from products where SupID=:SupID ),
				:matchedskus,
				:stock_0_count_bef,
				:d_stock_0,
				:p_stock_0_aft,
				:stock_gt_count_bef,
				:d_stock_gt,
				:p_stock_gt_aft,
				:v_disc_0_count_bef,
				:d_vd_0,
				:p_vd_0_aft,
				:v_disc_1_count_bef,
				:d_vd_1,
				:p_vd_1_aft,
				:eta_null_count_bef,
				:d_prodeta_null,
				:p_prodeta_null_aft,
				:eta_not_null_count_bef,
				:d_prodeta_notnull,
				:p_prodeta_notnull_aft
				)");
				$stmtinsert->bindParam(':SupID', $file_rows[1]['supid'], PDO::PARAM_INT);
				$stmtinsert->bindValue(':currdate', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmtinsert->bindParam(':stock_0_count_bef', $stock_0_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':stock_gt_count_bef', $stock_gt_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':v_disc_0_count_bef', $v_disc_0_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':v_disc_1_count_bef', $v_disc_1_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':eta_null_count_bef', $eta_null_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':eta_not_null_count_bef', $eta_not_null_count_bef, PDO::PARAM_INT);
				$stmtinsert->bindParam(':supplier_out_file', $supplier_output_filename, PDO::PARAM_STR);
				$stmtinsert->bindParam(':matchedskus', $rowmatch, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_stock_0', $d_stock_0, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_stock_0_aft', $p_stock_0_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_stock_gt', $d_stock_gt, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_stock_gt_aft', $p_stock_gt_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_vd_0', $d_vd_0, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_vd_0_aft', $p_vd_0_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_vd_1', $d_vd_1, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_vd_1_aft', $p_vd_1_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_prodeta_null', $d_prodeta_null, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_prodeta_null_aft', $p_prodeta_null_aft, PDO::PARAM_INT);
				$stmtinsert->bindParam(':d_prodeta_notnull', $d_prodeta_notnull, PDO::PARAM_INT);
				$stmtinsert->bindParam(':p_prodeta_notnull_aft', $p_prodeta_notnull_aft, PDO::PARAM_INT);

				if($pattern != NULL)
				{
					$stmtinsert->bindParam(':pattern', $pattern, PDO::PARAM_STR);
				}
				$stmtinsert->bindParam(':fileskucnt', count($file_rows), PDO::PARAM_INT);
				$stmtinsert->bindValue(':p_skucnt', $p_skucnt, PDO::PARAM_INT);
				if($stmtinsert->execute())
				{
					if(isset($this->logfp))
					{
						fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Initial product table counts are inserted for ".$row[$vendor_col_index].".\n\r");
					}
				}
			}
		//}
		//Comparision db with file
		echo "<b>Counts for vendor ".$file_rows[1]['supid'].": </b></br>";
		echo "<b>Total from vendor= </b>".((int)$d_stock_0 + (int)$d_stock_gt)."</br>";
		echo "<b>Total products in database= </b>".count($olddb_rows['skus'])."</br>";
		echo "<b>Total updated= </b>".((int)$p_stock_0_aft + (int)$p_stock_gt_aft)."</br>";
		echo "<b>Not updated= </b>".(count($olddb_rows['skus']) - ((int)$p_stock_0_aft + (int)$p_stock_gt_aft))."</br></br></br>";
		foreach($file_rows as $f_row)
		{
			$f_skus[] = trim($f_row['sku']);
		}

		//indb_notfile is not coming because db to file comparison is commented
/* 		if(isset($this->logfp))
		{
			fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")."Second pass: Running into reverse loop now.\n\r");
		}
		foreach($db_rows as $d_count => $d_row)
		{
			if(!in_array(trim($d_row['SKU']), $f_skus))
			{
				if(isset($this->logfp))
				{
					fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Updating all records in db which are not found in file rows array.\n\r");
				}
				$stmt = $this->conn->prepare("update products set Stock = :Stock, v_disc = :v_disc, Discontinued = :Discontinued, active = :Active, isDeleted = :isDeleted where SKU = :SKU");
				$sku = trim($d_row['SKU']);
				$stmt->bindValue(':Stock', 0, PDO::PARAM_INT);
				$stmt->bindValue(':v_disc', 1, PDO::PARAM_INT);
				$stmt->bindValue(':Discontinued', 1, PDO::PARAM_INT);
				$stmt->bindValue(':Active', 0, PDO::PARAM_INT);
				$stmt->bindValue(':isDeleted', 3, PDO::PARAM_INT);
				$stmt->bindParam(':SKU', $sku, PDO::PARAM_STR);
				if($stmt->execute())
				{
					echo $message = "\n\r".date('Y-m-d H:i:s')." Row ".($d_count+1)." Record with sku <b>".trim($d_row['SKU'])."</b> and supplier <b>".trim($d_row['SupID'])."</b> is updated successfully.\n\r";
					if(isset($this->logfp)){
						fwrite($this->logfp, strip_tags($message));
					}
				}
				$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "indb_notfile", "sup_id" => $f_row['supid'], "SKU" => $f_row['sku']);
			}
			else if($d_row['v_disc'] == 1 && $d_row['isDeleted'] == 3)
			{
				if(isset($this->logfp))
				{
					fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." If v_disc = 1 and isDeleted = 3 then mark record as inDBdisc_butalsoinfile\n\r");
				}
				$file_db[trim($f_row['supid'])][trim($f_row['sku'])] = array("List Name" => "inDBdisc_butalsoinfile", "sup_id" => $f_row['supid'], "SKU" => $f_row['sku']);
			}

		} */

		return $file_db;
	}
		catch(\Exception $e)
		{
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s"). $e->getMessage(). "\n\r");
			}

		}
	}
	public function outputCsv($supplier_folder, $fileName, $assocDataArray)
	{
		try{
			if(!file_exists($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_OUTPUT_FOLDER_DAILYINVP."/".$supplier_folder))
			{
				if(isset($this->logfp))
				{
					fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Create ".$supplier_folder." folder if not created.\n\r");
				}
				mkdir($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_OUTPUT_FOLDER_DAILYINVP."/".$supplier_folder, 0777);
			}

		if(isset($assocDataArray['1'])){

			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." Updating rows into file ".$fileName."\n\r");
			}

			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_OUTPUT_FOLDER_DAILYINVP."/".$supplier_folder."/".$fileName, 'w');
			fputcsv($fp, array_keys($assocDataArray['1']));
			foreach($assocDataArray as $values){
				fputcsv($fp, $values);
			}
			fclose($fp);
			//creating one copy to latest_toupld
			$fp = fopen($_SERVER['DOCUMENT_ROOT']."/".ROOT_DIRECTORY_DAILYINVP."/".VENDOR_OUTPUT_FOLDER_DAILYINVP."/latest_toupld/".$fileName, 'w');
			fputcsv($fp, array_keys($assocDataArray['1']));
			foreach($assocDataArray as $values){
				fputcsv($fp, $values);
			}
			fclose($fp);
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s")." File ". $fileName . " created successfully\n\r");
			}
		}
		}
		catch(\Exception $e)
		{
			if(isset($this->logfp))
			{
				fwrite($this->logfp, "\n\r".date("Y-m-d H:i:s"). $e->getMessage(). "\n\r");
			}
		}
	}
}


//AH: main
if(isset($_POST['submit']))
{
$invp_tool = new Invptool();	//calls constructor
$invp_tool->run();
}
?>
