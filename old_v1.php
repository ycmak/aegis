<html>

<head>
	<title>AEGIS</title>
	<style>
		body{
			margin: 0;
			font-size: 8pt;
			font-family: arial;
			cursor: crosshair;
			width: 3000px;
		}
	</style>
</head>

<script>

	function q(a){
		return document.getElementById(a);
	}

	function showthis(x,y,a){
		q("msg").innerHTML = a; 
		q("msg").style.left = (x+10)+"px";
		q("msg").style.top = (y+10)+"px";
	}

</script>

<body>

	<div id='msg' style='position:absolute;top:0px;left:0px;border:1px solid black;background-color:white;padding:5px;'></div>

<?php
	
	$link = mysql_connect('localhost','root','') or die('Cannot connect to database.');
	mysql_select_db('aegis') or die('Cannot select database.');
	/*$fp = fopen('mortality2.csv','r');
	$den = 1;
	while(!feof($fp)){
		$a = fgets($fp);
		$a = explode(',',$a);
		settype($a[0],'int');
		settype($a[1],'float');
		$pagb = $a[1]/$den;
		mysql_query('INSERT INTO `mortality`(`age`,`p_death`,`pagb`) VALUES("'.$a[0].'","'.$a[1].'","'.$pagb.'")',$link);
		$den *= (1-$a[1]);
	}
	fclose($fp);

	die('done');*/

	// GRAPHITE PLUGIN
	//include('Graph.php');

	$_DATA = array();
	$_DATA['gender'] = array('Male','Female');
	$_DATA['races'] = array('Chinese','Malay','Indian','Others');
	$_DATA['nationality'] = array('Citizen','PR','Foreigner');
	//$_DATA['ca'] = array(127.7,150.0,177.6,186.7,159.8,194.9,216.1,229.0,234.5,241.1,228.4,186.8,126.8,97.0,66.3,48.1,28.1,22.8);
	//$_DATA['ca'] = array(193.9,223.6,253.8,262.9,225.4,263.1,289.8,307.2,317.4,318.1,289.2,229.4,153.2,115.2,81.3,59.0,33.9,26.4);
	// data modified for tail end >85 years old
	$_DATA['ca'] = array(193.9,223.6,253.8,262.9,225.4,263.1,289.8,307.2,317.4,318.1,289.2,229.4,153.2,115.2,81.3,59.0,33.9,16,7.4,3);
	$_DATA['tfr'] = array(0,0,0,6.1,29.1,78.9,94.6,41.5,6.6,0.2);
	$_DATA['imr'] = 2.1; // infant mortality rate per 1,000 births
	$_DATA['migration'] = 0.009; // net inflow of migrants annually, as a % of current resident population

	for($x=0;$x<count($_DATA['ca']);$x++){
		$_DATA['ca2'][$x] = floor($_DATA['ca'][$x]);
	}

	$t = 2009;
	$cnt = 0;
	$agents = array();

	# Populates the world with agents
	function popAgents(){
		global $_DATA,$cnt,$agents;
		for($x=0;$x<count($_DATA['ca2']);$x++){
			for($y=0;$y<$_DATA['ca2'][$x];$y++){
				$agents[$cnt] = new Agent;
				$agents[$cnt]->setAge($x*5+mt_rand(0,4));
				$cnt++;
			}
		}
	}

	// A basic agent.
	class Agent{
		# Positive attributes
		public $age,$gender,$nationality,$race,$educ,$income,$married,$id;
		function __construct(){
			$this->gender = mt_rand(0,1);
			$this->race = $_DATA['races'][mt_rand(0,3)];
		}
		function setAge($a){
			$this->age = $a;
		}
		function setGender($g){
			$this->gender = $g;
		}
	}

	$deathrate = array();
	// Load data from SQL
	$r2 = mysql_query('SELECT * FROM `mortality`',$link);
	while($r = mysql_fetch_assoc($r2)){
		$deathrate[$r['age']] = $r['p_death']*10000;
	}

	function median($arr){
		sort($arr);
		if(count($arr)%2==0) return ($arr[(count($arr)/2)]+$arr[(count($arr)/2)-1])/2;
		else return $arr[(count($arr)-1)/2];
	}

	// Start the game
	popAgents();
	$originalpop = $cnt;

	for($y=$t;$y<=2100;$y++){
		$total_alive = 0;
		$newborn = 0;
		$newdead = 0;
		$totalAge = 0;
		$agecat['b15'] = 0;
		$agecat['15t39'] = 0;
		$agecat['40t64'] = 0;
		$agecat['a64'] = 0;
		if(isset($ages)) unset($ages);
		$ages = array(); // for calculation of popn statistics
		// IMPROVING LIFE EXPECTANCY
		unset($k);
		foreach($deathrate as $k=>$v){
			$deathrate[$k] *= 0.995;
		}
		unset($key);
		foreach($agents as $key=>$value){
			$z = $agents[$key];
			// Chance of dying
			if(mt_rand(0,10000)<$deathrate[$z->age]){
				unset($agents[$key]);
				$newdead++;
				continue;
			}
			// Chance of giving birth
			if($z->gender==1&&$z->age>=15&&$z->age<50){
				if(mt_rand(0,1000)<$_DATA['tfr'][floor($z->age/5)]){
					// Infant mortality rate
					if(mt_rand(0,10000)>$_DATA['imr']*10){
						$agents[$cnt] = new Agent;
						$agents[$cnt]->age = 0;
						$cnt++;
						$newborn++;
						$ages[] = 0;
						$agecat['b15']++;
					}
				}
			}
			$z->age++;
			$total_alive++;
			$ages[] = $z->age;
			if($z->age<15) $agecat['b15']++;
			if($z->age>=15&&$z->age<40) $agecat['15t39']++;
			if($z->age>=40&&$z->age<65) $agecat['40t64']++;
			if($z->age>=65) $agecat['a64']++;
		}
		
		// MIGRATION
		// Create new agents who are migrants
		// Currently, migrants are assumed to ~ Unif[0,50]
		//for($x=0;$x<floor($_DATA['migration']*$total_alive);$x++){
		// Currently, migrants come in 39,300 per year
		for($x=0;$x<39;$x++){
			$agents[$cnt] = new Agent;
			$agents[$cnt]->age = mt_rand(0,50);
			$ages[] = $agents[$cnt]->age;
			if($agents[$cnt]->age<15) $agecat['b15']++;
			if($agents[$cnt]->age>=15&&$agents[$cnt]->age<40) $agecat['15t39']++;
			if($agents[$cnt]->age>=40&&$agents[$cnt]->age<65) $agecat['40t64']++;
			if($agents[$cnt]->age>=65) $agecat['a64']++;
			$cnt++;
		}
		$total_alive *= (1+$_DATA['migration']);
		$total_alive = floor($total_alive);

		// DISPLAY
		$title = 'Year: '.$y.'<br>';
		$title .= 'Pop: '.$total_alive.' ('.(round($total_alive/$originalpop*10000)/100).'%)<br>';
		$title .= 'Born: '.$newborn.'<br>';
		$title .= 'Dead: '.$newdead.'<br>';
		$title .= 'Median age: '.median($ages).'<br>';
		$title .= '&lt;15 yo: '.$agecat['b15'].'<br>';
		$title .= '15 to 39 yo: '.$agecat['15t39'].'<br>';
		$title .= '40 to 64 yo: '.$agecat['40t64'].'<br>';
		$title .= '&gt;64yo: '.$agecat['a64'].'<br>';
		print('<div style="width:'.round($agecat['b15']/7).'px;height:4px;background-color:#00ff00;float:left;clear:left;" onmousemove="showthis(event.clientX,event.clientY,\''.$title.'\');"></div>');
		print('<div style="width:'.round($agecat['15t39']/7).'px;height:4px;background-color:#ffff00;float:left;" onmousemove="showthis(event.clientX,event.clientY,\''.$title.'\');"></div>');
		print('<div style="width:'.round($agecat['40t64']/7).'px;height:4px;background-color:#ff9900;float:left;" onmousemove="showthis(event.clientX,event.clientY,\''.$title.'\');"></div>');
		print('<div style="width:'.round($agecat['a64']/7).'px;height:4px;background-color:#ff0000;float:left;" onmousemove="showthis(event.clientX,event.clientY,\''.$title.'\');"></div>');

		if($y==2050){
			print('<div style="position:absolute;top:162px;width:900px;border-top:1px solid black;text-align:right;" onmousemove="showthis(event.clientX,event.clientY,\''.$title.'\');">2050</div>');
		}
	}

?>

</body>

</html>