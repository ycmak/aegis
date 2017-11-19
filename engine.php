<?php
//	var_dump($_GET);
//	die();
?>
<html>

<head>
	<title>hmm</title>
    <style>
		body{
			cursor: crosshair;	
		}
	</style>
    <script language="javascript" src="js-class.js" type="text/javascript"></script>
	<script language="javascript" src="bluff-src.js" type="text/javascript"></script>
	<script language="javascript" src="excanvas.js" type="text/javascript"></script>

</head>

<script language='javascript'>

	function moveGuides(evt){
		document.getElementById("chY").style.left = evt.clientX+"px";
		document.getElementById("chX").style.top = evt.clientY+"px";
	}

</script>

<body onmousemove='moveGuides(event);'>

        <div style="margin:0;position:absolute;top:0;left:0;">
          <canvas id="example"></canvas>
        </div>

        <div style="margin:0;position:absolute;top:0;left:0;opacity:0.5;">
          <canvas id="example2"></canvas>
        </div>

<?php
	/*
	print('<pre>');
	var_dump($_GET);
	die('</pre>');
	*/
	ob_start();
	function flushall(){
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();
	}

	include('config.inc.php');
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

	# Populates the world with agents
	function popAgents(){
		global $_DATA,$cnt,$agents;
		$crap = 0;
		for($x=0;$x<count($_DATA['ca2']);$x++){
			for($y=0;$y<$_DATA['ca2'][$x];$y++){
				$agents[$cnt] = new Agent;
				$agents[$cnt]->setAge($x*5+mt_rand(0,4));
				// Setting marital status... currently use the same array for both males and females
				$agents[$cnt]->married = (mt_rand(0,1000)<$_DATA['marriage_cum'][$agents[$cnt]->age]) ? true : false;
				// Employment status
				if($agents[$cnt]->gender==0){
					$agents[$cnt]->econ_active = (mt_rand(0,10000)<($_DATA['lf_m'][floor($agents[$cnt]->age/5)]*100)) ? true : false;
				}
				elseif($agents[$cnt]->gender==1)
					$agents[$cnt]->econ_active = (mt_rand(0,10000)<($_DATA['lf_f'][floor($agents[$cnt]->age/5)]*100)) ? true : false;
				if($agents[$cnt]->econ_active){
					$agents[$cnt]->employed = (mt_rand(0,10000)<($o['unE']*100)) ? false : true;
				}
				// Education Status if above 15 years old
				$lol = mt_rand(0,10000);
				$sum = 0;
				$band = $agents[$cnt]->age-($agents[$cnt]->age%5);
				if($agents[$cnt]->age<15) $band = 'final';
				$z = 0;
				$notyet = true;
				while($notyet&&$z<5){
					$sum += $_DATA['educ_'.$band][$z]*10000;
					if($lol<=$sum){
						$agents[$cnt]->educ = $z;
						$notyet = false;	
					}
					$z++;
				}
				$cnt++;
			}
		}
		//var_dump($_DATA['lf_m']);
		//die($crap.' hah');
	}

	// A basic agent.
	class Agent{
		# Positive attributes
		public $age,$gender,$nationality,$race,$educ,$income,$married,$id,$econ_active,$employed;
		function __construct(){
			$this->gender = mt_rand(0,1); // 0 is male, 1 is female
			$this->race = $_DATA['races'][mt_rand(0,3)];
			$this->married = false;
		}
		function setAge($a){
			$this->age = $a;
		}
		function setGender($g){
			$this->gender = $g;
		}
	}

	$deathrate = array();
/*	// Load data from SQL
	$r2 = mysql_query('SELECT * FROM `mortality`',$link);
	while($r = mysql_fetch_assoc($r2)){
		$deathrate[$r['age']] = $r['p_death']*10000;
	}*/
	
$deathrate[0] = 21.1;
$deathrate[1] = 1.5;
$deathrate[2] = 1.5;
$deathrate[3] = 1.4;
$deathrate[4] = 1.3;
$deathrate[5] = 1.1;
$deathrate[6] = 1;
$deathrate[7] = 0.9;
$deathrate[8] = 0.9;
$deathrate[9] = 0.9;
$deathrate[10] = 0.9;
$deathrate[11] = 0.9;
$deathrate[12] = 1;
$deathrate[13] = 1.1;
$deathrate[14] = 1.4;
$deathrate[15] = 1.6;
$deathrate[16] = 1.9;
$deathrate[17] = 2.2;
$deathrate[18] = 2.5;
$deathrate[19] = 2.8;
$deathrate[20] = 3.2;
$deathrate[21] = 3.5;
$deathrate[22] = 3.7;
$deathrate[23] = 3.9;
$deathrate[24] = 3.9;
$deathrate[25] = 3.9;
$deathrate[26] = 3.9;
$deathrate[27] = 4;
$deathrate[28] = 4;
$deathrate[29] = 4;
$deathrate[30] = 4;
$deathrate[31] = 4;
$deathrate[32] = 4.1;
$deathrate[33] = 4.5;
$deathrate[34] = 5;
$deathrate[35] = 5.4;
$deathrate[36] = 6;
$deathrate[37] = 6.6;
$deathrate[38] = 7.5;
$deathrate[39] = 8.5;
$deathrate[40] = 9.6;
$deathrate[41] = 10.7;
$deathrate[42] = 11.8;
$deathrate[43] = 13.1;
$deathrate[44] = 14.4;
$deathrate[45] = 15.6;
$deathrate[46] = 17;
$deathrate[47] = 18.7;
$deathrate[48] = 20.8;
$deathrate[49] = 23.2;
$deathrate[50] = 25.7;
$deathrate[51] = 28.3;
$deathrate[52] = 31.2;
$deathrate[53] = 34.6;
$deathrate[54] = 38.3;
$deathrate[55] = 42.2;
$deathrate[56] = 46.1;
$deathrate[57] = 50.5;
$deathrate[58] = 55.5;
$deathrate[59] = 60.7;
$deathrate[60] = 66;
$deathrate[61] = 71.8;
$deathrate[62] = 79.3;
$deathrate[63] = 89.5;
$deathrate[64] = 101.1;
$deathrate[65] = 113.1;
$deathrate[66] = 125.7;
$deathrate[67] = 140.5;
$deathrate[68] = 158.7;
$deathrate[69] = 179.1;
$deathrate[70] = 200.1;
$deathrate[71] = 221.5;
$deathrate[72] = 244.8;
$deathrate[73] = 271;
$deathrate[74] = 298.8;
$deathrate[75] = 326.8;
$deathrate[76] = 356.1;
$deathrate[77] = 389.8;
$deathrate[78] = 430.2;
$deathrate[79] = 474.3;
$deathrate[80] = 519.1;
$deathrate[81] = 564.8;
$deathrate[82] = 615.9;
$deathrate[83] = 677.1;
$deathrate[84] = 748.2;
$deathrate[85] = 822.6;
$deathrate[86] = 903.5;
$deathrate[87] = 991.6;
$deathrate[88] = 1087.1;
$deathrate[89] = 1190.8;
$deathrate[90] = 1302.9;
$deathrate[91] = 1424.1;
$deathrate[92] = 1554.9;
$deathrate[93] = 1695.7;
$deathrate[94] = 1847.1;
$deathrate[95] = 2009.6;
$deathrate[96] = 2183.7;
$deathrate[97] = 2369.6;
$deathrate[98] = 2568;
$deathrate[99] = 2779;
$deathrate[100] = 10000;

	function median($arr){
		sort($arr);
		if(count($arr)%2==0) return ($arr[(count($arr)/2)]+$arr[(count($arr)/2)-1])/2;
		else return $arr[(count($arr)-1)/2];
	}

	// MULTIPLE SCENARIOS
	$_OP = array();
	$scenarios = 1;
	$max_scenarios = $_GET['scenarios'];
	settype($max_scenarios,'integer');
	//
	// ITERATE THROUGH EACH SCENARIO HERE
	//
	while($scenarios<=$max_scenarios):

	// Output array for graphing purposes
	$_OP[$scenarios] = array();

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
	$_DATA['migration'] = 0.1119; // net inflow of migrants annually, as a % of current resident population
	
	// EDUCATION (STATIC DATA FIRST, FOR GENERATING START POPULATION)
	$_DATA['educ_15'] = array(0.01924,0.03426,0.87193,0.07458,0.00000);
	$_DATA['educ_20'] = array(0.01402,0.01681,0.47821,0.36292,0.12803);
	$_DATA['educ_25'] = array(0.01442,0.02202,0.32068,0.27143,0.37145);
	$_DATA['educ_30'] = array(0.02346,0.04369,0.37751,0.20292,0.35241);
	$_DATA['educ_35'] = array(0.04514,0.07192,0.46567,0.15211,0.26517);
	$_DATA['educ_40'] = array(0.06830,0.12104,0.52026,0.11011,0.18029);
	$_DATA['educ_45'] = array(0.11315,0.16369,0.52768,0.08707,0.10841);
	$_DATA['educ_50'] = array(0.16043,0.16744,0.52729,0.06274,0.08210);
	$_DATA['educ_55'] = array(0.26420,0.18264,0.44165,0.05306,0.05843);
	$_DATA['educ_60'] = array(0.41079,0.17775,0.31943,0.04406,0.04797);
	$_DATA['educ_65'] = array(0.53254,0.16939,0.23290,0.03196,0.03321);
	$_DATA['educ_70'] = array(0.66606,0.13990,0.15089,0.02067,0.02248);
	$_DATA['educ_75'] = array(0.74894,0.10601,0.12138,0.01004,0.01363);
	$_DATA['educ_80'] = array(0.74894,0.10601,0.12138,0.01004,0.01363); // these tail end stats are artificial
	$_DATA['educ_85'] = array(0.74894,0.10601,0.12138,0.01004,0.01363);
	$_DATA['educ_90'] = array(0.74894,0.10601,0.12138,0.01004,0.01363);
	$_DATA['educ_95'] = array(0.74894,0.10601,0.12138,0.01004,0.01363);
	
	$_DATA['educ_final'] = array(0.01442,0.02202,0.32068,0.27143,0.37145);


	for($x=0;$x<count($_DATA['ca']);$x++){
		$_DATA['ca2'][$x] = floor($_DATA['ca'][$x]);
	}

	# Get options from parent UI
	$o = array();
	$t = 2009;
	$o['tEnd'] = $_GET['endtime'];
	settype($o['tEnd'],'int');
	
	// Check for the no. of different years in this scenario
	$_tmp = explode('|',$_GET['yearlist_'.$scenarios]);
	if(isset($_years)) unset($_years);
	$_years = array();
	foreach($_tmp as $key => $value){
		settype($value,'integer');
		$_years[$value] = true;
	}
	unset($_tmp);

	$cnt = 0;
	$agents = array();

	for($y=$t;$y<=$o['tEnd'];$y++){
		
		// Get data based on the year
		// do this ONLY if this year is a new scenario
		
		if(isset($_years[$y])):
	
		
			$o['le_increase'] = $_GET['life_'.$scenarios.'_'.$y];
			settype($o['le_increase'],'int');
			$o['le_increase'] /= 100;
			$o['unE'] = $_GET['opt_unE_'.$scenarios.'_'.$y]; // seasonally adjusted unemployment rate, this comes as a percentage (float)
			settype($o['unE'],'float');
	
			// IMMIGRATION
			if($_GET['opt_immigrants_what_'.$scenarios.'_'.$y]=='people'){
				settype($_GET['opt_immigrants_'.$scenarios.'_'.$y],'integer');
				$immigrants_limit = round($_GET['opt_immigrants_'.$scenarios.'_'.$y]/1000);
			}
			elseif($_GET['opt_immigrants_what_'.$scenarios.'_'.$y]=='percent'){
				settype($_GET['opt_immigrants_'.$scenarios.'_'.$y],'float');
				$immigrants_limit = round($_GET['opt_immigrants_'.$scenarios.'_'.$y]/100*$total_alive);
			}
			$immigrants_min = $_GET['opt_immigrants_min_'.$scenarios.'_'.$y];
			$immigrants_max = $_GET['opt_immigrants_max_'.$scenarios.'_'.$y];
			settype($immigrants_min,'integer');
			settype($immigrants_max,'integer');
			
			// MARRIAGE
			$_DATA['marriage_m'] = explode(',',$_GET['opt_marriageM_'.$scenarios.'_'.$y]);
			for($x=0;$x<count($_DATA['marriage_m']);$x++){
				settype($_DATA['marriage_m'][$x],'float');
			}
			$newsum = 0;
			for($x=0;$x<count($_DATA['marriage_m']);$x++){
				// marriage rate for males, per 1,000 resident males
				$newsum += $_DATA['marriage_m'][$x]*3.5;
				$_DATA['marriage_m'][$x] = $_DATA['marriage_m'][$x]/(1000+$newsum)*1000;
			}
			$_DATA['marriage_f'] = explode(',',$_GET['opt_marriageF_'.$scenarios.'_'.$y]);
			for($x=0;$x<count($_DATA['marriage_f']);$x++){
				settype($_DATA['marriage_f'][$x],'float');
			}
			$newsum = 0;
			for($x=0;$x<count($_DATA['marriage_f']);$x++){
				// marriage rate for females, per 1,000 resident females
				$newsum += $_DATA['marriage_f'][$x]*3.5;
				$_DATA['marriage_f'][$x] = $_DATA['marriage_f'][$x]/(1000+$newsum)*1000;
			}
			$_DATA['marriage_cum'] = array(0,0,0); // cumulative marriage rate for females, by age group
			$_DATA['tfr_marriage'] = array(0,0,0); // revised TFR for females, conditional upon marriage
			for($x=3;$x<count($_DATA['tfr']);$x++){
				for($w=0;$w<5;$w++){
					$_DATA['marriage_cum'][$x*5+$w] = $_DATA['marriage_cum'][$x*5+$w-1]+$_DATA['marriage_f'][$x];
				}
			}
			for($x=3;$x<count($_DATA['tfr']);$x++){
				for($w=0;$w<5;$w++){
					$_DATA['tfr_marriage'][$x*5+$w] = $_DATA['tfr'][$x]/$_DATA['marriage_cum'][$x*5+$w];
				}
			}
			// Sets the cumulative marriage rate for females, after 45 years of age
			for($x=50;$x<=100;$x++) $_DATA['marriage_cum'][$x] = $_DATA['marriage_cum'][49];
			
			// Labour force
			// Age-specific resident male+female labour force participation rates
			// Data from Yearbook of Statistics 2009, chapter 4.3
			// 2008 statistics
			//$_DATA['lf_m'] = array(0,0,0,13.9,66.1,93.3,98.1,97.7,97.5,96.6,93.0,84.9,64.7,40.1,23.4,9.4);
			$_DATA['lf_m'] = explode(',',$_GET['opt_lfM_'.$scenarios.'_'.$y]);

			for($x=0;$x<count($_DATA['lf_m']);$x++){
				settype($_DATA['lf_m'][$x],'float');
			}
			//var_dump($_DATA['lf_m']);
				//							   die();
			$_DATA['lf_f'] = explode(',',$_GET['opt_lfF_'.$scenarios.'_'.$y]);
			for($x=0;$x<count($_DATA['lf_f']);$x++){
				settype($_DATA['lf_f'][$x],'float');
			}
			//var_dump($_DATA['lf_m']);
			//die();
			// percentage chance of joining or quitting LFPR (% of total popn)
			$_DATA['lf_m_diff'] = array(0,0,0,$_DATA['lf_m'][3]);
			$_DATA['lf_f_diff'] = array(0,0,0,$_DATA['lf_f'][3]);
			for($x=4;$x<count($_DATA['lf_m']);$x++){
				$_DATA['lf_m_diff'][$x] = (($_DATA['lf_m'][$x]-$_DATA['lf_m'][$x-1])>0) ? (($_DATA['lf_m'][$x]-$_DATA['lf_m'][$x-1])/(100-$_DATA['lf_m'][$x-1]))*100 : (($_DATA['lf_m'][$x]-$_DATA['lf_m'][$x-1])/$_DATA['lf_m'][$x-1])*100;
				$_DATA['lf_f_diff'][$x] = (($_DATA['lf_f'][$x]-$_DATA['lf_f'][$x-1])>0) ? (($_DATA['lf_f'][$x]-$_DATA['lf_f'][$x-1])/(100-$_DATA['lf_f'][$x-1]))*100 : (($_DATA['lf_f'][$x]-$_DATA['lf_f'][$x-1])/$_DATA['lf_f'][$x-1])*100;
			}
			
			if($y==2009){
				// Start the game
				popAgents();
				$originalpop = $cnt;	
			}
		
		endif;
		
		
		
		//
		// Key indicator variables
		//
		$total_alive = 0;
		$newborn = 0;
		$newdead = 0;
		$totalAge = 0;
		$married = 0;
		$econ_active = 0;
		$employed = 0;
		$males = 0;
		$females = 0;
		$agecat['b15'] = 0;
		$agecat['15t39'] = 0;
		$agecat['40t64'] = 0;
		$agecat['a64'] = 0;
		// To check labour force aging symdrome
		$labour_agecat['b15'] = 0;
		$labour_agecat['15t39'] = 0;
		$labour_agecat['40t64'] = 0;
		$labour_agecat['a64'] = 0;
		// Education
		$educ_polyuni64 = 0;

		if(isset($ages)) unset($ages);
		$ages = array(); // for calculation of median popn age
		$ages_labour = array(); // for calculation of median labour age

		// IMPROVING LIFE EXPECTANCY
		unset($k);
		foreach($deathrate as $k=>$v){
			$deathrate[$k] *= 1-$o['le_increase'];
		}

		// BIRTH AND MARRIAGE
		unset($key);
		foreach($agents as $key=>$value){
			$z = $agents[$key];
			// Chance of dying
			if(mt_rand(0,10000)<$deathrate[$z->age]){
				unset($agents[$key]);
				$newdead++;
				continue;
			}
			// Chance of getting married
			if(!$z->married&&$z->age>=15&&$z->age<45){ // not already married
				if($z->gender==1){
					if(mt_rand(0,1000)<$_DATA['marriage_f'][floor($z->age/5)]){
						$z->married = true;
					}
				}elseif($z->gender==0){
					if(mt_rand(0,1000)<$_DATA['marriage_m'][floor($z->age/5)]){
						$z->married = true;
					}
				}
			}
			// Chance of giving birth
			// You can only give birth if you're female and you're married
			//if($z->gender==1&&$z->age>=15&&$z->age<50){
			if($z->married&&$z->gender==1){
				/*
				print('marriage_cum, per 1000:<br />');
				var_dump($_DATA['marriage_cum']);
				print('<br>');
				print('TFR, per 1000:<br />');
				var_dump($_DATA['tfr']);
				print('<br>');
				print('prob of child given marriage:<br>');
				var_dump($_DATA['tfr_marriage']);
				print('<hr>');
				for($x=0;$x<count($_DATA['tfr_marriage']);$x++){
					print(($_DATA['tfr_marriage'][$x]).'<br>');
				}
				die();
				*/
				// two different cases for tfr_marriage <=1 and >1:
				$givebirth = false;
				if($_DATA['tfr_marriage'][$z->age]<=1){
					// assume a Unif distribution
					if(mt_rand(0,1000)<($_DATA['tfr_marriage'][$z->age]*1000)) $givebirth = true;
				}
				if($givebirth){
					// Infant mortality rate
					if(mt_rand(0,10000)>$_DATA['imr']*10){
						$agents[$cnt] = new Agent;
						$agents[$cnt]->age = 0;

						// EDUCATION
						$lol = mt_rand(0,10000);
						$sum = 0;
						$asd = 0;
						$notyet = true;
						while($notyet&&$asd<5){
							$sum += $_DATA['educ_final'][$asd]*10000;
							if($lol<=$sum){
								$agents[$cnt]->educ = $asd;
								$notyet = false;	
							}
							$asd++;
						}
						
						$cnt++;
						$newborn++;
						$ages[] = 0;
						$agecat['b15']++;
					}
				}
			}
			if($z->married) $married++;

			// LABOUR AND PRODUCTIVITY
			// Each agent has a certain percentage of joining or quitting the labour force
			// Each agent who is economically active (i.e. in the labour force) has a certain chance of being unemployed
			$tmp_g = ($z->gender==0) ? 'm' : 'f';

			if($_DATA['lf_'.$tmp_g.'_diff'][floor($z->age/5)]>0){ // if LFPR increases for this age range
				if(!$z->econ_active){ // if not currently in labour force
					if(mt_rand(0,10000)<($_DATA['lf_'.$tmp_g.'_diff'][floor($z->age/5)]*100)){
						$z->econ_active = true; // joins the labour force
						$z->employed = (mt_rand(0,10000)<($o['unE']*100)) ? false : true; // employed or not
					}
				}
			}elseif($_DATA['lf_'.$tmp_g.'_diff'][floor($z->age/5)]<0){ // if LFPR decreases for this age range
				if($z->econ_active&&$z->age%5==0){ // if currently in labour force AND (simplification) enters a new age group
					if(mt_rand(0,10000)<($_DATA['lf_'.$tmp_g.'_diff'][floor($z->age/5)]*-100)){
						$z->econ_active = false; // quit the labour force
						$z->employed = false; // not employed
					}
				}
			}
			// if no change in LFPR for this age group, no agent gets in or out of labour force

			$z->age++;
			$total_alive++;
			$ages[] = $z->age;
			if($z->age<15) $agecat['b15']++;
			if($z->age>=15&&$z->age<40) $agecat['15t39']++;
			if($z->age>=40&&$z->age<65) $agecat['40t64']++;
			if($z->age>=65) $agecat['a64']++;
			if($z->econ_active){
				$econ_active++;
				$ages_labour[] = $z->age;
				if($z->age<15) $labour_agecat['b15']++;
				if($z->age>=15&&$z->age<40) $labour_agecat['15t39']++;
				if($z->age>=40&&$z->age<65) $labour_agecat['40t64']++;
				if($z->age>=65) $labour_agecat['a64']++;
			}
			if($z->employed) $employed++;
			if($z->gender==0) $males++;
			else $females++;
			if($z->age>=65&&$z->educ>=3) $educ_polyuni64++;
		}

		// IMMIGRATION
		// Create new agents who are migrants
		// Currently, migrants are assumed to ~ Unif[0,50]
		//for($x=0;$x<floor($_DATA['migration']*$total_alive);$x++){
		// Currently, migrants come in 39,300 per year
		for($x=0;$x<$immigrants_limit;$x++){
			$agents[$cnt] = new Agent;
			$agents[$cnt]->age = mt_rand($immigrants_min,$immigrants_max);
			$ages[] = $agents[$cnt]->age;
			$ages_labour[] = $agents[$cnt]->age;
			if($agents[$cnt]->age<15) $agecat['b15']++;
			if($agents[$cnt]->age>=15&&$agents[$cnt]->age<40) $agecat['15t39']++;
			if($agents[$cnt]->age>=40&&$agents[$cnt]->age<65) $agecat['40t64']++;
			if($agents[$cnt]->age>=65) $agecat['a64']++;
			// Set marriage status
			if($agents[$cnt]->age>=15){
				$agents[$cnt]->married = (mt_rand(0,1000)<$_DATA['marriage_cum'][$agents[$cnt]->age]) ? true : false;
			}
			// Set labour/employment status
			if($agents[$cnt]->gender==0)
				$agents[$cnt]->econ_active = (mt_rand(0,10000)<($_DATA['lf_m'][floor($agents[$cnt]->age/5)*100])) ? true : false;
			elseif($agents[$cnt]->gender==1)
				$agents[$cnt]->econ_active = (mt_rand(0,10000)<($_DATA['lf_f'][floor($agents[$cnt]->age/5)*100])) ? true : false;
			if($agents[$cnt]->econ_active)
				$agents[$cnt]->employed = (mt_rand(0,10000)<($o['unE']*100)) ? false : true;
			// Set education status
			// CURRENTLY WE ASSUME THE IMMIGRANTS' EDUCATION PROFILE ISTHE SAME AS RESIDENTS
			$lol = mt_rand(0,10000);
			$sum = 0;
			$asd = 0;
			$notyet = true;
			while($notyet&&$asd<5){
				$sum += $_DATA['educ_final'][$asd]*10000;
				if($lol<=$sum){
					$agents[$cnt]->educ = $asd;
					$notyet = false;	
				}
				$asd++;
			}
			
			$cnt++;
			if($agents[$cnt]->married) $married++;
			if($agents[$cnt]->econ_active){
				$econ_active++;
				$ages_labour[] = $agents[$cnt]->age;
				if($agents[$cnt]->age<15) $labour_agecat['b15']++;
				if($agents[$cnt]->age>=15&&$agents[$cnt]->age<40) $labour_agecat['15t39']++;
				if($agents[$cnt]->age>=40&&$agents[$cnt]->age<65) $labour_agecat['40t64']++;
				if($agents[$cnt]->age>=65) $labour_agecat['a64']++;
			}
			if($agents[$cnt]->employed) $employed++;
			if($agents[$cnt]->gender==0) $males++;
			else $females++;
			// EDUCATION
			if($agents[$cnt]->age>=65&&$agents[$cnt]->educ>=3) $educ_polyuni64++;
		}
		//$total_alive *= (1+$_DATA['migration']);
		$total_alive += $immigrants_limit;
		//$total_alive = floor($total_alive);

		// COLLECT THE DATA
		$_OP[$scenarios][$y] = array();
		$_OP[$scenarios][$y]['total_alive'] = $total_alive;
		$_OP[$scenarios][$y]['b15'] = $agecat['b15'];
		$_OP[$scenarios][$y]['15t39'] = $agecat['15t39'];
		$_OP[$scenarios][$y]['40t64'] = $agecat['40t64'];
		$_OP[$scenarios][$y]['a64'] = $agecat['a64'];
		
		// Labour force size by age group
		$_OP[$scenarios][$y]['labour_b15'] = $labour_agecat['b15'];
		$_OP[$scenarios][$y]['labour_15t39'] = $labour_agecat['15t39'];
		$_OP[$scenarios][$y]['labour_40t64'] = $labour_agecat['40t64'];
		$_OP[$scenarios][$y]['labour_a64'] = $labour_agecat['a64'];
		
		// LFPR by age group
		$_OP[$scenarios][$y]['lfpr_b15'] = $labour_agecat['b15']/$agecat['b15']*100;
		$_OP[$scenarios][$y]['lfpr_15t39'] = $labour_agecat['15t39']/$agecat['15t39']*100;
		$_OP[$scenarios][$y]['lfpr_40t64'] = $labour_agecat['40t64']/$agecat['40t64']*100;
		$_OP[$scenarios][$y]['lfpr_a64'] = $labour_agecat['a64']/$agecat['a64']*100;
		
		$_OP[$scenarios][$y]['a15'] = $total_alive - $agecat['b15'];
		$_OP[$scenarios][$y]['married'] = round($married/($total_alive-$agecat['b15'])*10000)/100;
		$_OP[$scenarios][$y]['born'] = $newborn;
		$_OP[$scenarios][$y]['dead'] = $newdead;
		$_OP[$scenarios][$y]['median_age'] = median($ages);
		$_OP[$scenarios][$y]['median_worker_age'] = median($ages_labour);
		$_OP[$scenarios][$y]['immigrants'] = $immigrants_limit;
		$_OP[$scenarios][$y]['econ_active'] = $econ_active;
		$_OP[$scenarios][$y]['employed'] = $employed;
		$_OP[$scenarios][$y]['lfpr'] = $econ_active/($total_alive-$agecat['b15'])*100;
		$_OP[$scenarios][$y]['gender_ratio'] = $males/$females*100;
		
		// EDUCATION
		$_OP[$scenarios][$y]['educ_polyuni64'] = $educ_polyuni64;
		
		// Sets the progressbar
		$progress = round(((($scenarios-1)/$max_scenarios)+((($y-$t)/($o['tEnd']-$t))/$max_scenarios))*100);
		print('<script>');
		print('window.parent.$("#pbar").progressbar("value",'.$progress.');'."\n");
		print('window.parent.q("pvalue").innerHTML = "'.$progress.'% complete";'."\n");
		print('</script>');
		flushall();
	}
	$scenarios++;
	endwhile;

	?>
<script>
	function a() {
		// Specify graph type
		var g = new Bluff.<?php print($_GET['graphtype']); ?>('example', '900x550');

		g.set_theme({
			colors: ['red', 'green', 'blue', 'orange', 'purple', '#3a5b87', 'black'],
			marker_color: '#aea9a9',
			font_color: 'black',
			background_colors: ['#eeeeee', '#ffffff']
		});

          g.title = 'Forecast 2009 to <?php print($o['tEnd']); ?>';
          g.hide_dots = true;
		  g.marker_count = 10;
			g.marker_font_size = 11;
			g.legend_font_size = 12;
			g.title_font_size = 16;
			g.font = "Georgia";
			g.x_axis_label = "Year";


		<?php
			$scenarios = 1;
			while($scenarios<=$max_scenarios):
			
			unset($key);
			// Total population
			if(isset($_GET['opt_var_totalpop'])){
				print('g.data("Population (thousands) #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['total_alive']);
					$cnt++;
				}
				print(']);');
			}
			// Below 15
			if(isset($_GET['opt_var_pop_b15'])){
				unset($key);
				print('g.data("<15 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['b15']);
					$cnt++;
				}
				print(']);');
			}
			// 15 to 39
			if(isset($_GET['opt_var_pop_15t39'])){
				unset($key);
				print('g.data("15 to 39 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['15t39']);
					$cnt++;
				}
				print(']);');
			}
			// 40 to 64
			if(isset($_GET['opt_var_pop_40t64'])){
				unset($key);
				print('g.data("40 to 64 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['40t64']);
					$cnt++;
				}
				print(']);');
			}
			// Above 64
			if(isset($_GET['opt_var_pop_a64'])){
				unset($key);
				print('g.data(">64 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['a64']);
					$cnt++;
				}
				print(']);');
			}
			// Above 15
			if(isset($_GET['opt_var_pop_a15'])){
				unset($key);
				print('g.data(">=15 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['a15']);
					$cnt++;
				}
				print(']);');
			}
			
			// POPULATION PERCENTAGE COMPOSITION BY AGE GROUP
			// Below 15
			if(isset($_GET['opt_var_popp_b15'])){
				unset($key);
				print('g.data("<15 (%) #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print(($_OP[$scenarios][$key]['b15']/$_OP[$scenarios][$key]['total_alive']*100));
					$cnt++;
				}
				print(']);');
			}
			// 15 to 39
			if(isset($_GET['opt_var_popp_15t39'])){
				unset($key);
				print('g.data("15 to 39 (%) #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print(($_OP[$scenarios][$key]['15t39']/$_OP[$scenarios][$key]['total_alive']*100));
					$cnt++;
				}
				print(']);');
			}
			// 40 to 64
			if(isset($_GET['opt_var_popp_40t64'])){
				unset($key);
				print('g.data("40 to 64 (%) #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print(($_OP[$scenarios][$key]['40t64']/$_OP[$scenarios][$key]['total_alive']*100));
					$cnt++;
				}
				print(']);');
			}
			// Above 64
			if(isset($_GET['opt_var_popp_a64'])){
				unset($key);
				print('g.data(">64 (%) #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print(($_OP[$scenarios][$key]['a64']/$_OP[$scenarios][$key]['total_alive']*100));
					$cnt++;
				}
				print(']);');
			}
			// Above 15
			if(isset($_GET['opt_var_popp_a15'])){
				unset($key);
				print('g.data(">=15 (%) #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print(($_OP[$scenarios][$key]['a15']/$_OP[$scenarios][$key]['total_alive']*100));
					$cnt++;
				}
				print(']);');
			}
			
			// Married
			if(isset($_GET['opt_var_mrate'])){
				unset($key);
				print('g.data("Marriage Rate #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['married']);
					$cnt++;
				}
				print(']);');
			}
			// Dead
			if(isset($_GET['opt_var_deaths'])){
				unset($key);
				print('g.data("Deaths #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['dead']);
					$cnt++;
				}
				print(']);');
			}
			// Births
			if(isset($_GET['opt_var_births'])){
				unset($key);
				print('g.data("Births #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['born']);
					$cnt++;
				}
				print(']);');
			}
			// Median Age
			if(isset($_GET['opt_var_medianage'])){
				unset($key);
				print('g.data("Median Age #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['median_age']);
					$cnt++;
				}
				print(']);');
			}
			// Immigrants
			if(isset($_GET['opt_var_immigrants'])){
				unset($key);
				print('g.data("New Immigrants #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['immigrants']);
					$cnt++;
				}
				print(']);');
			}
			// Labour Force
			if(isset($_GET['opt_econ_active'])){
				unset($key);
				print('g.data("Labour Force #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['econ_active']);
					$cnt++;
				}
				print(']);');
			}
			// Employed
			if(isset($_GET['opt_employed'])){
				unset($key);
				print('g.data("Employed #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['employed']);
					$cnt++;
				}
				print(']);');
			}
			// Labour Force Participation Rate (LFPR)
			if(isset($_GET['opt_lfpr'])){
				unset($key);
				print('g.data("LFPR #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['lfpr']);
					$cnt++;
				}
				print(']);');
			}
			// Gender Ratio (males to females)
			if(isset($_GET['opt_gender_ratio'])){
				unset($key);
				print('g.data("Gender Ratio (males to females) #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['gender_ratio']);
					$cnt++;
				}
				print(']);');
			}
			// Median Worker Age
			if(isset($_GET['opt_var_median_worker_age'])){
				unset($key);
				print('g.data("Median Worker Age #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['median_worker_age']);
					$cnt++;
				}
				print(']);');
			}
			// LABOUR Below 15
			if(isset($_GET['opt_var_labour_b15'])){
				unset($key);
				print('g.data("Labour <15 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['labour_b15']);
					$cnt++;
				}
				print(']);');
			}
			// LABOUR 15 to 39
			if(isset($_GET['opt_var_labour_15t39'])){
				unset($key);
				print('g.data("Labour 15 to 39 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['labour_15t39']);
					$cnt++;
				}
				print(']);');
			}
			// LABOUR 40 to 64
			if(isset($_GET['opt_var_labour_40t64'])){
				unset($key);
				print('g.data("Labour 40 to 64 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['labour_40t64']);
					$cnt++;
				}
				print(']);');
			}
			// LABOUR Above 64
			if(isset($_GET['opt_var_labour_a64'])){
				unset($key);
				print('g.data("Labour >64 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['labour_a64']);
					$cnt++;
				}
				print(']);');
			}

			// LFPR Below 15
			if(isset($_GET['opt_var_lfpr_b15'])){
				unset($key);
				print('g.data("LFPR <15 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['lfpr_b15']);
					$cnt++;
				}
				print(']);');
			}
			// LFPR 15 to 39
			if(isset($_GET['opt_var_lfpr_15t39'])){
				unset($key);
				print('g.data("LFPR 15 to 39 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['lfpr_15t39']);
					$cnt++;
				}
				print(']);');
			}
			// LFPR 40 to 64
			if(isset($_GET['opt_var_lfpr_40t64'])){
				unset($key);
				print('g.data("LFPR 40 to 64 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['lfpr_40t64']);
					$cnt++;
				}
				print(']);');
			}
			// LFPR Above 64
			if(isset($_GET['opt_var_lfpr_a64'])){
				unset($key);
				print('g.data("LFPR >64 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['lfpr_a64']);
					$cnt++;
				}
				print(']);');
			}
			
			// EDUCATION: POLYUNI ABOVE 64
			// LFPR Above 64
			if(isset($_GET['opt_var_educ_polyuni64'])){
				unset($key);
				print('g.data("Poly/Uni >64 #'.$scenarios.'", [');
				$cnt = 0;
				foreach($_OP[$scenarios] as $key=>$value){
					if($cnt>0) print(',');
					print($_OP[$scenarios][$key]['educ_polyuni64']);
					$cnt++;
				}
				print(']);');
			}
			
			$scenarios++;
			endwhile;
			
			// Print the labels (years)
			unset($key);
			print('g.labels = {');
			$cnt = 0;
			foreach($_OP[1] as $key=>$value){
				if($cnt%10==0){
					if($cnt>0) print(',');
					print($cnt.':'."'".$key."'");
				}
				$cnt++;
			}
			print('};');
			
			// VERTICAL AXIS
			if($_GET['yaxis']=='start_zero') print('g.minimum_value = 0;');
			
		?>
     
          g.draw();
        };

		a();
		parent.window.$("#status").dialog("close");
		parent.window.$("#ui-main").dialog("close");
		parent.window.$("#ui-panel").fadeIn();
<?php
// end

?>
</script>

	<div id='chY' style='position:absolute;top:0px;width:1px;height:100%;background-color:black;'></div>
	<div id='chX' style='position:absolute;left:0px;height:1px;width:100%;font-size:1px;background-color:black;'></div>
    
</body>

</html>