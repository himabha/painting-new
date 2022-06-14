<?php
include_once(dirname(__FILE__).'/../config.php');
include_once(dirname(__FILE__).'/DB.php');
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")
{
	$http = "https://";
}
else
{
	$http = "http://";
}
$db = new DbConnection;
$conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// prepare and bind
$stmt = $conn->prepare("SELECT * from frames where active = 1 order by sort asc");
//$stmt->bind_param("sss", $firstname, $lastname, $email);
$stmt->execute();
$result= $stmt->get_result();
if($result->num_rows === 0) exit('No rows');
?>
<div id="table">    
	<?php 
	$i = 0;
	while($row = $result->fetch_assoc()) {
		$data = json_encode($row);
		if($i % 3 == 0)
		{
	?>
		<div class="tr">
	<?php 
		}
	?>
	<div class="td">
		<img width="150px" height="150px" class="frame_img" src="<?php echo $http.$_SERVER['HTTP_HOST'].ROOT_DIRECTORY.$row['img_path'];?>"/>
		<input type="hidden" class="frame_data" name="frame_data_<?php echo $i;?>" id="frame_data_<?php echo $i;?>" value='<?php echo $data; ?>'/>
	</div>
	<?php
		if($i > 0 && ($i+1) % 3 == 0)
		{
	?>
		</div>
	<?php 
		}
		$i++;
	}
	?>
</div>
<?php 
	$stmt->close();
	$conn->close();
?>
<style>
#table{ 
    display: table;
	float:left;
}
.tr{ 
    display: table-row; 
}
.td{ 
    display: table-cell;
}
.td img
{
	padding:15px;
}
</style>

<script>
$(document).ready(function(){
	$(".frame_img").on("click", function(){
		var frame_data = ($("#frame_data_"+$(".frame_img").index(this)).val());
		$("#frame_selected").val(frame_data);
		$(".frame_img").removeClass("selected");
		$(this).addClass("selected");		
	})
	
	$("#frame_type").on("change", function(){
		$("#frame_selected").val("");
		var val = $(this).val();
		$(".frame_data").each(function(index){
			var framedata = JSON.parse($(this).val());
			if(framedata.type == val)
			{
				$(".td:eq("+index+")").css("display", "block");
			}
			else{
				$(".td:eq("+index+")").css("display", "none");
			}
			
		})
		
	})
})
</script>