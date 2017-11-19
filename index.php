<html>

<head>
	<title>ADelphi</title>
	<link type="text/css" href="css/mcys/jquery-ui-1.7.2.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
	<style>
		body{
			margin: 0;
		}
		table{

		}
		h1{
			font-size: 14pt;
			color: #1484e6;
			font-variant: small-caps;
		}
		.ui-progressbar-value { background-image: url(css/mcys/images/pbar-ani.gif); }
		.ui-btn{
			padding: 5px;
			text-align: center;
			cursor: pointer;
		}
		select,input.text{
			background-color: white;
			font-family: "trebuchet ms";
			padding: 3px;
			border: 1px solid silver;
		}
		select:hover,input.text:hover{
			background-color: white;
			font-family: "trebuchet ms";
			padding: 3px;
			border: 1px solid red;
			color: #aa0000;
		}
		.ui-widget th{
			width: 100px;
			text-align: right;
			padding-right: 10px;
			color: #002092;
			vertical-align: top;
		}
		.slider-main span{
			height: 200px;
			float: left;
			cursor: pointer;
			margin: 15px;
			margin-bottom: 0px;
		}
		.slider-value span{
			height: 20px;
			float: left;
			margin: 15px;
			margin-top: 5px;
			cursor: pointer;
			width: 12px;
			font-size: 8pt;
			font-weight: bold;
		}
		.slider-value{
			clear:left;	
		}
		.slider-main2 span{
			height: 200px;
			float: left;
			cursor: pointer;
			margin: 6px;
			margin-bottom: 0px;
		}
		.slider-value2{
			clear:left;	
		}
		.slider-value2 span{
			height: 20px;
			float: left;
			margin: 6px;
			margin-top: 5px;
			cursor: pointer;
			width: 12px;
			font-size: 7pt;
			font-weight: bold;
		}
		<?php
			for($x=1;$x<=3;$x++){
				print('#tab_scenarios-'.$x);
				if($x<3) print(',');
			}
			print('{overflow:auto;height:340px;}');
			for($x=1;$x<=3;$x++){
				print('#tab_options-'.$x);
				if($x<3) print(',');
			}
			print('{overflow:auto;height:340px;}');
		?>
	</style>
</head>

<script>

	function q(a){
		return document.getElementById(a);
	}

	function qv(a){
		return q(a).value;
	}

	function changeInputs(){
		var els = document.getElementsByTagName('input');
		var elsLen = els.length;
		var i = 0;
		for (i=0;i<elsLen;i++){
			if(els[i].getAttribute('type')){
				if(els[i].getAttribute('type')=="text") els[i].className = "text ui-corner-all";
				else els[i].className = "button";
			}
		}
	}

	function showthis(x,y,a){
		q("msg").innerHTML = a; 
		q("msg").style.left = (x+10)+"px";
		q("msg").style.top = (y+10)+"px";
	}

	// jQuery stuff
	$(document).ready(function () {
		changeInputs();
		
		$("#status").dialog({
			bgiframe: true,
			height: 140,
			modal: true,
			autoOpen: false,
			hide: 'fold'
		});
		$("#error, #msgbox").dialog({
			bgiframe: true,
			height: 250,
			width: 400,
			modal: true,
			autoOpen: false,
			hide: 'fold',
			buttons: {
				"Continue": function(){
					$(this).dialog("close");	
				}
			}
		});
		$("#about").dialog({
			bgiframe: true,
			height: 350,
			width: 500,
			modal: true,
			autoOpen: false,
			hide: 'fold',
			buttons: {
				"Close": function(){
					$(this).dialog("close");	
				}
			}
		});
		$("#ui-main").dialog({
			bgiframe: true,
			height: 550,
			width: 1000,
			modal: true,
			autoOpen: true,
			hide: 'fold'
		});
		$("#tab_options").tabs({
			fx:{
				opacity: 'toggle'
			}
		});
		$("#tab_scenarios").find(".ui-tabs-nav").sortable({
			axis: 'x'
		});
		$("#tab_scenarios").tabs({
			fx:{
				opacity: 'toggle'
			}
		});
		$("#var_list").accordion({
			autoHeight: false
		 });
		<?php
			for($x=1;$x<=3;$x++){
				?>
				$("#s<?php print($x); ?>").accordion({
					autoHeight: false,
					collapsible: true,
					active: false
				 });
				<?php
			}
		?>
		$("#pbar").progressbar({
			value: 0
		});
		$("#btn_run, #btn_scenarios, #btn_options, #btn_showmenu, #btn_years, #btn_addyear, #btn_saveyear").hover(
			function() { $(this).addClass('ui-state-hover'); }, 
			function() { $(this).removeClass('ui-state-hover'); }
		);
		// Sliders
		$.extend($.ui.slider.defaults, {
			range: "min",
			animate: true,
			orientation: "vertical"
		});
		<?php
			$tmp = array('M','F');
			for($x=1;$x<=3;$x++){
				foreach($tmp as $key=>$value){
					?>
					$("#dist_marriage<?php print($value); ?>_<?php print($x); ?> > span").each(function(i){
						var value = parseInt($(this).text());
						$(this).empty();
						$(this).slider({
							value: value,
							min: 0,
							max: 200,
							slide: function(event,ui){
								q("dist_marriage<?php print($value); ?>_value_<?php print($x); ?>_"+i).innerHTML = ui.value;
							}
						});
					});
					$("#dist_lf<?php print($value); ?>_<?php print($x); ?> > span").each(function(i){
						var value = parseInt($(this).text());
						$(this).empty();
						$(this).slider({
							value: value,
							min: 0,
							max: 100,
							slide: function(event,ui){
								q("dist_lf<?php print($value); ?>_value_<?php print($x); ?>_"+i).innerHTML = ui.value;
							}
						});
					});
					<?php
				}
			}
		?>
	});

	// Run a simulation
	function runSim(){
		$("#pbar").progressbar("value",0);
		q("pvalue").innerHTML = "0% complete";
		$("#status").dialog("open");
		//setTimeout("",1000);
		src = "engine.php?";
		src += "endtime=" + qv("opt_endtime");
		src += "&graphtype=" + qv("opt_graphtype");
		src += "&scenarios=" + qv("opt_no_scenarios");
		src += "&yaxis=" + qv("opt_yaxis");
		
		if(q("opt_var_totalpop").checked) src += "&opt_var_totalpop=1";
		// No. of residents by age group
		if(q("opt_var_pop_b15").checked) src += "&opt_var_pop_b15=1";
		if(q("opt_var_pop_15t39").checked) src += "&opt_var_pop_15t39=1";
		if(q("opt_var_pop_40t64").checked) src += "&opt_var_pop_40t64=1";
		if(q("opt_var_pop_a64").checked) src += "&opt_var_pop_a64=1";
		if(q("opt_var_pop_a15").checked) src += "&opt_var_pop_a15=1";
		// Population composition (%) by age group
		if(q("opt_var_popp_b15").checked) src += "&opt_var_popp_b15=1";
		if(q("opt_var_popp_15t39").checked) src += "&opt_var_popp_15t39=1";
		if(q("opt_var_popp_40t64").checked) src += "&opt_var_popp_40t64=1";
		if(q("opt_var_popp_a64").checked) src += "&opt_var_popp_a64=1";
		if(q("opt_var_popp_a15").checked) src += "&opt_var_popp_a15=1";
		// Labour Demographics
		if(q("opt_var_labour_b15").checked) src += "&opt_var_labour_b15=1";
		if(q("opt_var_labour_15t39").checked) src += "&opt_var_labour_15t39=1";
		if(q("opt_var_labour_40t64").checked) src += "&opt_var_labour_40t64=1";
		if(q("opt_var_labour_a64").checked) src += "&opt_var_labour_a64=1";
		if(q("opt_var_births").checked) src += "&opt_var_births=1";
		if(q("opt_var_deaths").checked) src += "&opt_var_deaths=1";
		if(q("opt_var_mrate").checked) src += "&opt_var_mrate=1";
		if(q("opt_var_medianage").checked) src += "&opt_var_medianage=1";
		if(q("opt_var_median_worker_age").checked) src += "&opt_var_median_worker_age=1";
		if(q("opt_var_immigrants").checked) src += "&opt_var_immigrants=1";
		
		// EDUCATION
		if(q("opt_var_educ_polyuni64").checked) src += "&opt_var_educ_polyuni64=1";
		
		// Different scenarios
		// Iterate through each of the three current scenarios
		for(x=1;x<=3;x++){
			// Account for different timings
			// Sort the years within each scenario
			zz = new Array;
			cnt = 0;
			yearlist = "";
			for(key in s[x]){
				zz[cnt] = key;
				if(cnt>0) yearlist += "|";
				yearlist += key;
				cnt++;
			}
			src += "&yearlist_"+x+"=" + yearlist;
			zz.sort();
			for(z=0;z<cnt;z++){
				// Get all the elements of s	
				// Different times within each scenario
				src += "&life_"+x+"_"+zz[z]+"=" + s[x][zz[z]].life;
				src += "&opt_immigrants_what_"+x+"_"+zz[z]+"=" + s[x][zz[z]].immigrants_what;
				src += "&opt_immigrants_"+x+"_"+zz[z]+"=" + s[x][zz[z]].immigrants;
				src += "&opt_immigrants_min_"+x+"_"+zz[z]+"=" + s[x][zz[z]].immigrants_min;
				src += "&opt_immigrants_max_"+x+"_"+zz[z]+"=" + s[x][zz[z]].immigrants_max;
				src += "&opt_unE_"+x+"_"+zz[z]+"=" + s[x][zz[z]].unE;
				<?php
					$tmp = array('M','F');
					foreach($tmp as $key=>$value){
						?>
						// Marriage
						marriage<?php print($value); ?> = "";
						for(i=0;i<8;i++){
							if(i>0) marriage<?php print($value); ?> += ",";
							marriage<?php print($value); ?> += s[x][zz[z]].marriage<?php print($value); ?>[i];
						}
						src += "&opt_marriage<?php print($value); ?>_"+x+"_"+zz[z]+"=" + marriage<?php print($value); ?>;
						// Labor force participation rate
						lf<?php print($value); ?> = "";
						for(i=0;i<20;i++){
							if(i>0) lf<?php print($value); ?> += ",";
							lf<?php print($value); ?> += s[x][zz[z]].lf<?php print($value); ?>[i];
						}
						src += "&opt_lf<?php print($value); ?>_"+x+"_"+zz[z]+"=" + lf<?php print($value); ?>;
						<?php
					}
				?>
			}
			
		}
		
		if(q("opt_var_econ_active").checked) src += "&opt_econ_active=" + qv("opt_var_econ_active");
		if(q("opt_var_lfpr").checked) src += "&opt_lfpr=" + qv("opt_var_lfpr");
		
		// LFPR by age group
		if(q("opt_var_lfpr_b15").checked) src += "&opt_var_lfpr_b15=1";
		if(q("opt_var_lfpr_15t39").checked) src += "&opt_var_lfpr_15t39=1";
		if(q("opt_var_lfpr_40t64").checked) src += "&opt_var_lfpr_40t64=1";
		if(q("opt_var_lfpr_a64").checked) src += "&opt_var_lfpr_a64=1";
		
		if(q("opt_var_employed").checked) src += "&opt_employed=" + qv("opt_var_employed");
		if(q("opt_var_gender_ratio").checked) src += "&opt_gender_ratio=" + qv("opt_var_gender_ratio");
		
		q("thebox").src = src;
	}
	
	// SAVE DATA FOR VARIOUS SCENARIOS
	s = new Array();	// The index of s is the scenario index, i.e. s[1] = {a set of scenarios for the first scenario}
	_scenario = 1;		// Active scenario; this changes everytime the user changes the tab
	_year = 2009;		// Active year... both are global variables
	// Add default for 2009, present day
	for(x=1;x<=3;x++){
		s[x] = new Array();
		s[x][2009] = new Object;
	}
	function sSaveNow(){
		for(x=1;x<=3;x++){
			s[x][2009].life = qv("opt_life_"+x);
			s[x][2009].immigrants_what = qv("opt_immigrants_what_"+x);
			s[x][2009].immigrants = qv("opt_immigrants_"+x);
			s[x][2009].immigrants_min = qv("opt_immigrants_min_"+x);
			s[x][2009].immigrants_max = qv("opt_immigrants_max_"+x);
			s[x][2009].unE = qv("opt_unE_"+x);
			
			// Construct the marriage rate from the sliders
			// Marriage rate for males
			<?php
				$tmp = array('M','F');
				foreach($tmp as $key=>$value){
					?>
					// Marriage
					s[x][2009].marriage<?php print($value); ?> = new Array();
					for(i=0;i<8;i++){
						s[x][2009].marriage<?php print($value); ?>[i] = q("dist_marriage<?php print($value); ?>_value_"+x+"_"+i).innerHTML;
					}
					// Labor force participation rate
					s[x][2009].lf<?php print($value); ?> = new Array();
					for(i=0;i<20;i++){
						s[x][2009].lf<?php print($value); ?>[i] = q("dist_lf<?php print($value); ?>_value_"+x+"_"+i).innerHTML;
					}
					<?php
				}
			?>
		}	
	}
	function sSaveYear(){
		//showMsg("Saving for _scenario #"+_scenario+" Year "+_year+".");
		before = "scenario #1 year #2009 life is "+s[1][2009].life+" and _year is "+_year;
		before += "<br>";
		s[_scenario][_year].life = qv("opt_life_"+_scenario);
		before += "scenario #1 year #2009 life is "+s[1][2009].life+" and _year is "+_year;
		//showMsg(before);
		s[_scenario][_year].immigrants_what = qv("opt_immigrants_what_"+_scenario);
		s[_scenario][_year].immigrants = qv("opt_immigrants_"+_scenario);
		s[_scenario][_year].immigrants_min = qv("opt_immigrants_min_"+_scenario);
		s[_scenario][_year].immigrants_max = qv("opt_immigrants_max_"+_scenario);
		s[_scenario][_year].unE = qv("opt_unE_"+_scenario);
		// Construct the marriage rate from the sliders
		// Marriage rate for males
		<?php
			$tmp = array('M','F');
			foreach($tmp as $key=>$value){
				?>
				// Marriage
				//s[_scenario][_year].marriage<?php print($value); ?> = new Array();
				for(i=0;i<8;i++){
					s[_scenario][_year].marriage<?php print($value); ?>[i] = q("dist_marriage<?php print($value); ?>_value_"+_scenario+"_"+i).innerHTML;
				}
				// Labor force participation rate
				//s[_scenario][_year].lf<?php print($value); ?> = new Array();
				for(i=0;i<20;i++){
					s[_scenario][_year].lf<?php print($value); ?>[i] = q("dist_lf<?php print($value); ?>_value_"+_scenario+"_"+i).innerHTML;
				}
				<?php
			}
		?>
		//window.alert(s[_scenario][_year].marriageM[5]);
		//showMsg("Settings for _scenario #"+_scenario+" Year "+_year+" have been successfully saved. For e.g. life expectancy is "+s[_scenario][_year].life);
		return true;
	}
	function sLoadYear(year){
			
	}
	function sAddYear(){
		new_year = qv("newyear_"+_scenario);
		// check if the year already exists
		if(s[_scenario][new_year]!=null){
			showError("Sorry! A scenario for the year "+new_year+" already exists for #"+_scenario+".");
			return false;
		}
		// Check if the year is in a valid range (>2009)
		parseInt(new_year);
		if(new_year<=2009){
			showError("Please specify a year that is after 2009.");
			return false;
		}
		// Add the new _scenario
		// By default, copy values from the year 2009 for this _scenario
		s[_scenario][new_year] = new Object;
		s[_scenario][new_year].life = qv("opt_life_"+_scenario);
		s[_scenario][new_year].immigrants_what = qv("opt_immigrants_what_"+_scenario);
		s[_scenario][new_year].immigrants = qv("opt_immigrants_"+_scenario);
		s[_scenario][new_year].immigrants_min = qv("opt_immigrants_min_"+_scenario);
		s[_scenario][new_year].immigrants_max = qv("opt_immigrants_max_"+_scenario);
		s[_scenario][new_year].unE = qv("opt_unE_"+_scenario);
		// Construct the marriage rate from the sliders
		// Marriage rate for males
		<?php
			$tmp = array('M','F');
			foreach($tmp as $key=>$value){
				?>
				// Marriage
				s[_scenario][new_year].marriage<?php print($value); ?> = new Array();
				for(i=0;i<8;i++){
					s[_scenario][new_year].marriage<?php print($value); ?>[i] = q("dist_marriage<?php print($value); ?>_value_"+_scenario+"_"+i).innerHTML;
				}
				// Labor force participation rate
				s[_scenario][new_year].lf<?php print($value); ?> = new Array();
				for(i=0;i<20;i++){
					s[_scenario][new_year].lf<?php print($value); ?>[i] = q("dist_lf<?php print($value); ?>_value_"+_scenario+"_"+i).innerHTML;
				}
				<?php
			}
		?>
		// Generate the new button
		str = "<div id='btn_years_"+_scenario+"_"+new_year+"' class='ui-state-default ui-corner-all ui-btn' style='width:55px;float:left;margin:3px;'>";
		str += "<span onclick='sActivateYear("+_scenario+","+new_year+");' style='float:left;'>"+new_year+"</span>";
		str += "<span style='float:right;margin-left:5px;' class='ui-icon ui-icon-closethick' onclick='sRemoveYear("+new_year+");'></span>";
		str += "</div>";
		$("#years_holder_"+_scenario).append(str);
		// Change the active year
		sActivateYear(_scenario,new_year);
	}
	function sRemoveYear(year){
		s[_scenario][year] = null;
		$("#btn_years_"+_scenario+"_"+year).remove();
		sActivateYear(_scenario,2009);
	}
	function sActivateYear(scenario,year){
		// De-highlight current active year
		if(s[_scenario][_year]!=null){
			$("#btn_years_"+_scenario+"_"+_year).removeClass("ui-state-error");
			$("#btn_years_"+_scenario+"_"+_year).addClass("ui-state-default");
		}
		// Save the current year
		sSaveYear();
		// Activate the new year
		_year = year;
		_scenario = scenario;
		// Highlight the active year
		$("#btn_years_"+_scenario+"_"+_year).removeClass("ui-state-default");
		$("#btn_years_"+_scenario+"_"+_year).addClass("ui-state-error");
		// Change the save year button
		q("label_activeyear_"+_scenario).innerHTML = "Save Active Year ("+_year+")";
		// Loads values for the new active year
		q("opt_life_"+_scenario).value = s[_scenario][_year].life;
		//showMsg("For e.g. life expectancy is "+s[scenario][year].life+" after and "+q("opt_life_"+scenario).value+" after.");
		//window.alert(s[scenario][year].life);
		//window.alert(qv("opt_life_"+scenario));
		//s[scenario][year].immigrants_what = qv("opt_immigrants_what_"+scenario);
		q("opt_immigrants_"+_scenario).value = s[_scenario][_year].immigrants;
		q("opt_immigrants_min_"+_scenario).value = s[_scenario][_year].immigrants_min;
		q("opt_immigrants_max_"+_scenario).value = s[_scenario][_year].immigrants_max;
		q("opt_unE_"+_scenario).value = s[_scenario][_year].unE;
		<?php
			$tmp = array('M','F');
			foreach($tmp as $key=>$value){
				?>
				// Marriage
				for(i=0;i<8;i++){
					// Update the value
					q("dist_marriage<?php print($value); ?>_value_"+_scenario+"_"+i).innerHTML = s[_scenario][_year].marriage<?php print($value); ?>[i];
				}
				// Update the distribution curve sliders
				$("#dist_marriage<?php print($value); ?>_"+_scenario+" > span").each(function(i){
					$(this).slider("value",s[_scenario][_year].marriage<?php print($value); ?>[i]);				 
				});
				// Labor force participation rate
				for(i=0;i<20;i++){
					// Update the value
					q("dist_lf<?php print($value); ?>_value_"+_scenario+"_"+i).innerHTML = s[_scenario][_year].lf<?php print($value); ?>[i];
				}
				// Update the distribution curve sliders
				$("#dist_lf<?php print($value); ?>_"+_scenario+" > span").each(function(i){
					$(this).slider("value",s[_scenario][_year].lf<?php print($value); ?>[i]);				 
				});
				<?php
			}
		?>
		//showMsg("Settings for Scenario #"+scenario+" Year "+year+" have <b>LOADED</b>. For e.g. life expectancy is "+s[scenario][year].life);
	}
	
	// UI functions
	function showOptions(){
		var optionz = {};
		$("#frame_scenarios").hide("blind",optionz,500);
		$("#frame_options").show("blind",optionz,500);
	}
	function showScenarios(){
		var optionz = {};		
		$("#frame_options").hide("blind",optionz,500);
		$("#frame_scenarios").show("blind",optionz,500);
	}
	function showError(msg){
		q("error_msg").innerHTML = msg;
		$("#error").dialog("open");
	}
	function showMsg(msg){
		q("msg").innerHTML = msg;
		$("#msgbox").dialog("open");
	}

	// Menu button UI functions
	function showMenu(){
		var optionz = {};
		$("#ui-panel").effect("blind",optionz,500,callback);
	}
	function callback(){
		$("#ui-main").dialog("open");
	}

</script>

<body>

	<!--div id='msg' style='position:absolute;top:0px;left:0px;border:1px solid black;background-color:white;padding:5px;'></div-->
	<iframe id='thebox' style='position:absolute;top:0;left:0;width:100%;height:100%;border:0px;' frameborder='0'></iframe>

	<!-- UI: MENU BUTTON -->
	<div id='ui-panel' class='ui-widget-content ui-corner-all' style='display:none;position:absolute;top:0px;left:0px;width:100px;height:30px;'>
		<div id='btn_showmenu' class='ui-state-default ui-corner-all ui-btn' style='width:90px;' onclick='showMenu();'>Menu</div>
	</div>
	
	<!-- DIALOG: Simulation Progressbar -->
	<div id="status" title="ADelphi v1.0">
		<p>Please wait, running simulation...</p>
		<div id="pbar"></div>
		<p>
			<div id='pvalue'></div>
		</p>
	</div>

	<!-- DIALOG: Simulation Options -->
	<div id='ui-main' title='ADelphi v1.0'>
		<div style='width:150px;height:490px;float:left;padding:5px;'>
			<h1>Menu</h1>
			<p>
				<div id='btn_run' class='ui-state-default ui-corner-all ui-btn' onclick='runSim();'>Run Simulation</div>
			</p>
            <p>
				<div id='btn_options' class='ui-state-default ui-corner-all ui-btn' onclick='showOptions();'>Edit Options</div>
            </p>
            <p>
				<div id='btn_scenarios' class='ui-state-default ui-corner-all ui-btn' onclick='showScenarios();'>Setup Scenarios</div>
            </p>
            <hr style='height:1px;border:1px solid silver;'>
            <p>
				<div id='btn_scenarios' class='ui-state-default ui-corner-all ui-btn' onclick='$("#about").dialog("open");'>About</div>
            </p>
		</div>
		<div id='frame_options' style='float:left;width:780px;height:490px;padding:5px;'>
			<h1>Options</h1>
			<div id='tab_options' style='height:400px;'>
				<ul>
					<li><a href="#tab_options-1">General</a></li>
					<li><a href="#tab_options-2">Variables</a></li>
					<li><a href="#tab_options-3">Data Sources</a></li>
				</ul>
				<div id='tab_options-1'>
					<p>
						<table class='ui-widget'>
							<tr>
								<th>Graph Type</th>
								<td>
									<select id='opt_graphtype' class='ui-corner-all'>
										<option value='Line'>Line</option>
										<option value='StackedBar'>Stacked Bar</option>
                                        <option value='Area'>Area</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>Time Period</th>
								<td>
									From 2009 to
									<input type='text' id='opt_endtime' value='2100' style='width:50px;'>
								</td>
							</tr>
							<tr>
								<th>No. of Scenarios</th>
								<td>
									<select id='opt_no_scenarios' class='ui-corner-all'>
										<?php
											for($x=1;$x<=3;$x++){
												print('<option value="'.$x.'">'.$x.'</option>');	
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<th>Vertical Axis</th>
								<td>
									<select id='opt_yaxis' class='ui-corner-all'>
										<option value='start_zero'>Starts from 0</option>
                                        <option value='custom'>Dynamically determined</option>
									</select>
								</td>
							</tr>
						</table>
					</p>
				</div>
				<div id='tab_options-2'>
					<p>
                        <div id='var_list' style='width:600px;'>
                            <h3><a href='#'>Population Composition</a></h3>
                            <div>
                                <input type='checkbox' id='opt_var_totalpop' value='1'>
                                Total Population<br><br>
                                <b>No. of residents by age group</b><br>
                                <input type='checkbox' id='opt_var_pop_b15' value='1'>
                                &lt; 15 yo
                                <input type='checkbox' id='opt_var_pop_15t39' value='1'>
                                15 to 39 yo
                                <input type='checkbox' id='opt_var_pop_40t64' value='1'>
                                40 to 64 yo
                                <input type='checkbox' id='opt_var_pop_a64' value='1'>
                                &gt; 64 yo
                                <input type='checkbox' id='opt_var_pop_a15' value='1'>
                                &gt; 15 yo<br><br>
                                <b>Population composition (%) by age group</b><br>
                                <input type='checkbox' id='opt_var_popp_b15' value='1'>
                                &lt; 15 yo
                                <input type='checkbox' id='opt_var_popp_15t39' value='1'>
                                15 to 39 yo
                                <input type='checkbox' id='opt_var_popp_40t64' value='1'>
                                40 to 64 yo
                                <input type='checkbox' id='opt_var_popp_a64' value='1'>
                                &gt; 64 yo
                                <input type='checkbox' id='opt_var_popp_a15' value='1'>
                                &gt; 15 yo
                            </div>
                            <h3><a href='#'>Labour Force</a></h3>
                            <div>
                                <input type='checkbox' id='opt_var_econ_active' value='1'>
                                Labour Force<br>
                                <input type='checkbox' id='opt_var_lfpr' value='1'>
                                General Labour Force Participation Rate (LFPR)<br>
                                <b>LFPR by age group:</b><br>
                                <input type='checkbox' id='opt_var_lfpr_b15' value='1'>
                                &lt; 15 yo 
                                <input type='checkbox' id='opt_var_lfpr_15t39' value='1'>
                                15 to 39 yo
                                <input type='checkbox' id='opt_var_lfpr_40t64' value='1'>
                                40 to 64 yo
                                <input type='checkbox' id='opt_var_lfpr_a64' value='1'>
                                &gt; 64 yo<br><br>
                                <input type='checkbox' id='opt_var_employed' value='1'>
                                Employed<br>
                                <input type='checkbox' id='opt_var_gender_ratio' value='1'>
                                Gender Ratio (males to females)<br>
                                <input type='checkbox' id='opt_var_median_worker_age' value='1'>
                                Median Worker Age<br>
                                <b>No. of economically active residents by age group:</b><br>
                                <input type='checkbox' id='opt_var_labour_b15' value='1'>
                                &lt; 15 yo 
                                <input type='checkbox' id='opt_var_labour_15t39' value='1'>
                                15 to 39 yo
                                <input type='checkbox' id='opt_var_labour_40t64' value='1'>
                                40 to 64 yo
                                <input type='checkbox' id='opt_var_labour_a64' value='1'>
                                &gt; 64 yo
                            </div>
                            <h3><a href='#'>Family & Integration</a></h3>
                            <div>
                                <input type='checkbox' id='opt_var_births' value='1'>
                                Births<br>
                                <input type='checkbox' id='opt_var_deaths' value='1'>
                                Deaths<br>
                                <input type='checkbox' id='opt_var_mrate' value='1'>
                                Marriage Rate (% of pop)<br>
                                <input type='checkbox' id='opt_var_medianage' value='1'>
                                Median Age<br>
                                <input type='checkbox' id='opt_var_immigrants' value='1'>
                                New Immigrants
                            </div>
                            <h3><a href='#'>Education</a></h3>
                            <div>
                                <input type='checkbox' id='opt_var_educ_polyuni64' value='1'>
                                &gt; 64 yo with Poly/Diploma or Degrees<br>
                            </div>
                        </div>
					</p>
				</div>
				<div id='tab_options-3'>
					<p>
						sdf
					</p>
				</div>
			</div>
        </div>
		<div id='frame_scenarios' style='float:left;width:780px;height:490px;padding:5px;display:none;'>
			<h1>Scenarios</h1>
			<div id='tab_scenarios' style='height:400px;'>
				<ul>
                	<li><a href='#tab_scenarios-0'>Overview</a></li>
					<li><a href="#tab_scenarios-1" onclick='sActivateYear(1,2009);'>#1</a></li>
					<li><a href="#tab_scenarios-2" onclick='sActivateYear(2,2009);'>#2</a></li>
					<li><a href="#tab_scenarios-3" onclick='sActivateYear(3,2009);'>#3</a></li>
				</ul>
                <div id='tab_scenarios-0'>
                	<p>
                    	Welcome to ADelphi Scenarios.
                    </p>
                </div>
                <?php
					for($x=1;$x<=3;$x++){
						?>
                        <div id='tab_scenarios-<?php print($x); ?>'>
                            <p>
                                <table class='ui-widget'>
                                	<tr>
                                    	<th style='border-bottom:1px solid black;'>Policy Points</th>
                                        <td style='border-bottom:1px solid black;'>
                                        	<div id='years_holder_<?php print($x); ?>' style='float:left;'>
                                                <div id='btn_years_<?php print($x); ?>_2009' class='ui-state-default ui-corner-all ui-btn' style='width:40px;float:left;margin:3px;'>
                                                    <span onclick='sActivateYear(<?php print($x); ?>,2009);'>2009</span>
                                                </div>
                                            </div>
                                            <div style='float:left;'>
                                                <input type='text' id='newyear_<?php print($x); ?>' value='' maxlength='4' style='width:45px;float:left;margin:3px;'>
                                                <div id='btn_addyear' class='ui-state-default ui-corner-all ui-btn' style='width:18px;float:left;margin:3px;' onclick='sAddYear();'>
                                                    <span class='ui-icon ui-icon-plusthick'></span>
                                                </div>
                                            </div>
                                            <div style='clear:both;'>
												<div id='btn_saveyear' class='ui-state-default ui-corner-all ui-btn' style='width:175px;float:left;margin:3px;' onclick='sSaveYear();'>
                                                    <span class='ui-icon ui-icon-check' style='float:left;'></span>
                                                    <span style='float:right;' id='label_activeyear_<?php print($x); ?>'>Save Active Year (2009)</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Life Expectancy</th>
                                        <td>
                                            Increases by 
                                            <input type='text' id='opt_life_<?php print($x); ?>' value='0.5' style='width:50px;'> % annually
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Immigration</th>
                                        <td>
                                            Inflow of
                                            <input type='text' id='opt_immigrants_<?php print($x); ?>' value='39300' style='width:75px;'>
                                            <select id='opt_immigrants_what_<?php print($x); ?>' class='ui-corner-all'>
                                                <option value='percent'>percent</option>
                                                <option value='people' selected>people</option>
                                            </select>
                                            annually
                                        </td>
                                    </tr>
                                    <tr>
                                    	<th></th>
                                        <td>
                                        	Aged between
                                            <input type='text' id='opt_immigrants_min_<?php print($x); ?>' value='0' style='width:40px;'>
                                            and 
                                            <input type='text' id='opt_immigrants_max_<?php print($x); ?>' value='50' style='width:45px;'>
                                            (uniformly)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Options</th>
                                        <td>
											<div id='s<?php print($x); ?>'>
                                            	<h3><a href='#'>Marriage Rate (Males)</a></h3>
                                                <div>
													<?php
                                                        print('<div id="dist_marriageM_'.$x.'" class="slider-main">');
                                                        $dist_marriageM = array(0,0,0,0.9,14.4,83.0,111.8,84.9,58.5);
                                                        for($i=0;$i<count($dist_marriageM);$i++){
                                                            print('<span>'.$dist_marriageM[$i].'</span>');
                                                        }
                                                        print('</div>');
                                                        print('<div id="dist_marriageM_values_'.$x.'" class="slider-value">');
                                                        for($i=0;$i<count($dist_marriageM);$i++){
                                                            print('<span id="dist_marriageM_value_'.$x.'_'.$i.'">'.$dist_marriageM[$i].'</span>');
                                                        }
                                                        print('</div>');
                                                    ?>
                                            	</div>
                                                <h3><a href='#'>Marriage Rate (Females)</a></h3>
                                                <div>
													<?php
                                                        print('<div id="dist_marriageF_'.$x.'" class="slider-main">');
                                                        $dist_marriageF = array(0,0,0,3.1,30.9,114.5,79.8,38.8,18.7);
                                                        for($i=0;$i<count($dist_marriageF);$i++){
                                                            print('<span>'.$dist_marriageF[$i].'</span>');
                                                        }
                                                        print('</div>');
                                                        print('<div id="dist_marriageF_values_'.$x.'" class="slider-value">');
                                                        for($i=0;$i<count($dist_marriageF);$i++){
                                                            print('<span id="dist_marriageF_value_'.$x.'_'.$i.'">'.$dist_marriageF[$i].'</span>');
                                                        }
                                                        print('</div>');
                                                    ?>
                                                </div>
                                                <h3><a href='#'>Labor Force Participation Rate (Males)</a></h3>
                                                <div>
													<?php
                                                        print('<div id="dist_lfM_'.$x.'" class="slider-main2">');
                                                        $dist_lfM = array(
                                                                               0,		// 0  to  4
                                                                               0,		// 5  to  9
                                                                               0,		// 10 to 14
                                                                               13.9,	// 15 to 19
                                                                               66.1,	// 20 to 24
                                                                               93.3,	// 25 to 29
                                                                               98.1,	// 30 to 34
                                                                               97.7,	// 35 to 39
                                                                               97.5,	// 40 to 44
                                                                               96.6,	// 45 to 49
                                                                               93.0,	// 50 to 54
                                                                               84.9,	// 55 to 59
                                                                               64.7,	// 60 to 64
                                                                               40.1,	// 65 to 69
                                                                               23.4,	// 70 to 74
                                                                               9.4,		// 75 to 79
                                                                               9.4,		// 80 to 84 [ESTIMATED]
                                                                               9.4,		// 85 to 89 [ESTIMATED]
                                                                               9.4,		// 90 to 94 [ESTIMATED]
                                                                               9.4		// 95 to 99 [ESTIMATED]
                                                                               );
                                                        for($i=0;$i<20;$i++){
                                                            print('<span>'.$dist_lfM[$i].'</span>');
                                                        }
                                                        print('</div>');
                                                        print('<div id="dist_lfM_values_'.$x.'" class="slider-value2">');
                                                        for($i=0;$i<20;$i++){
                                                            if($i%2==0) $c = 'black';
                                                            else $c = 'red';
                                                            print('<span id="dist_lfM_value_'.$x.'_'.$i.'" style="color:'.$c.';">'.$dist_lfM[$i].'</span>');
                                                        }
                                                        print('</div>');
                                                    ?>
                                                </div>
                                                <h3><a href='#'>Labor Force Participation Rate (Females)</a></h3>
                                                <div>
													<?php
                                                        print('<div id="dist_lfF_'.$x.'" class="slider-main2">');
                                                        $dist_lfF = array(
                                                                               0,		// 0  to  4
                                                                               0,		// 5  to  9
                                                                               0,		// 10 to 14
                                                                               11.6,	// 15 to 19
                                                                               67.0,	// 20 to 24
                                                                               84.5,	// 25 to 29
                                                                               80.5,	// 30 to 34
                                                                               74.4,	// 35 to 39
                                                                               69.9,	// 40 to 44
                                                                               68.7,	// 45 to 49
                                                                               62.0,	// 50 to 54
                                                                               48.0,	// 55 to 59
                                                                               33.1,	// 60 to 64
                                                                               16.6,	// 65 to 69
                                                                               7.9,		// 70 to 74
                                                                               2.7,		// 75 to 79
                                                                               2,		// 80 to 84 [ESTIMATED]
                                                                               2,		// 85 to 89 [ESTIMATED]
                                                                               2,		// 90 to 94 [ESTIMATED]
                                                                               2		// 95 to 99 [ESTIMATED]
                                                                               );
                                                        for($i=0;$i<20;$i++){
                                                            print('<span>'.$dist_lfF[$i].'</span>');
                                                        }
                                                        print('</div>');
                                                        print('<div id="dist_lfF_values_'.$x.'" class="slider-value2">');
                                                        for($i=0;$i<20;$i++){
                                                            if($i%2==0) $c = 'black';
                                                            else $c = 'red';
                                                            print('<span id="dist_lfF_value_'.$x.'_'.$i.'" style="color:'.$c.';">'.$dist_lfF[$i].'</span>');
                                                        }
                                                        print('</div>');
                                                    ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Unemployment Rate</th>
                                        <td>
                                            Seasonally adjusted, 
                                            <input type='text' id='opt_unE_<?php print($x); ?>' value='3.5' style='width:50px;'> % (residents only)
                                        </td>
                                    </tr>
                                </table>
                            </p>
                        </div>
                        <?php
					}
				?>
			</div>
		</div>
	</div>

	<!-- DIALOG: Error Message -->
	<div id="error" title="Oops!">
		<div class='ui-state-error ui-corner-all'>
        	<p style='padding-left:15px;padding-right:15px;'>
	        	<span class='ui-icon ui-icon-alert' style='float:left;'></span>
                Error
            </p>
        </div>
		<div id="error_msg" style='padding:15px;'></div>
	</div>
    
	<!-- DIALOG: Message -->
	<div id="msgbox" title="ADelphi">
		<div class='ui-state-default ui-corner-all'>
        	<p style='padding-left:15px;padding-right:15px;'>
	        	<span class='ui-icon ui-icon-alert' style='float:left;'></span>
                Notice
            </p>
        </div>
		<div id="msg" style='padding:15px;'></div>
	</div>
    
	<!-- DIALOG: About -->
	<div id="about" title="About ADelphi">
    	<h4>Credits</h4>
		<p>
        	Developed by: Mak Yiing Chau, Intern/SPRD (ycmak@stanford.edu)<br />
            Supervised by: Devadas Krishnadas, AD/SPRD<br />
            Yeong Gah Hou, D/SPRD
        </p>
        <h4>Recommended User System Requirements</h4>
        <p>
        	Google Chrome<br />
            1024x768 screen resolution
        </p>
        <h4>Version</h4>
        <p>
        	This version is dated Monday, 10 August 2009.
        </p>
	</div>

</body>

<script language='javascript'>
	sSaveNow();
</script>

</html>