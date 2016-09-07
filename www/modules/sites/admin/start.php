<?
function check_last(){
	global $glb;
	$date=date("Y-m-d");
	$sql="SELECT * FROM `counter` WHERE `domenID`='{$glb["domen_id"]}' AND `date`<'{$date}';";
	
	$res=mysql_query($sql);
	while($row=mysql_fetch_assoc($res))
	{
		$hibydate[$row['date']]+=1;
	}
	if(count($hibydate))
	{
		foreach($hibydate as $key=>$value)
		{
			$query5="SELECT distinct `ip` FROM `counter` WHERE `domenID`='{$glb["domen_id"]}' AND `date`='{$key}';";
			$result5 = mysql_query($query5);
			$users=mysql_num_rows($result5);
			mysql_query("INSERT INTO `counter_arhive` SET `domenID`='{$glb["domen_id"]}', `date`='{$key}', `hits`='{$value}', `users`='{$users}'");
			
		}
		mysql_query("DELETE FROM `counter` WHERE `domenID`='{$glb["domen_id"]}' AND `date`<'{$date}';");
	}
}
if(isset($_POST["clean_history"]))
{
	$sql="TRUNCATE TABLE `counter`;";mysql_query($sql);
	$sql="TRUNCATE TABLE `counter_arhive`;";mysql_query($sql);
}
check_last();
	$lines="";
	{
		$last_date=date("Y-m-d",strtotime(date("Y-m-d"))-60*60*24*31);
		$sql="
		SELECT * FROM `counter_arhive` WHERE `domenID`='{$glb["domen_id"]}' AND `date`>'{$last_date}' ORDER BY `date`;";
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res))
		{
			$hibydate[$row['date']]=$row['hits'];
			$usersbydate[$row['date']]=$row['users'];
		}
		$date=date("Y-m-d");
		for($i=86400;$i<2678400;$i+=86400)
		{
			$ssdate=date("Y-m-d",strtotime($last_date)+$i);
			$hibydate[$ssdate]=is_numeric($hibydate[$ssdate])?$hibydate[$ssdate]:0;
			$usersbydate[$ssdate]=is_numeric($usersbydate[$ssdate])?$usersbydate[$ssdate]:0;
			$lines.=" {'period': '{$ssdate}', 'licensed':{$hibydate[$ssdate]}, 'sorned':{$usersbydate[$ssdate]}},";
		}
		
		$query5="SELECT * FROM `counter` WHERE `domenID`='{$glb["domen_id"]}' AND `date`='{$date}';";
		$result5 = mysql_query($query5);
		$hits=mysql_num_rows($result5);

		$query5="SELECT distinct `ip` FROM `counter` WHERE `domenID`='{$glb["domen_id"]}' AND `date`='{$date}';";
		$result5 = mysql_query($query5);
		$users=mysql_num_rows($result5);
		
		$lines.=" {'period': '{$date}', 'licensed':{$hits}, 'sorned': {$users}}";
		
		$center= "
	<script src='/includes/graph/raphael-min.js'></script>
	<script src='/includes/graph/morris.js'></script>
	<script src='/includes/graph/prettify.js'></script>
	<script src='/includes/graph/example.js'></script>
		<h1>График посещений</h1>
<div id='graph' ></div>
<pre id='code' class='prettyprint linenums' style='display:none'>
// Use Morris.Area instead of Morris.Line
var day_data = [
  {$lines}
];
Morris.Area({
  element: 'graph',
  data: day_data,
   behaveLikeLine: true,
  xkey: 'period',
  ykeys: ['licensed', 'sorned'],
  labels: ['Страницы', 'Посетители'],
   xLabelAngle: 60
}).on('click', function(i, row){
  console.log(i, row);
});
</pre>
<br />
<style>
.cl_tablehead
{
display:none;
}
</style>
		<form action='{$request_url}' method='POST'>
			<input name='clean_history' type='submit' value='Очистить историю' />
		</form>
		";
	}
?>