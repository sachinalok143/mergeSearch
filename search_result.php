<?php
use google\appengine\api\cloud_storage\CloudStorageTools;
$options = ['gs_bucket_name' => BUCKET_NAME];
//$upload_url = CloudStorageTools::createUploadUrl(site_url('Communication/saveQuestion'), $options);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Search</title>
<meta content="text/html" charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- For polyfill support across non-compatible browsers-->
<script src="<?php echo base_url().'assets/polymer_dependency/webcomponents-lite.min.js'?>"></script>

<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/assets/js/encode_req.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/cssreset-min.css'?>">

<link rel="shortcut icon" type="image/png" href="<?php echo base_url().'assets/images/icons/home-icon.png'?>"/>
<link href="<?php echo asset_url();?>css/jquery.tagit.css" rel="stylesheet" type="text/css">
<link href="<?php echo asset_url();?>css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<!--importing vulcanized polymer dependencies-->
<link rel="import" href="<?php echo base_url().'assets/polymer_dependency/polymer-imports-vulc.html'?>">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script type="text/javascript" src="<?php echo asset_url();?>js/clipboard.js"></script>
<script src="<?php echo asset_url();?>js/tag-it.js" type="text/javascript" charset="utf-8"></script>

<style is="custom-style" include="shared-css iron-flex iron-flex-alignment">
	html, body {
        max-width: 100%;
        overflow-x: hidden;
    }
	.college {
		/*width: inherit;*/
	}
	#container{
		width: 90%;
		margin: 10px auto;
		margin-top: 0px;
		margin-bottom: 5%;
		background-color: #fff;
	}

	#leftPanel{
		margin-left: 0px;
		/*padding-right: 6px;*/
		margin-top: 10px;
		 @apply(--shadow-elevation-2dp);
				 background-color: white;
				 overflow: auto;

	}

      #collapse3 {
        max-height: 250px;
      }
	.categoryOptions paper-checkbox {
		width: 100%;
	}

	#rightPanel{
		padding-left: 15px;
		padding-right: 12px;
		padding-top: 0px;
		display: inline-table;
		width: 78%;
	}

	paper-card{
		color: black;
	}

	#leftSort {
		/*visibility: hidden;
		display: none;*/
	}

	#profilesResults paper-card {
		display: block;
		margin-top: 5px;
		padding: 12px;
		padding-left: 15px;
		padding-right: 15px;
	}

	.college-heading {
		color: #009688;
		font-weight: 500;
		text-transform: capitalize;
		font-size: 14px;
		margin-bottom: 3px;
		/*width: 70%;*/

	}



	.college-loc {
		display: inline-block;
		color: #868686;
		/*padding-left: 10px;*/
		font-size: 12px;
	}

	.college-affil {
		display: inline-block;
		color: #868686;
		margin-left: 20px;
		font-size: 12px;
	}

     .left-up {
     	display: inline-block;
    }

     .right-up {
     	display: inline-block;
     	text-align: right;
     	color: #ffd400;
     	display: flex; 
     	flex-direction: column; 
     	justify-content: space-between;
     }
     .fnew {
     	font-size: 11px;
     }
     .newfnt {
     	margin-right: 7px;
     }

     .other-details {
     	margin-top: 15px;
     }

     .detail1 {
     	display: inline-block;
     	font-size: 13px;
     	color: #959595;
     	margin-right: 10px;
     }

     .detail2 {
     	display: inline-block;
     	/*font-weight: 600;*/
     	font-size: 13px;
     	color: #959595;
     	/*padding-left: 10px;*/
     }
	.mobile-bottom-bar div{
		position: relative;
		cursor: pointer;
	}
     .det1 {
     	display: inline-block;
     }

     .det1-res {
     	font-weight: 600;
     	display: inline-block;
     }

     .rating {
     	/*height: 400px;*/
     }

     .rating-point {
     	font-size: 30px;
     	font-weight: 600;
     	display: inline-block;
     }

     .rating-det {
     	height: 15px;
     	display: inline-block;
     }

     .rating-stars {
     	display: inline-block;
     }

     .rating-stars iron-icon {
     	height: 10px;
     	width: 10px;
     	padding-left: 1px;
     }


     .rating-label {
     	font-size: 10px;
     	color: #959595;
     	text-align: right;
     }

     .compare-button paper-button {
     	background-color: #009688;
     	color: white;
     	font-size: 12px;
     	border-top-right-radius: 0px;
     	border-top-left-radius: 0px;
     	border-bottom-left-radius: 0px;
     	border-bottom-right-radius: 0px;
     	margin-top: 2px;
     }

	#filterHeading{
		width: 90%;
		margin: 0 auto;
		line-height: 48px;
		font-weight: 500;
		font-size: 14px;
		letter-spacing: 0.018em;
		text-rendering: optimizeLegibility;
	}

	#collegeFilters{
		color : black;
		width: 90%;
		display: block;
		margin: 0 auto;
	}

	#closeFilter{
		display: none;
	}

	#mobileBottomBar{
		display: none;
	}

	.abst {
		--paper-tabs-selection-bar-color: #009688;
	}
	.custom2 {
		width: 220px;
	}
	.lbar {
		height: 100%; 
		overflow-x: hidden; 
		width: 20%;
	}
	.applyBar{
		display: none;
	}
				#filter-res {
				width: 89.5%;
				margin: 0 auto;
				/*line-height: 48px;*/
				/*font-weight: 500;*/
				font-size: 14px;
				letter-spacing: 0.018em;
				text-rendering: optimizeLegibility;
				display: inline-block;
				font-size: 13px;
				/*width: 90%;*/
				padding-left: 15px;
				padding-right: 15px;
			}

			#found {
				color: grey;
				text-align: left;
				width: 60%;
				display: inline-block;
				/*font-size: 10px;*/
			}

			#def {
				color: #278a80;
				text-align: right;
				/*width: 50%;*/
				display: inline-block;
				float: right;
			}
			.filterName {
				width: 100%;
				margin: 0 auto;
				/*line-height: 48px;*/
				/*font-weight: 500;*/
				font-size: 14px;
				letter-spacing: 0.018em;
				text-rendering: optimizeLegibility;
				margin-top: 5px;
				color: #278a80;
				background-color: #f9f9f9;
				padding-top: 10px;
				padding-bottom: 10px;
				font-size: 9px;
				display: inline-block;
				padding-left: 15px;
				padding-right: 15px;
				/*width: 90.5%;*/
				/*margin-right: 10px;*/
				cursor: pointer;
			}

			#name-detail {
				width: 60%;
				display: inline-block;
				text-align: left;
				/*padding-top: 5px;*/
				font-size: 13px;
				font-weight: 900;
			}

			#cross {
				float: right;
				/*display: inline-block;*/
				font-size: 5px;
			}

			#cross iron-icon {
				/*padding-top: 3px;*/
			}

			.searchBox {
				width: 89.5%;
				margin: 0 auto;
				margin-bottom: 10px;
				color: #f0f0f0;
				border-bottom-right-radius: 2px;
				border-bottom-left-radius: 2px;
				border-top-left-radius: 2px;
				border-top-right-radius: 2px;
				border: 0.5px #f2f2f2 solid;
			}

			.listOptions {
				width: 89.5%;
				margin: 0 auto;
				color: #f0f0f0;
				font-size: 13px;
				min-height: 50px;
				max-height: 150px;
				overflow: auto;
			}

			.listOptions .categoryOptions{
				display: block;
				margin-bottom: 3px;
				margin-top: 3px;
			}
	@media only screen and (max-width: 1300px){
		.custom2 {
			width: 200px;
		}
	}
	@media only screen and (max-width: 1200px){
		.custom2 {
			width: 180px;
		}
	}
	@media only screen and (max-width: 1024px){
		.custom2 {
			width: 155px;
		}
	}
	@media only screen and (max-width: 900px){
		.custom2 {
			width: 140px;
		}
	}
	@media only screen and (max-width: 800px){
		.custom2 {
			width: 125px;
		}
	}
	@media only screen and (max-width: 769px){
		#rightPanel{
			padding-right: 0px;
			padding-left: 0px;
			width: initial;
			width: 100%;
		}
		.lbar {
			height: 100%; 
			overflow-x: hidden; 
			width: 95%;
		}
		.custom2 {
			width: 100%;
		}
		.lftbar {
			display: none;
		}
		#container {
		    width: 95%;
		    margin: 0px;
		    margin-left: 2.5%;
		    margin-top: 0px;
		    margin-bottom: 5%;
		    background-color: #fff;
		}
		.rating-point:not([style-scope]):not(.style-scope) {
		    font-size: 22px;
		    font-weight: 600;
	    display: inline-block;
		}
		#leftPanel{
			display: none;
			padding: 0;
			position: fixed;
			z-index: 1;
			background-color: white;
			height: 81.4vh;
			overflow: auto;
			overflow-x: hidden;
			margin-top: 0px;
			margin-left: 0px;
		}

		.left-up {
			width: 100%;
		}

		.rating {
			display: inline-block;
			/*margin-right: 40px;*/
			float: left;
		}

		.compare-button {
			display: inline-block;
			float: right;
		}

		.right-up {
			padding-top: 10px;
			width: 100%;
		}

		.detail1 {
			float: left;
			font-size: 12px;
		}

		.detail2 {
			float: right;
			font-size: 12px;
			padding-right: 4px;
		}

		#profilesResults paper-card {
			padding-left: 15px;
		}

		.categoryOptions paper-checkbox {
			padding-top: 5px;
			padding-bottom: 5px;
		}

		.college-heading {
			width: 100%;
		}

		#collegeFilters{
			padding-top: 48px;
			padding-bottom: 60px;
		}
		#filterHeading{
			padding: 0 5%;
			background-color: var(--main-color);
			color: white;
			/*position: fixed;*/
			width: 100%;
			z-index: 1;
			display: table;
		}
		#tagsBar {
			/*visibility: hidden;
			display: none;*/
		}
		#filter-res {
			padding-top: 10px;
		}
		#closeFilter{
			display: inline-flex;
		}

		#mobileBottomBar{
			display: block;
		}

		.mobile-bottom-bar{
			display: flex;
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100vw;
			height: 55px;
			background-color: var(--main-color);
			color: white;
			z-index: 1;
			text-align: center;
		}

		.applyBar{
			display: block;
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			text-align: center;
			background-color: var(--main-color);
			color: white;
			padding: 15px 0;
			font-size: 16px;
			line-height: 16px;
		}
	}
	.fltr {
		display: flex; 
		justify-content: center; 
		align-items: center; 
		padding: 0px; 
		padding-right: 75px;
	}
	@media only screen and (max-width: 500px){
		.boxdr {
			flex-direction: column;
		}
		.right-up {
			flex-direction: row;
			text-align: left;
			font-size: 13px;
		}
		.fnew {
	     	font-size: 12px;
	     }
	     .compare-button paper-button {
	     	font-size: 10px;
	     }
	}
	@media only screen and (max-width: 350px){
		.fltr {
			padding-right: 45px;
		}
	}
	.collegeCard
	{
		width: 100%;
		min-height: 150px;
		margin-top: 1%;
		margin-bottom: 1%;
	}

	.college-header {
		@apply(--paper-font-headline);
	}

	.college-light {
		color: var(--paper-grey-600);
	}

	.college-location {
		float: right;
		font-size: 15px;
		vertical-align: middle;
	}

	.college-compare {
		color: var(--main-color);
	}

	.college-details span{
		margin: 5px 0;
	}


	.college-name{
		color: black;
	}

	iron-icon.star {
		--iron-icon-width: 16px;
		--iron-icon-height: 16px;
		color: var(--paper-amber-500);
	}

	iron-icon.star:last-of-type {
		color: var(--paper-grey-500);
	}

	.category
	{
		margin-top: 5%;
		width: 100%;
	}

	.categoryOption
	{
		display: block;
		margin-top: 5%;
	}

	paper-checkbox{
		--paper-checkbox-checked-color: var(--main-color);
	}

	#searchTabs{
		--paper-tabs:{
			background-color: #fff;
			color: #000;
			font-size: 16px;
			font-weight: 500;
		};
	}

	.questionCard{
		width: 100%;
		display: block;
		margin-top: 1%;
		margin-bottom: 1%;
	}

	.card-content{
		font-size: 14px;
		font-weight: bold;
	}

	.tagContainer{
		display: inline-block;
		background-color: #f44336;
		border-radius: 5px;
		padding: 5px;
		margin-top: 2%;
	}

	.tagLink{
		text-decoration: none;
		color: white;
		margin: 2%;
		margin-bottom: 0;
	}

	.question{
		color: rgba(0,0,0,0.87);
		font-size: 24px;
		font-weight: bold;
	}

	.question:hover{
		text-decoration: underline;
	}

	.question p{
		margin-bottom: 5px;
	}

	.author{
		color: rgba(0,0,0,0.54);
		margin-bottom: 15px;
	}

	.bottom-bar{
		border-top: 1px solid rgba(0,0,0,0.14);
		@apply(--layout-horizontal);
		/*background-color: #f44336;*/
	}

	.bottom-bar div{
		display: inline-block;
		@apply(--layout-flex);
		padding: 10px;
	}

	.bottom-bar div:nth-child(2){
		@apply(--layout-flex-2);
	}

	#loader-wrapper{
	    position: fixed;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    opacity: 1;
	    z-index: 1000;
		display: table;
		background-color: white;
		margin: 0;
		overflow-x: hidden;
		overflow-y: hidden;
		transition: 0.3s opacity;
	}
		.showTag{
				display: inline-block;
				font-size: 14px;
				color: black;
				opacity: 0.87;
				padding: 0px 10px;
				color: #07c;
				transition: background-color .3s cubic-bezier(0.215,0.61,0.355,1);
			}
#overlay {
    background-color: #009688;
    z-index: 999;
    position: fixed;
    left: 0;
	opacity:0.7;
    top: 0;
    width: 100%;
    height: 100%;
}
#sortby_button{position:relative;z-index:50;}
#sortby_button:before{
    position:absolute;
    content:'';
    top:-1.1vh;
    right:-10vw;
    left:-15vw;
    bottom:-4vh;
    z-index:40;
}
ul.tagit {
    border-style: solid;
    border-width: 1px;
    border-color: #C6C6C6;
    background: inherit;
}
ul.tagit {
    padding: 4px 5px;
    overflow-x: auto;
    margin-left: inherit;
    margin-right: inherit;
}
ul.tagit li.tagit-choice {
    -moz-border-radius: 6px;
    border-radius: 6px;
    -webkit-border-radius: 6px;
    border: 1px solid #fedcec;
    background: none;
    background-color: #fedcec;
    font-weight: normal;
}
ul.tagit li.tagit-choice .tagit-label:not(a) {
     color: #555;
    font-size: 0.8em;
}
</style>



<script type="text/javascript">
	document.addEventListener('WebComponentsReady', function () {
		var loaderWrapper = document.getElementById("loader-wrapper");
		loaderWrapper.style.opacity = "0";
		setTimeout(function(){
			loaderWrapper.style.display = "none";
		},300);
	});
	
	//initially hide overlay
	("#overlay").hide();
</script>

</head>

<body onload = "initialize()">
<div id="loader-wrapper">
</div>
<div id="overlay"><center><h1 class='center-block' style='color:white;padding-top:40vh;'>Loading...</h1></center></div>	
			
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<app-drawer-layout fullbleed responsive-width="1024px">

	<app-drawer>
		<?php include "common_components/app-drawer.php" ?>
	</app-drawer>

	<app-header-layout>
		<app-header fixed effects="waterfall">
			<?php include "common_components/app-header.php" ?>
		</app-header>
		<paper-toast id="paperToast" text="Link copied!"></paper-toast>
		<div id="container" class="flex-container-desktop" >
			<?php include "common_components/question-fab.php" ?>
			<template is="dom-bind">
				<div id="mobileBottomBar">
					<iron-pages selected={{selected}}>
							<div class="mobile-bottom-bar flex-container">
							<div class="flex"  style='margin-top: 0;padding: 0px; display: flex;justify-content: center;align-items: center;'>
								<paper-ripple class="dropdown-trigger"></paper-ripple>
								  <paper-menu-button noink class="dropdown-trigger" vertical-align="bottom" horizontal-align="left" label="Sort By" >
									<paper-button noink  style='color:white;' id='sortby_button' class="dropdown-trigger"><iron-icon icon="sort"></iron-icon>&nbsp;Sort By</paper-button> 
									
									  <paper-listbox  noink  id="nodename"   selected="{{sorting}}"  onclick='sorting()' class="dropdown-content"  style="padding: 0px;">
									  <paper-item style="font-size: 13px; color: #858585; padding-top: 2px; padding-bottom: 2px;">Highest Rating</paper-item>
  									    <paper-item style="font-size: 13px; color: #858585; padding-top: 2px; padding-bottom: 2px;">Most Viewed</paper-item>
    									<paper-item style="font-size: 13px; color: #858585;">Fees (Low to High)</paper-item>
  									    <paper-item style="font-size: 13px; color: #858585;">Fees (High to Low)</paper-item>
  									    <paper-item style="font-size: 13px; color: #858585;">Starting Salary(Low to High)</paper-item>
									  	<paper-item style="font-size: 13px; color: #858585;">Starting Salary(High to Low)</paper-item>
									  	<paper-item style="font-size: 13px; color: #858585;">Class Strength(Low to High)</paper-item>
									  	<paper-item style="font-size: 13px; color: #858585;">Class Strength(High to Low)</paper-item>
									  </paper-listbox>
										
									</paper-menu-button>
									<input is="iron-input" name="title" id='sorting' type="hidden" value$="[[sorting]]">
									</div>
									 
								
							
							<div class="flex fltr" onclick="openFilter()">
								<paper-ripple></paper-ripple>
								<iron-icon icon="filter-list"></iron-icon> FILTERS
							</div>
						</div>
						<div class="mobile-bottom-bar flex-container"></div>
						<div class="mobile-bottom-bar flex-container"></div>

						<div class="mobile-bottom-bar flex-container"></div>
					</iron-pages>
				</div>

<?php //print_r( $rank);?>			
							<input type="hidden" id="cid" value="<?php echo $this->session->cid; ?>">
				<div id="leftPanel" class="lbar">
					<div class="lftbar flex" style="margin: 10px; display: flex; justify-content: center;">
						<paper-dropdown-menu class="custom2" label="Sort by"   >
						  <paper-listbox  id="nodename" selected="{{sorting}}"  onclick='sorting()' class="dropdown-content custom2"  style="padding: 0px;">
						  <paper-item style="font-size: 13px; color: #858585; padding-top: 2px; padding-bottom: 2px;">Highest Rating</paper-item>
							    <paper-item style="font-size: 13px; color: #858585; padding-top: 2px; padding-bottom: 2px;">Most Viewed</paper-item>
							<paper-item style="font-size: 13px; color: #858585;">Fees (Low to High)</paper-item>
							<paper-item style="font-size: 13px; color: #858585;">Fees (High to Low)</paper-item>
							<paper-item style="font-size: 13px; color: #858585;">Starting Salary(Low to High)</paper-item>
							<paper-item style="font-size: 13px; color: #858585;">Starting Salary(High to Low)</paper-item>
							<paper-item style="font-size: 13px; color: #858585;">Class Strength(Low to High)</paper-item>
							<paper-item style="font-size: 13px; color: #858585;">Class Strength(High to Low)</paper-item>
						  </paper-listbox>
						</paper-dropdown-menu>	
						<input is="iron-input" name="title" id='sorting' type="hidden" value$="[[sorting]]">
					</div>
					<iron-pages selected="{{selected}}">
											<div class="left-panel-pages" style="margin-bottom: 10px;">
							<h2 id="filterHeading">
								<span style="display:table-cell"><iron-icon icon="filter-list" style="margin-right: 5px;"></iron-icon>Filters</span>
								<span style="display: table-cell; text-align: center;" onclick="closeFilter()">
									<paper-ripple></paper-ripple>
									<iron-icon icon="close" id="closeFilter"></iron-icon>
								</span>
							</h2>

							<span>
									<div id="filter-res" style="margin-bottom: 5px;">
									<div id="found"> Found <?php if( sizeof($rank)>=10){echo '10';} else{echo sizeof($rank);} ?> Colleges</div>
									<!-- <div id="def">Set Default </div> -->
								</div>
								</span>
								<span>

									<div class="filterName" aria-expanded$="[[isExpanded()]]" aria-controls="State-div" onclick="toggle('#State-div')">

									<div id = "name-detail"><?php echo strtoupper($Parents[0]['Node_Name']); ?></div><!-- <div id="cross"><iron-icon icon="clear"></iron-icon></div>  -->
								</div>
								</span>
								<iron-collapse id="State-div" opened="{{}}">
								<!-- <div class="searchBox">
									<iron-icon icon="search" style="color: #f2f2f2;"></iron-icon>
								</div> -->
									<!-- <div id="Name" class="filterName">
									<div id = "name-detail">NAME</div> <div id="cross"><iron-icon icon="clear"></iron-icon></div>
								</div> -->
								

								<div class="listOptions" id='stream'>
								<?php 
								
								     $i=0;
								     foreach($Streams as $val)
									 {
									 echo '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" ';for($j=0;$j<sizeof($mark_stream);$j++){if($Streams[$i]['Node_ID']==$mark_stream[$j]){ print "checked";} }    echo'    >'.str_replace("Yes_", "", $Streams[$i]["Node_Name"]).'</paper-checkbox> <input type="hidden" id="streamid" value="'.$Streams[$i]["Node_ID"].'" /></div>';
										 $i++;
									 }
								
								?>
							
								</div> 
								
								</iron-collapse>

						<div class="filterName" aria-expanded$="[[isExpanded(opened3)]]" aria-controls="majors-div" onclick="toggle('#majors-div')">
									<div id = "name-detail"><?php echo strtoupper($Parents[1]['Node_Name']); ?></div> <!-- <div id="cross"><iron-icon icon="clear"></iron-icon></div> -->
								</div>
								<iron-collapse id="majors-div" >
								<!-- <div class="searchBox">
									<iron-icon icon="search" style="color: #f2f2f2;"></iron-icon>
								</div> -->
									<!-- <div id="Name" class="filterName">
									<div id = "name-detail">NAME</div> <div id="cross"><iron-icon icon="clear"></iron-icon></div>
								</div> -->

								<div class="listOptions" id='degree'>
								<?php 
								     $i=0;
								     foreach($Degrees as $val)
									 {
										 echo '<div class="categoryOptions"><paper-checkbox  onchange="filter()" ';for($j=0;$j<sizeof($mark_degree);$j++){if(str_replace("Yes_", "",$Degrees[$i]['Node_Name'])==$mark_degree[$j]){ print "checked";}}  echo'>'.str_replace("Yes_", "", $Degrees[$i]["Node_Name"]).'</paper-checkbox></div>';
										 $i++;
									 }
								
								?>
							

								</div>
								</iron-collapse>

								<div class="filterName" aria-expanded$="[[isExpanded(opened3)]]" aria-controls="majors-div2" onclick="toggle('#majors-div2')">
									<div id = "name-detail"><?php echo strtoupper($Parents[2]['Node_Name']); ?></div> <!-- <div id="cross"><iron-icon icon="clear"></iron-icon></div> -->
								</div>
								<iron-collapse id="majors-div2" >
								<!-- <div class="searchBox">
									<iron-icon icon="search" style="color: #f2f2f2;"></iron-icon>
								</div> -->
									<!-- <div id="Name" class="filterName">
									<div id = "name-detail">NAME</div> <div id="cross"><iron-icon icon="clear"></iron-icon></div>
								</div> -->

								<div class="listOptions" id="major" >
								<?php 
								     $i=0;
								     foreach($Majors as $val)
									 {
										 echo '<div class="categoryOptions" ><paper-checkbox  onchange="filter()" ';for($j=0;$j<sizeof($mark_majors);$j++){if($Majors[$i]['Node_ID']==$mark_majors[$j]){ print "checked";} }  echo'>'.str_replace("Yes_", "", $Majors[$i]["Node_Name"]).'</paper-checkbox> <input type="hidden" id="majorid" value="'.$Majors[$i]["Node_ID"].'" /></div>';
										 $i++;
									 }
								
								?>
							
								</div>
								</iron-collapse>
								<div class="filterName" aria-expanded$="[[isExpanded(opened3)]]" aria-controls="majors-div3" onclick="toggle('#majors-div3')">
									<div id = "name-detail">COUNTRY</div> <!-- <div id="cross"><iron-icon icon="clear"></iron-icon></div> -->
								</div>
								<iron-collapse id="majors-div3" >
								<!-- <div class="searchBox">
									<iron-icon icon="search" style="color: #f2f2f2;"></iron-icon>
								</div> -->
									<!-- <div id="Name" class="filterName">
									<div id = "name-detail">NAME</div> <div id="cross"><iron-icon icon="clear"></iron-icon></div>
								</div> -->

								<div class="listOptions" id='country'>
								<?php
								 $i=0;
								     foreach($Countries as $val)
									 {
										 echo '<div class="categoryOptions"><paper-checkbox  onchange="filter()" ';for($j=0;$j<sizeof($mark_country);$j++){if(str_replace("Yes_", "",$Countries[$i]['Country_Name'])==$mark_country[$j]){ print "checked";}} echo'>'.str_replace("Yes_", "", $Countries[$i]["Country_Name"]).'</paper-checkbox></div>';
										 $i++;
									 }
								?>

						

								</div>
								</iron-collapse>

								<div class="filterName" aria-expanded$="[[isExpanded(opened2)]]" aria-controls="country-div" onclick="toggle('#country-div')">
									<div id = "name-detail">FEES</div> <!-- <div id="cross"><iron-icon icon="clear"></iron-icon></div> -->
								</div>
								<iron-collapse id="country-div">
								<!-- <div class="searchBox">
									<iron-icon icon="search" style="color: #f2f2f2;"></iron-icon>
								</div> -->
									<!-- <div id="Name" class="filterName">
									<div id = "name-detail">NAME</div> <div id="cross"><iron-icon icon="clear"></iron-icon></div>
								</div> -->

								<div class="listOptions">
								<!--
									<div class="categoryOptions"><paper-checkbox <?php if($country=="india") echo"checked"; ?>>India</paper-checkbox></div>
									<div class="categoryOptions"><paper-checkbox <?php if($country=="usa") echo"checked"; ?>>USA</paper-checkbox></div>
									<div class="categoryOptions"><paper-checkbox <?php if($country=="uk") echo"checked"; ?>>UK</paper-checkbox></div>
									<div class="categoryOptions"><paper-checkbox <?php if($country=="germany") echo"checked"; ?>>Germany</paper-checkbox></div>
									<div class="categoryOptions"><paper-checkbox <?php if($country=="alaska") echo"checked"; ?>>Alaska</paper-checkbox></div>
									<div class="categoryOptions"><paper-checkbox  <?php if($country=="russia") echo"checked"; ?>>Russia</paper-checkbox></div>
									-->

								</div>
								</iron-collapse>

							<div id="collegeFilters">
				
							</div>

							<div class="applyBar" onclick="closeFilter()">
									<paper-ripple></paper-ripple> Apply
							</div>
						</div>
						<div class="left-panel-pages"></div>
						<div class="left-panel-pages"></div>

						<div class="left-panel-pages"></div>
					</iron-pages>
				</div>
				
				<div class="flex-4" id="rightPanel">
					<paper-tabs id="searchTabs" class="abst" selected="{{selected}}">
						<!-- <paper-tab>All</paper-tab> -->
						<paper-tab onclick="viewProfiles()" style="color: #009688; font-size: 14px;">Profile</paper-tab>
						<paper-tab onclick="viewDiscussions()" style="color: #009688; font-size: 14px;">Discussion</paper-tab>
						
						<!-- <paper-tab>Contributors</paper-tab> -->
					</paper-tabs>

					<iron-pages selected="{{selected}}">
						<div class="tab-page">
							<div id="searchResults">
								<?php
								if($origin == 2)
								{
									?>
									<div id="allDiscussionsResults">
									<?php
									foreach($discussion['questions'] as $row)
									{
										$qid=$row->qid;
										echo '<paper-card class="questionCard" id="questionCardNo-'.$row->qid.'" data-upvotes="'.$row->upvotes.'" data-views="'.$row->views.'" data-date="'.$row->cr_dt.'"><div class="card-content">
																<div>
																	<a href="'.site_url('Communication/showQuestion?qid='.$row->qid).'"><span class="questionText">'.$row->question.'</span></a>';
																	if($this->session->is_logged_in!=0){
																	echo '<span class="questionEndIcons">
																		<span><paper-tooltip position="left">Follow</paper-tooltip><iron-icon id="follow-'.$row->qid.'" icon="social:plus-one" onclick="follow('.$row->qid.')" class="follow-'.$row->qid.' follow';
																			if($row->followed){
																				echo ' followed';
																			}
																			echo '"></iron-icon></span>
																		<span><paper-tooltip position="right" style="white-space:nowrap;">Flag as inappropriate</paper-tooltip><iron-icon icon="flag" class="flag flag-'.$row->qid;
																			if(!$row->question_upvoted){
																				echo ' flagged';
																			}
																			echo '" id="flag-'.$row->qid.'" onclick="updatedownvote('.$row->qid.')"></iron-icon></span>
																	</span>';
																}
																echo '</div>
															</div>';														if(!empty($row->answer)){
															echo '<div class="card-actions">
																<div>
																	<div class="topAnswerImg">
																		<img src="/assets/images/profilePics/profile1.jpg" style="width:40px;height:40px;">
																	</div>
																	<div class="topAnswerAbout" style="display: inline-block;">
																		<span class="topAnswerAuthor"><a href="'.site_url("User/profile/".encode_id($row->answer->CID)).'">'.$row->answer->Display_Name.'</a>,</span>
																		<span class="topAnswerBio"> bio </span><br>
																	</div>
																</div>';
																echo '<div class="topAnswer" id="topAnswerId-'.$row->answer->ansid.'">
																	<span class="topAnswerText">'.$row->answer->answer.'</span>';
																	if($row->answer->images!="")
															      	{
															        	foreach ($row->answer->images->result() as $key)
															        	{
																        	if($key->imagename=='__UNLINK__' || $key->imagename=='')
																        	{
																        		break;
																        	}
																        	else
																        	{
																	            $options = ['size' => 500, 'crop' => false];
																	            $image_file = BUCKET_ANSWERS.$key->imagename;
																	            $image_url = CloudStorageTools::getImageServingUrl($image_file, $options);

																	            echo "<div class='topAnswerImg'><img src='".$image_url."' class='img-responsive' style='width:20%;'></div>";
															        		}
																		}
																	}

																echo '</div>';

																echo '<div class="ansButtonsRow flex-container-desktop">';
																if($this->session->is_logged_in!=0){
													    			echo '<paper-button id="upvoteansBtn_'.$row->answer->ansid.'" name="upvoteansBtn_'.$row->answer->ansid.'" class="ansUpvoteBtn upvoteansBtn_'.$row->answer->ansid;
													    				if($row->answer->upvoted == 1){
													    					echo ' buttonUpvoted';
													    				}
													    				echo '" onclick="updateupvoteans('.$row->answer->ansid.')" value="'.$row->answer->ansid.'">
													    				<span class="votesSeperator';
													    				if($row->answer->upvoted == 1){
													    					echo ' votesSeperatorUpvoted';
													    				}
													    				echo '">
													    					<iron-icon icon="thumb-up" style="font-size: 14px;margin-right: 5px;"></iron-icon>
													    					<span id="ansUpvoteBtnText_'.$row->answer->ansid.'" class="ansUpvoteBtnText ansUpvoteBtnText_'.$row->answer->ansid.'">';
													    						if($row->answer->upvoted == 1){
													    							echo 'Upvoted';
													    						}
													    						else{
													    							echo 'Upvote';
													    						}
													    						echo '</span>
													    				</span>
													    				<span class="answerupvotes_'.$row->answer->ansid.'" id="answerupvotes_'.$row->answer->ansid.'" style="padding-left: 10px;">'.
													    					($row->answer->upvotes).'
													    				</span>
													    			</paper-button>

													    			<paper-button id="downvoteansBtn_'.$row->answer->ansid.'" name="downvoteansBtn_'.$row->answer->ansid.'" class="ansDownvoteBtn downvoteansBtn_'.$row->answer->ansid;
													    				if($row->answer->upvoted == 0){
													    					echo ' buttonDownvoted';
													    				}
													    				echo '" onclick="updatedownvoteans('.$row->answer->ansid.')" value="'.$row->answer->ansid.'">
													    				<span class="votesSeperator';
													    				if($row->answer->upvoted == 0){
													    					echo ' votesSeperatorDownvoted';
													    				}
													    				echo '">
													    					<iron-icon icon="thumb-down" style="font-size: 14px;margin-right: 5px;"></iron-icon>
													    					<span id="ansDownvoteBtnText_'.$row->answer->ansid.'" class="ansDownvoteBtnText ansDownvoteBtnText_'.$row->answer->ansid.'">';
													    						if($row->answer->upvoted == 0){
													    							echo 'Downvoted';
													    						}
													    						else{
													    							echo 'Downvote';
													    						}
													    						echo '</span>
													    				</span>
													    				<span class="answerdownvotes_'.$row->answer->ansid.'" id="answerupvotes_'.$row->answer->ansid.'" style="padding-left: 10px;">'.
													    					($row->answer->downvotes).'
													    				</span>
													    			</paper-button>

													    			<span class="topAnswerComment" onclick="showCommentDiv(\'topAnswerCommentDiv'.$row->answer->ansid.'\')">Comment</span>
													    			';
													    		}
													    			echo '<span class="flex"></span>
													    			<span class="fb-share-button" data-href="'.site_url("Communication/showQuestionAnswer/".$qid."/".$row->answer->ansid).'" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></span>
													    			<span class="share-btn" onclick="paperToast.open()" data-clipboard-text="'.site_url("Communication/showQuestionAnswer/".$qid."/".$row->answer->ansid).'">
													    				<iron-icon icon="link"></iron-icon> Copy Link</span>
													    		</div>';
													    	?>
													    	<?php if($this->session->is_logged_in!=0){ ?>
													    		<div class="ansCommentDiv" id="<?php echo 'topAnswerCommentDiv'.$row->answer->ansid ?>">
															    	<?php
																    	if($row->answer->comments!=0)
																      	{
																        	foreach($row->answer->comments->result() as $commentdata)
																        	{
																	          	echo '
																		        	<div class="comment-row">
																		        	<span class="comment">'.$commentdata->comment.'  -</span>
													                                <span class="comment-author"><a href="'.site_url("User/profile/".encode_id($commentdata->CID)).'">'.$commentdata->Display_Name.'</a></span>
													                                <span class="comment-time"> '.$commentdata->cr_dt.'</span>
																		        	</div>';
																        	}
																		}
															    	?>

																    <?php echo '<div id="commenta_'.$row->answer->ansid.'div" class="ansCommentInput flex-container">';?>
																    <?php echo '<div class="flex-5"><paper-textarea type="text" id="commenta_'.$row->answer->ansid.'" placeholder="Start writing your comment" required="required"></paper-textarea></div>';?>
																    <?php echo '<div class="flex"><paper-button class="ansCommentBtn" onclick="saveanscomment('.$row->answer->ansid.');">Comment</paper-button></div></div>';?>
																    <?php echo '<p id="errorc_'.$row->answer->ansid.'" style="color:red"></p>';?>
																</div>

											<?php
															}
															echo '</div>';
														}
														echo '</paper-card>';
									}
									?>
									</div>
								<?php
								}
								?>
								<div id="allProfilesResults">
								<?php
									$collegeOrder = json_decode($jsonCollegeOrder);
									$collegeData = json_decode($jsonFinalData);
						
								?>
								
								</div>
								<div id="loadMoreProfilesAll">
														<div class="tab-page">	<!-- Profiles -->
							<div id="profilesResults">
								<!-- Add College Cards -->
								
								<div id="tagsBar"style="background-color: white; width: 100%;  ">
								

						        <div class="form-group has-feedback" id="tagdiv" style="display: inline-block;  margin-top: 5px; margin-bottom: 5px;">
						           
														       
														        <div class="form-group has-feedback" id="tagdiv">
																
														           <!--<input name="tags" id="tags"  required="required" placeholder='Enter Tags' onclick="getTags" value="<?php // for($i=0;$i<sizeof($tags);$i++){echo $tags[$i].',';} ?>" class="form-control" style="width: 100%;border-radius: 0;box-sizing: border-box;border: 1px solid #eaeaea;padding: 8.4px;">
														        -->
																				<script>
																
					$(document).ready(function() {

{
getTagsinitial();
}

});
 var sampleTags ;
 
 function getTagsinitial()
			  {
				  
			    $.ajax({
			      url: '<?php echo site_url('Communication/getTags'); ?>',
			      type: 'POST',
			      async: false,
			      dataType: "json",
			      success: function(data)
			      {
			        sampleTags=data;
				  // sampleTags.push.apply(sampleTags, data);
			        $('#tags').tagit({
						
			                availableTags: sampleTags,
			                onlyAvailableTags: true,
			                caseSensitive: false,
			                allowSpaces: true,
			               // showAutocompleteOnFocus: true,
			               
							afterTagAdded  : 
							function (){ 
							var availabe_tag = <?php echo sizeof($tags); ?>;
							if(availabe_tag==0)
							{$("#tags").tagit("removeTagByLabel", "Tag");}
							$("#overlay").hide();
								$('html, body').css({
								overflow: 'auto',
								overflowX: 'hidden',
								height: 'auto'
							});
							
							var filterpresent=0;
							var stream = document.getElementById("stream").querySelectorAll("paper-checkbox");
							for(i = 0;i < stream.length;i++){
								if(stream[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var major = document.getElementById("major").querySelectorAll("paper-checkbox");
							for(i = 0;i < major.length;i++){
								if(major[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var degree = document.getElementById("degree").querySelectorAll("paper-checkbox");
							for(i = 0;i < degree.length;i++){
								if(degree[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var country = document.getElementById("country").querySelectorAll("paper-checkbox");
							for(i = 0;i < country.length;i++){
								if(country[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							if (filterpresent>0)
							filter();
							else
								nofilter();
							function nofilter(){
								
							$("#overlay").hide();
								$('html, body').css({
								overflow: 'auto',
								overflowX: 'hidden',
								height: 'auto'
							});
							}}
							
			            }); 
						
						
			      }
			      });

			  }
			  
			  function getTags()
			  {
				  

	           $('#load_more').html('Load More');
			   $('#load_more').removeAttr('disabled');
			   $('#show_less').html('Show Less');
			   $('#show_less').removeAttr('disabled');
			    $.ajax({
			      url: '<?php echo site_url('Communication/getTags'); ?>',
			      type: 'POST',
			      async: false,
			      dataType: "json",
			      success: function(data)
			      {
			        sampleTags=data;
				  // sampleTags.push.apply(sampleTags, data);
			        $('#tags').tagit({
			                availableTags: sampleTags,
			                onlyAvailableTags: true,
			                caseSensitive: false,
			                allowSpaces: true,
			               
			               
							afterTagAdded  : 
							function (){ 
							$("#overlay").show();
								$('html, body').css({
								overflow: 'auto',
								overflowX: 'hidden',
								height: '100%'
							});
							
							var filterpresent=0;
							var stream = document.getElementById("stream").querySelectorAll("paper-checkbox");
							for(i = 0;i < stream.length;i++){
								if(stream[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var major = document.getElementById("major").querySelectorAll("paper-checkbox");
							for(i = 0;i < major.length;i++){
								if(major[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var degree = document.getElementById("degree").querySelectorAll("paper-checkbox");
							for(i = 0;i < degree.length;i++){
								if(degree[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var country = document.getElementById("country").querySelectorAll("paper-checkbox");
							for(i = 0;i < country.length;i++){
								if(country[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							if (filterpresent>0)
							filter();
							else
								nofilter();
							function nofilter(){
							 var tags = $("#tags").tagit("assignedTags");
						//	 tags = tags.toString();
							 $.ajax({
								  url: '<?php echo site_url('Search/UpdateCollegeListsorting'); ?>',
								  type: 'POST',
								  data: {'tags':tags},
								  
								  async: true,
								  dataType: "json",
								  success: function(data)
								  {
									   $('#load_more').show();
									   $('#show_less').hide();
											  if(data['load_status']==1)
											{
												$('#load_more').hide();
												$('#show_less').hide();
											}
											
									        
											if(data['rank'].length<10)
											{
												 $('#load_more').hide();
											}
											$('#college_list').html('');
											
											updateListView(data);
									
								  }
								});
							}},
							afterTagRemoved: 
							function(){ 
							$("#overlay").show();
								$('html, body').css({
								overflow: 'auto',
								overflowX: 'hidden',
								height: '100%'
							});
							
							var filterpresent=0;
							var stream = document.getElementById("stream").querySelectorAll("paper-checkbox");
							for(i = 0;i < stream.length;i++){
								if(stream[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var major = document.getElementById("major").querySelectorAll("paper-checkbox");
							for(i = 0;i < major.length;i++){
								if(major[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var degree = document.getElementById("degree").querySelectorAll("paper-checkbox");
							for(i = 0;i < degree.length;i++){
								if(degree[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var country = document.getElementById("country").querySelectorAll("paper-checkbox");
							for(i = 0;i < country.length;i++){
								if(country[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							if (filterpresent>0)
							filter();
							else
								nofilter();
							function nofilter(){
							 var tags = $("#tags").tagit("assignedTags");
							// tags = tags.toString();
							 $.ajax({
								  url: '<?php echo site_url('Search/UpdateCollegeListsorting'); ?>',
								  type: 'POST',
								  data: {'tags':tags},
								  async: true,
								  dataType: "json",
								  success: function(data)
								  {
									
									         $('#load_more').show();
										  $('#show_less').hide();
											  if(data['load_status']==1)
											{
												$('#load_more').hide();
												$('#show_less').hide();
											}
											  
											if(data['rank'].length<10)
											{
												 $('#load_more').hide();
											}
											$('#college_list').html('');
											
											updateListView(data);
									
								  }
								});
							}}
			            }); 
						
						
			      }
			      });

			  }
			  
</script> 
																													
			<ul id="tags" onclick="getTags()">
			<?php array_multisort(array_map('strlen', $tags),SORT_DESC, $tags); ?>
			<?php if(sizeof($tags)==0)echo '<li>Tag</li><script>$(document).ready(function() {$("#tags").tagit("removeTagByLabel", "Tag");});</script>';  ?>
			<?php for($i=0;$i<sizeof($tags);$i++)echo '<li>'.$tags[$i].'</li>'; ?>

            </ul>
							<?php if(sizeof($tags)==0)//echo '<script>$(document).ready(function() {$("#tags").tagit("removeTagByLabel", "Tag");});</script>';  ?>						
																
																<p id="errort" style="color:red"></p>
														            <span>
														             <span id="loadingpara" style="display:none">Getting Most relevant Tags for you</span>
														             <img src="<?php echo asset_url().'images/icons/loading.gif';?>" width="20px" id="loadingimage" style="display:none">
														             </span>
																	 
														        </div>

															
																<p id="errort" style="color:red"></p>
						        </div>
									<script>
									function sorting(){
									
	           $('#load_more').html('Load More');
			   $('#load_more').removeAttr('disabled');
			   $('#show_less').html('Show Less');
			   $('#show_less').removeAttr('disabled');
										$("#overlay").show();
											$('html, body').css({
											overflow: 'auto',
											overflowX: 'hidden',
											height: '100%'
										});
									var sorting = $('#sorting').val();
									var filterpresent=0;
							var stream = document.getElementById("stream").querySelectorAll("paper-checkbox");
							for(i = 0;i < stream.length;i++){
								if(stream[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var major = document.getElementById("major").querySelectorAll("paper-checkbox");
							for(i = 0;i < major.length;i++){
								if(major[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var degree = document.getElementById("degree").querySelectorAll("paper-checkbox");
							for(i = 0;i < degree.length;i++){
								if(degree[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							var country = document.getElementById("country").querySelectorAll("paper-checkbox");
							for(i = 0;i < country.length;i++){
								if(country[i].checked ? 1 : 0)
								{
									filterpresent = filterpresent +1; 
								}
							}
							if (filterpresent>0)
							filter();
							else
								sortnofilter();
							function sortnofilter(){
										$.ajax({
										  url: '<?php echo site_url('Search/UpdateCollegeListsorting'); ?>',
										  type: 'POST',
										  data: {'sorting':sorting,'tags':$("#tags").tagit("assignedTags")},
										  async: true,
										  dataType: "json",
										  success: function(data)
										  {
											   $('#load_more').show();
										  $('#show_less').hide();
											  if(data['load_status']==1)
											{
												$('#load_more').hide();
												$('#show_less').hide();
											}
											$('#college_list').html('');
										  
											if(data['rank'].length<10)
											{
												 $('#load_more').hide();
											}
											//alert(JSON.stringify(data));
											
											updateListView(data);
										  }
										});
							}
									}

									</script>
						        </div>
								<div id = 'college_list'>
								
								<?php
								if(sizeof($rank)>10)
								{
									$show_length = 10;
									
								}
								else{
									$show_length = sizeof($rank);
								}
								for ($i=0;$i<$show_length;$i++)
								{
									if($rank[$i]['degree']=="")$rank[$i]['degree']='NA';
									if($rank[$i]['streams']=="")$rank[$i]['streams']='NA';
									if($tag_name == 1)
									{
										$heading = $rank[$i]['header'];
										$value    = $rank[$i]['stats'].'('.$rank[$i]['footer'].')';
									}
									else 
									{
										$heading = 'Annual Expenses';
										$value ='<b>'.$rank[$i]['fee'].'</b>';
									}
							     echo	'<paper-card>';
								echo	'<div class="college">';
								echo '<a href="'.site_url('college/details/'.$rank[$i]["encoded_id"]).'" style="cursor: pointer;">';
							    echo	'<div class="college-heading">';
								echo    $rank[$i]['COLL_NAME'].' ';
								echo 	'<div style="display: inline-block; color: #868686;font-size: 12px; text-transform: capitalize;">';
								echo	$rank[$i]['city'];
								echo 	'</div>';
								echo 	'</div>';
								echo	'</a>';
								echo	'<div class="boxdr" style="display: flex;"><div class="left-up flex-4">';
								
								echo		'<a href="'.site_url('college/details/'.$rank[$i]['encoded_id']).'" style="cursor: pointer;">';
								echo		'<div class="other-details">';
								echo		'<div class="detail1">';
								echo			'<div class="det1"><span style="font-weight: 600;">Streams:</span>'.' '.$rank[$i]['streams'].'</div><br>';
								echo			'<div class="det1-res" style="margin-top: 7px;">Degrees:<span style="font-weight: 400;">'.' '.$rank[$i]['degree'].'</span></div>';
								echo		'</div>';
								echo		'</div>';

								echo		'</div></a>';

								echo		'<div class="right-up flex-2">';

								echo		'<div class="detail1">';
								echo			'<div class="det1 fnew"><span class="newfnt">'.$heading.':</span>'.$value.' </div><br>';
								echo			'<div class="det1 fnew"><span class="newfnt">Starting Annual Salary:</span><b>'.$rank[$i]["salary"].'</b></div><br>';
								echo			'<div class="det1 fnew"><span class="newfnt">Profile Views:</span>'.$rank[$i]["views"].'</div>';
								echo		'</div>';
								echo		'<a href="'.site_url('college/add_compare/'.$rank[$i]['encoded_id']).'" style="cursor: pointer;">';
								echo		'<div class="compare-button">';
								echo		'<paper-button>';
								echo			'ADD TO COMPARE';
								echo			'</paper-button>';
								echo		'</div>';
								echo		'</a>';
								echo		'</div>';
								echo	'</div></div>';
								echo '</paper-card>';
								}
								?>
								</div>
						
							</div>
							
							
						</div>
								</div></br></br><center><div class="compare-button"><paper-button id='load_more' onclick="load_more(5)">Load More</paper-button><paper-button id = 'show_less' onclick="load_more(6)">Show Less</paper-button></div></center>
                               <script>
							   var count_rank = <?php echo sizeof($rank);?>;
							     if( count_rank <=10)
											{
												$('#load_more').hide();
												$('#show_less').hide();
											}
											$('#show_less').hide();
							   </script>
								<?php
								if($origin == 1)
								{
									?>
									<div id="allDiscussionsResults">
									<?php
									foreach($discussion['questions'] as $row)
									{
										$qid=$row->qid;
										echo '<paper-card class="questionCard" id="questionCardNo-'.$row->qid.'" data-upvotes="'.$row->upvotes.'" data-views="'.$row->views.'" data-date="'.$row->cr_dt.'"><div class="card-content">
																<div>
																	<a href="'.site_url('Communication/showQuestion?qid='.$row->qid).'"><span class="questionText">'.$row->question.'</span></a>';
																	if($this->session->is_logged_in!=0){
																	echo '<span class="questionEndIcons">
																		<span><paper-tooltip position="left">Follow</paper-tooltip><iron-icon id="follow-'.$row->qid.'" icon="social:plus-one" onclick="follow('.$row->qid.')" class="follow-'.$row->qid.' follow';
																			if($row->followed){
																				echo ' followed';
																			}
																			echo '"></iron-icon></span>
																			<span><paper-tooltip position="right" style="white-space:nowrap;">Flag as inappropriate</paper-tooltip><iron-icon icon="flag" class="flag flag-'.$row->qid;
																			if(!$row->question_upvoted){
																				echo ' flagged';
																			}
																			echo '" id="flag-'.$row->qid.'" onclick="updatedownvote('.$row->qid.')"></iron-icon></span>
																	</span>';
																}
																echo '</div>
															</div>';														if(!empty($row->answer)){
															echo '<div class="card-actions">
																<div>
																	<div class="topAnswerImg">
																		<img src="/assets/images/profilePics/profile1.jpg" style="width:40px;height:40px;">
																	</div>
																	<div class="topAnswerAbout" style="display: inline-block;">
																		<span class="topAnswerAuthor"><a href="'.site_url("User/profile/".encode_id($row->answer->CID)).'">'.$row->answer->Display_Name.'</a>,</span>
																		<span class="topAnswerBio"> bio </span><br>
																	</div>
																</div>';
																echo '<div class="topAnswer" id="topAnswerId-'.$row->answer->ansid.'">
																	<span class="topAnswerText">'.$row->answer->answer.'</span>';
																	if($row->answer->images!="")
															      	{
															        	foreach ($row->answer->images->result() as $key)
															        	{
																        	if($key->imagename=='__UNLINK__' || $key->imagename=='')
																        	{
																        		break;
																        	}
																        	else
																        	{
																	            $options = ['size' => 500, 'crop' => false];
																	            $image_file = BUCKET_ANSWERS.$key->imagename;
																	            $image_url = CloudStorageTools::getImageServingUrl($image_file, $options);

																	            echo "<div class='topAnswerImg'><img src='".$image_url."' class='img-responsive' style='width:20%;'></div>";
															        		}
																		}
																	}

																echo '</div>';

																echo '<div class="ansButtonsRow flex-container-desktop">';
																if($this->session->is_logged_in!=0){
													    			echo '<paper-button id="upvoteansBtn_'.$row->answer->ansid.'" name="upvoteansBtn_'.$row->answer->ansid.'" class="ansUpvoteBtn upvoteansBtn_'.$row->answer->ansid;
													    				if($row->answer->upvoted == 1){
													    					echo ' buttonUpvoted';
													    				}
													    				echo '" onclick="updateupvoteans('.$row->answer->ansid.')" value="'.$row->answer->ansid.'">
													    				<span class="votesSeperator';
													    				if($row->answer->upvoted == 1){
													    					echo ' votesSeperatorUpvoted';
													    				}
													    				echo '">
													    					<iron-icon icon="thumb-up" style="font-size: 14px;margin-right: 5px;"></iron-icon>
													    					<span id="ansUpvoteBtnText_'.$row->answer->ansid.'" class="ansUpvoteBtnText ansUpvoteBtnText_'.$row->answer->ansid.'">';
													    						if($row->answer->upvoted == 1){
													    							echo 'Upvoted';
													    						}
													    						else{
													    							echo 'Upvote';
													    						}
													    						echo '</span>
													    				</span>
													    				<span class="answerupvotes_'.$row->answer->ansid.'" id="answerupvotes_'.$row->answer->ansid.'" style="padding-left: 10px;">'.
													    					($row->answer->upvotes).'
													    				</span>
													    			</paper-button>

													    			<paper-button id="downvoteansBtn_'.$row->answer->ansid.'" name="downvoteansBtn_'.$row->answer->ansid.'" class="ansDownvoteBtn downvoteansBtn_'.$row->answer->ansid;
													    				if($row->answer->upvoted == 0){
													    					echo ' buttonDownvoted';
													    				}
													    				echo '" onclick="updatedownvoteans('.$row->answer->ansid.')" value="'.$row->answer->ansid.'">
													    				<span class="votesSeperator';
													    				if($row->answer->upvoted == 0){
													    					echo ' votesSeperatorDownvoted';
													    				}
													    				echo '">
													    					<iron-icon icon="thumb-down" style="font-size: 14px;margin-right: 5px;"></iron-icon>
													    					<span id="ansDownvoteBtnText_'.$row->answer->ansid.'" class="ansDownvoteBtnText ansDownvoteBtnText_'.$row->answer->ansid.'">';
													    						if($row->answer->upvoted == 0){
													    							echo 'Downvoted';
													    						}
													    						else{
													    							echo 'Downvote';
													    						}
													    						echo '</span>
													    				</span>
													    				<span class="answerdownvotes_'.$row->answer->ansid.'" id="answerupvotes_'.$row->answer->ansid.'" style="padding-left: 10px;">'.
													    					($row->answer->downvotes).'
													    				</span>
													    			</paper-button>

													    			<span class="topAnswerComment" onclick="showCommentDiv(\'topAnswerCommentDiv'.$row->answer->ansid.'\')">Comment</span>
													    			';
													    		}
													    			echo '<span class="flex"></span>
													    			<span class="fb-share-button" data-href="'.site_url("Communication/showQuestionAnswer/".$qid."/".$row->answer->ansid).'" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></span>
													    			<span class="share-btn" onclick="paperToast.open()" data-clipboard-text="'.site_url("Communication/showQuestionAnswer/".$qid."/".$row->answer->ansid).'">
													    				<iron-icon icon="link"></iron-icon> Copy Link</span>
													    		</div>';
													    	?>
													    	<?php if($this->session->is_logged_in!=0){ ?>
													    		<div class="ansCommentDiv" id="<?php echo 'topAnswerCommentDiv'.$row->answer->ansid ?>">
															    	<?php
																    	if($row->answer->comments!=0)
																      	{
																        	foreach($row->answer->comments->result() as $commentdata)
																        	{
																	          	echo '
																		        	<div class="comment-row">
																		        	<span class="comment">'.$commentdata->comment.'  -</span>
													                                <span class="comment-author"><a href="'.site_url("User/profile/".encode_id($commentdata->CID)).'">'.$commentdata->Display_Name.'</a></span>
													                                <span class="comment-time"> '.$commentdata->cr_dt.'</span>
																		        	</div>';
																        	}
																		}
															    	?>

																    <?php echo '<div id="commenta_'.$row->answer->ansid.'div" class="ansCommentInput flex-container">';?>
																    <?php echo '<div class="flex-5"><paper-textarea type="text" id="commenta_'.$row->answer->ansid.'" placeholder="Start writing your comment" required="required"></paper-textarea></div>';?>
																    <?php echo '<div class="flex"><paper-button class="ansCommentBtn" onclick="saveanscomment('.$row->answer->ansid.');">Comment</paper-button></div></div>';?>
																    <?php echo '<p id="errorc_'.$row->answer->ansid.'" style="color:red"></p>';?>
																</div>

											<?php
															}
															echo '</div>';
														}
														echo '</paper-card>';
									}											?>
									</div>
								<?php
								}
								?>
							</div>
						</div>

						<div class="tab-page">	<!-- Discussion -->
							<div id="discussionsResults">
							<button onclick="sortQuestions('mostupvotes',-1); return false;">Sort By Most upvotes</button>
							<button onclick="sortQuestions('leastupvotes',-1); return false;">Sort By Least upvotes</button>
								<!-- Add Question Cards -->
							</div>
							<div id="loadMoreDiscussions">
							</div>
						</div>



						<div class="tab-page"></div>

					</iron-pages>
				</div>
			</template>
		</div>

		<footer>
			<?php include "common_components/footer.php" ?>
		</footer>

	</app-header-layout>

</app-drawer-layout>

<!-- <script type="text/javascript" src="/assets/js/toolbarSearch.js"></script> -->
<script type="text/javascript" src="/assets/js/commonScript.js"></script>
<!-- <script type="text/javascript" src="/assets/js/encode_req.js"></script> -->
<script>

function filter(){
				
				
	           
										$("#overlay").show();
											$('html, body').css({
											overflow: 'auto',
											overflowX: 'hidden',
											height: '100%'
										});

							var stream = document.getElementById("stream").querySelectorAll("paper-checkbox");
							var streamid = document.getElementById("stream").querySelectorAll("input");
							var streams = new Array();
							var k=0;
							for(i = 0;i < stream.length;i++){
								if(stream[i].checked ? 1 : 0)
								{
									streams[k] = $(streamid[i]).val(); k++;
								}
							}
							var filter = [streams];
							var major = document.getElementById("major").querySelectorAll("paper-checkbox");
							var majorid = document.getElementById("major").querySelectorAll("input");
							var majors = new Array();
							var k =0;
							for(i = 0;i < major.length;i++){
								if(major[i].checked ? 1 : 0)
								{
									majors[k] = $(majorid[i]).val(); k++; 
								}
							}
							filter.push(majors);
							var degree = document.getElementById("degree").querySelectorAll("paper-checkbox");
							var degrees ='';
							for(i = 0;i < degree.length;i++){
								if(degree[i].checked ? 1 : 0)
								{
									degrees = degrees +'/'+ $(degree[i]).text(); 
								}
							}degrees  =degrees.replace(/(\r\n|\n|\r)/gm,"");
							degrees = degrees.split('/');
							filter.push(degrees);
							var country = document.getElementById("country").querySelectorAll("paper-checkbox");
							var countries ='';
							for(i = 0;i < country.length;i++){
								if(country[i].checked ? 1 : 0)
								{
									countries = countries +'/'+ $(country[i]).text(); 
								}
							}countries=countries.replace(/(\r\n|\n|\r)/gm,"");
							countries =countries.split('/');
							filter.push(countries);
							var sorting = $('#sorting').val();
							//alert(filter);
							$.ajax({
										  url: '<?php echo site_url('Search/Updatefilters'); ?>',
										  type: 'POST',
										  data: {'filter':filter,'tags': $("#tags").tagit("assignedTags"),'sorting':sorting},
										  async: true,
										  dataType: "json",
										  success: function(data)
										  {
											  $('#load_more').show();
										  $('#show_less').show();
											  if(data['load_status']==1)
											{
												$('#load_more').hide();
												$('#show_less').hide();
											}
											else if(data['load_status']==3)
											{
												
												$('#show_less').hide();
											}
										 $('#college_list').html('');
										
                                          //alert(JSON.stringify(data['mark_stream']));
										  
											updateListView(data);
										  
										  }
										});
}
function load_more(value_load){
	$("#overlay").show();
											$('html, body').css({
											overflow: 'auto',
											overflowX: 'hidden',
											height: '100%'
										});
	var stream = document.getElementById("stream").querySelectorAll("paper-checkbox");
							var streamid = document.getElementById("stream").querySelectorAll("input");
							var streams = new Array();
							var k=0;
							for(i = 0;i < stream.length;i++){
								if(stream[i].checked ? 1 : 0)
								{
									streams[k] = $(streamid[i]).val(); k++;
								}
							}
							var filter = [streams];
							var major = document.getElementById("major").querySelectorAll("paper-checkbox");
							var majorid = document.getElementById("major").querySelectorAll("input");
							var majors = new Array();
							var k =0;
							for(i = 0;i < major.length;i++){
								if(major[i].checked ? 1 : 0)
								{
									majors[k] = $(majorid[i]).val(); k++; 
								}
							}
							filter.push(majors);
							var degree = document.getElementById("degree").querySelectorAll("paper-checkbox");
							var degrees ='';
							for(i = 0;i < degree.length;i++){
								if(degree[i].checked ? 1 : 0)
								{
									degrees = degrees +'/'+ $(degree[i]).text(); 
								}
							}degrees  =degrees.replace(/(\r\n|\n|\r)/gm,"");
							degrees = degrees.split('/');
							filter.push(degrees);
							var country = document.getElementById("country").querySelectorAll("paper-checkbox");
							var countries ='';
							for(i = 0;i < country.length;i++){
								if(country[i].checked ? 1 : 0)
								{
									countries = countries +'/'+ $(country[i]).text(); 
								}
							}countries=countries.replace(/(\r\n|\n|\r)/gm,"");
							countries =countries.split('/');
							filter.push(countries);
							var sorting = $('#sorting').val();
							$.ajax({
										  url: '<?php echo site_url('Search/Updatefilters'); ?>',
										  type: 'POST',
										  data: {'filter':filter,'tags': $("#tags").tagit("assignedTags"),'sorting':sorting,'load':value_load},
										  async: true,
										  dataType: "JSON",
										  success: function(data)
										  {
											  $('#load_more').show();
										  $('#show_less').show();
											if(data['load_status']==1)
											{
												$('#load_more').hide();
												$('#show_less').hide();
											}
											else if(data['load_status']==3)
											{
												$('#show_less').hide();
												$('#college_list').html('');
											    updateListView(data);
											}
											else if(data['load_status']==11)
											{
												$('#load_more').hide();
												$('#college_list').html('');
												updateListView(data);
											}
										
											else{
											$('#college_list').html('');
											updateListView(data);
											}
										  }
										});
}

function updateListView(data)
{ 
	var site_url = '<?php echo site_url() ?>';
	for(var i=0;i<data['rank'].length;i++)
	{
		if(data["rank"][i]["degree"]=="")data["rank"][i]["degree"]="";
		if(data["rank"][i]["streams"]=="")data["rank"][i]["streams"]="";
		if(data["rank"][i]["city"]==null)data["rank"][i]["city"]="";
		if(data["tag_name"] == 1)
		{
			var heading = data["rank"][i]['header'];
			var value    = data["rank"][i]['stats']+'('+data["rank"][i]['footer']+')';
		}
		else 
		{
			var heading = data['tag_name'];
			if(data["rank"][i]['fee'] == "INT_MAX" || data["rank"][i]['fee'] == -1 )
			{
				var value = "NA";
			}
			else
			{
				var value = data["rank"][i]['fee'];
			}

		}
		if(data["rank"][i]["salary"] == "INT_MAX" || data["rank"][i]["salary"] == -1 )
		{
			data["rank"][i]["salary"] = "NA";
		}
		$('<paper-card> <div class="college"> <a href="'+site_url+'college/details/'+data["rank"][i]["encoded_id"]+'" style="cursor: pointer;">'+
			'<div class="college-heading">' + data["rank"][i]["COLL_NAME"] + ' '+'<div style="display: inline-block; color: #868686;font-size: 12px; text-transform: capitalize;">'+data["rank"][i]["city"]+'</div></div> </a> <div class="boxdr" style="display: flex; "><div class="left-up flex-4"> <a href="'+site_url+'college/details/'+data["rank"][i]["encoded_id"]+'" style="cursor: pointer;">'+
			'<div class="other-details"> <div class="detail1"><div class="det1"><span style="font-weight: 600;"></span>'+data["rank"][i]["streams"]+'</div><br> <div class="det1-res" style="margin-top: 7px;"><span style="font-weight: 400; ">'+ data["rank"][i]["degree"]+'</span></div> </div></div></div></a><div class="right-up flex-2"><div class="detail1"><div class="det1 fnew"><span class="newfnt">'+heading+':</span><b>'+ value +'</b></div><br><div class="det1 fnew"><span class="newfnt">Starting Annual Salary:</span><b>'+data["rank"][i]["salary"]+'</b></div><br><div class="det1 fnew"><span class="newfnt">Profile Views:</span>'+data["rank"][i]["views"]+'</div></div><a href="' +site_url+ 'college/add_compare/'+data["rank"][i]["encoded_id"]+'" style="cursor: pointer;"> <div class="compare-button"> <paper-button> ADD TO COMPARE</paper-button> </div> </a> </div> </div></div> </paper-card>').appendTo('#college_list');
	


	/*	
	$('<paper-card><div class="college"><a href="'+site_url+'college/details/'+data["rank"][i]["encoded_id"]+'" style="cursor: pointer;">' +
    '<div class="college-heading" >' + data["rank"][i]["COLL_NAME"] +  '</div></a><a href="'+site_url+'college/details/'+data["rank"][i]["encoded_id"]+'" style="cursor: pointer;">' +
    '<div class="left-up"><div class="college-basic"><div class="college-loc"><iron-icon icon="room"><iron-icon icon="room"></iron-icon>Roorkee, India</div>' +
    '<div class="college-affil"><iron-icon icon="done-all"></iron-icon>Fees: 0</div></div><div class="other-details"><div class="detail1"><div class="det1">Streams: Engineering, Medical...</div><br>' +
    '<div class="det1-res">' + data["fee"][i] + '</div></div><div class="detail2"><div class="det1">Admission</div><div class="det1-res">JEE-Advanced</div></div></div></div></a>' +
    '<div class="right-up"><div class="rating"><div class="rating-point">'+ data["rank"][i]["score"]+'v->'+data["rank"][i]["views"]+'</div><div class="rating-det"><div class="rating-stars"><iron-icon icon="star"></iron-icon><iron-icon icon="star"></iron-icon><iron-icon icon="star"></iron-icon><iron-icon icon="star"></iron-icon><iron-icon icon="star-half"></iron-icon>' +
    '</div><div class="rating-label">COURSE RATING</div></div></div><a href="'+site_url+'college/add_compare/'+data["rank"][i]["encoded_id"]+'" style="cursor: pointer;"><div class="compare-button">' +
    '<paper-button>ADD TO COMPARE</paper-button></div></a></div></div></paper-card>').appendTo('#college_list');
		
	*/	
		
	}
	
	
	$('#found').html('Found '+data['rank'].length+' Colleges');
		//streams filters
	//alert(JSON.stringify(data['mark_majors']));
	if(data['Streams'])
	{
	$('#stream').html('');
	for(var i=0;i<data['Streams'].length;i++){
		str = data['Streams'][i]['Node_Name'];
		str = str.replace("Yes_", ""); 
		var Streams = '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" >'+str+'</paper-checkbox><input type="hidden" id = "streamid" value="'+data['Streams'][i]['Node_ID']+'" /> </div>';
		if(data["Streams"][i]["value"]==0)
		{
			Streams = '<div class="categoryOptions"><paper-checkbox noink disabled onchange="filter()" >'+str+'</paper-checkbox><input type="hidden" id = "streamid" value="'+data['Streams'][i]['Node_ID']+'" /> </div>';
		}
		if(data['mark_stream'])
		{
			
			for(var j=0;j<data['mark_stream'].length;j++)
			{
		       
				if(data['mark_stream'][j]==data['Streams'][i]['Node_ID'])
				{
					Streams = '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" checked >'+str+'</paper-checkbox><input type="hidden" id = "streamid" value="'+data['Streams'][i]['Node_ID']+'" /> </div>';
				   $(Streams).prependTo('#stream');
				   Streams ='';
				}
				
				
			}
		} 
				
				
	$(Streams).appendTo('#stream');
	}
	}
	//degree filters
	if(data['Degrees'])
	{
	$('#degree').html('');
	for(var i=0;i<data['Degrees'].length;i++){
		str = data['Degrees'][i]['Node_Name'];
		str = str.replace("Yes_", ""); 
		var Degrees = '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" >'+str+'</paper-checkbox></div>';
		if(data["Degrees"][i]["value"]==0)
		{
			Degrees = '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" disabled >'+str+'</paper-checkbox></div>';
		}
		else{
					Degrees= '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" >'+str+'</paper-checkbox></div>';
			}
		if(data['mark_degree'])
		{
			
			for(var j=0;j<data['mark_degree'].length;j++)
			{
		       
				if(data['mark_degree'][j]==str)
				{
					Degrees = '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" checked >'+str+'</paper-checkbox></div>';
					$(Degrees).prependTo('#degree');
					Degrees = '';
				}
				
			}
		}
				
				
	$(Degrees).appendTo('#degree');
	}
	}
	//major filters
	if(data['Majors'])
	{
	$('#major').html('');
	for(var i=0;i<data['Majors'].length;i++){
		str = data['Majors'][i]['Node_Name'];
		str = str.replace("Yes_", ""); 
     var major = '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" >'+str+'</paper-checkbox><input type="hidden" id = "majorid" value="'+data['Majors'][i]['Node_ID']+'" /> </div>';
		 if(data["Majors"][i]["value"]==0)
				{
					major = '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" disabled >'+str+'</paper-checkbox><input type="hidden" id = "majorid" value="'+data['Majors'][i]['Node_ID']+'" /> </div>';
				}
		
				
     if(data['mark_majors'])
		{
			
			for(var j=0;j<data['mark_majors'].length;j++)
			{
				if(data['mark_majors'][j]==data['Majors'][i]['Node_ID'])
				{
					major = '<div class="categoryOptions"><paper-checkbox noink onchange="filter()" checked >'+str+'</paper-checkbox><input type="hidden" id = "majorid" value="'+data['Majors'][i]['Node_ID']+'" /> </div>';
				   $(major).prependTo('#major');
				   major = '';
				}
				
			}
		}
	$(major).appendTo('#major');
	}
	}
	$("#overlay").hide();
	$('html, body').css({
    overflow: 'auto',
    overflowX: 'hidden',
    height: 'auto'
});
}
function showCommentDiv(id){
	$("#" + id).toggle("fast");
}

function openFilter(){
	document.getElementById("leftPanel").style.display = "block";
	document.body.style.height = window.innerHeight;
	document.body.style.overflow = "hidden";
}

function openSort(){
	document.getElementById("leftSort").style.display = "block";
	document.body.style.height = window.innerHeight;
	document.body.style.overflow = "hidden";
}




function closeFilter(){
	document.getElementById("leftPanel").style.display = "none";
	document.body.style.height = "100%";
	document.body.style.overflow = "auto";
	document.body.style.overflowX = "hidden";
}

var query;


var selectedFilters = [];

var temp;

document.addEventListener('WebComponentsReady', function () {
	var template = document.querySelector('template[is="dom-bind"]');
	template.selected = 0; // selected is an index by default
});

function submitForm(id){
	var form = document.getElementById(id);
	form.submit();
}

function loadFilters()
{
	$.ajax
	(
		{
		type: "post",
		cache: false,
		url: '/search/showfilters',
		success: function(response)
			{
				var obj = JSON.parse(response);
				if(obj.length>0)
				{
					try
					{
						$.each(obj,function(i,val)
						{
							if(val.options.length>0)
							{
								term = '<paper-card class="category">';
								//term = term + '<p hidden id="filter-type-'+i+'">'+val.type+'</p>';
								term = term + '<div class="categoryLabel card-content">'+val.label+'</div><div class="card-actions">';
								for(var i=0;i<val.options.length;i++)
								{
									term = term + '<paper-checkbox name = "'+val.label+'" class = "categoryOption" onchange = "applyFilter()" value = "'+val.options[i]+'">'+val.options[i]+'</paper-checkbox>';
								}
								term = term + '</div></paper-card>';
								$('#collegeFilters').append(term);
							}
						});
					}
					catch(e)
					{
						alert(e);
						alert('Exception while request.');
					}
				}
			}
		}
	);
}

function viewProfiles()
{
	if(currentProfilesPage ==-1)
		getMoreProfiles();
}

function viewDiscussions()
{
	if(currentDiscussionsPage == -1)
		getMoreDiscussions("discussionsResults");
}

function addProfiles(collegeOrder,collegeData,div)	//Not Called First Time
{
	for(i = 0;i<collegeOrder.length;i++)
	{
		college = collegeData[collegeOrder[i]];
		term = '<paper-card class = "collegeCard" id = "college-'+collegeOrder[i]+'"><div class="card-content"><div class="college-header"><a href=\'/college/details/'+encode_id(collegeOrder[i])+'\'><span class="college-name">'+college["data"]["College_Name"]+'</span></a><div class="college-location college-light"><iron-icon icon="communication:location-on"></iron-icon><span>'+college["data"]["Country"]+'</span></div></div><div class="college-rating"><iron-icon class="star" icon="star"></iron-icon><iron-icon class="star" icon="star"></iron-icon><iron-icon class="star" icon="star"></iron-icon><iron-icon class="star" icon="star"></iron-icon><iron-icon class="star" icon="star"></iron-icon></div>';

		if(college["data"].hasOwnProperty('Streams/Schools'))
		{
			term = term + '<div class="college-details"><span>Streams Offered</span>';
			for(j = 0;j<collegeData[collegeOrder[i]]["data"]["Streams/Schools"].length;j++)
			{
				term = term + '<div class="college-light">'+collegeData[collegeOrder[i]]["data"]["Streams/Schools"][j]+'</div>';
			}
			term = term + '</div>';
		}

		term = term + '</div><div class="card-actions"><a href=\'/college/compare/'+encode_id(collegeOrder[i])+'\'><paper-button class="college-compare">Add To Compare</paper-button></a></div></paper-card>';
		$('#'+div).append(term);
	}
}

function addDiscussions(data,div)
{
	//for(i = 0;i<data.length;i++)
	$('#'+div).html("");
	$.each(data,function(index,value)
	{

		question = '<paper-card class="questionCard" id="questionCardNo-'+value.qid+'" data-upvotes="'+value.upvotes+'" data-date="'+value.cr_dt+'"><div class="card-content"><div><a href="<?php echo site_url('Communication/showQuestion');?>?qid='+value.qid+'"><span class="questionText">'+value.question+'</span></a>';



		questionendicons='<span class="questionEndIcons"><span><paper-tooltip position="left">Follow</paper-tooltip>';
		questionendicons=questionendicons+'<iron-icon id="follow-'+value.qid+'" icon="social:plus-one" onclick="follow('+value.qid+')" class="follow-'+value.qid+' follow';
		if(value.followed)
		{
			questionendicons= questionendicons + " followed";
		}
		questionendicons=questionendicons+'"></iron-icon></span><span><paper-tooltip position="right" style="white-space:nowrap;">Flag as appropriate</paper-tooltip><iron-icon icon="flag" class="flag flag-'+value.qid;
		if(value.question_upvoted==0)
		{
			questionendicons=questionendicons+' flagged';
		}
		questionendicons= questionendicons + ' id="flag-'+value.qid+'" onclick="updatedownvote('+ value.qid +')"></iron-icon></span></span>';


		<?php if($this->session->is_logged_in!=0){ ?>
		question=question+questionendicons;
		<?php } ?>


		question=question+'</div></div>';


		if(value.answer==null)
		{
			answer="";
			buttonblock="";
			commentblock="";
		}
		else{

		answer='<div class="card-actions"><div><div class="topAnswerImg"><img src="/assets/images/profilePics/profile1.jpg" style="width:40px;height:40px;"></div><div class="topAnswerAbout" style="display: inline-block;"><span class="topAnswerAuthor"><a href="<?php echo site_url('User/profile');?>/'+encode_id(value.answer.CID)+'">'+value.answer.Display_Name+'</a>,</span><span class="topAnswerBio"> bio </span><br></div></div><div class="topAnswer" id="topAnswerId-'+value.answer.ansid+'"><span class="topAnswerText">'+value.answer.answer+'</span></div>';



		var buttonblock='<paper-button id="upvoteansBtn_'+value.answer.ansid+'" name="upvoteansBtn_'+value.answer.ansid+'" class="ansUpvoteBtn upvoteansBtn_'+value.answer.ansid;


			if(value.answer.upvoted == 1){
				buttonblock=buttonblock+ ' buttonUpvoted';
			}
			buttonblock=buttonblock+'" onclick="updateupvoteans('+value.answer.ansid+')" value="'+value.answer.ansid+'"><span class="votesSeperator';

			if(value.answer.upvoted == 1){
				buttonblock=buttonblock+ ' votesSeperatorUpvoted';
			}
			buttonblock=buttonblock+ '"><iron-icon icon="thumb-up" style="font-size: 14px;margin-right: 5px;"></iron-icon>  <span id="ansUpvoteBtnText_'+value.answer.ansid+'" class="ansUpvoteBtnText ansUpvoteBtnText_'+value.answer.ansid+'">';

			if(value.answer.upvoted == 1){
				buttonblock=buttonblock+ 'Upvoted';
			}
			else
			{
				buttonblock=buttonblock+ 'Upvote';
			}
			buttonblock=buttonblock+'</span></span><span class="answerupvotes_'+value.answer.ansid+'" id="answerupvotes_'+value.answer.ansid+'" style="padding-left: 10px;">'+(value.answer.upvotes)+'</span></paper-button>';

		    buttonblock=buttonblock+ '<paper-button id="downvoteansBtn_'+value.answer.ansid+'" name="downvoteansBtn_'+value.answer.ansid+'" class="ansDownvoteBtn downvoteansBtn_'+value.answer.ansid;
			if(value.answer.upvoted == 0){
				buttonblock=buttonblock+ ' buttonDownvoted';
			}
			buttonblock=buttonblock +'" onclick="updatedownvoteans('+value.answer.ansid+')" value="'+value.answer.ansid+'"> <span class="votesSeperator';
			if(value.answer.upvoted == 0){
				buttonblock=buttonblock+ ' votesSeperatorDownvoted';
			}
			buttonblock=buttonblock+ '"><iron-icon icon="thumb-down" style="font-size: 14px;margin-right: 5px;"></iron-icon><span id="ansDownvoteBtnText_'+value.answer.ansid+'" class="ansDownvoteBtnText ansDownvoteBtnText_'+value.answer.ansid+'">';
			if(value.answer.upvoted == 0){
				buttonblock=buttonblock+ 'Downvoted';
			}
			else{
				buttonblock=buttonblock+ 'Downvote';
			}
			buttonblock=buttonblock+ '</span></span><span class="answerdownvotes_'+value.answer.ansid+'" id="answerupvotes_'+value.answer.ansid+'" style="padding-left: 10px;">'+(value.answer.downvotes)+'</span></paper-button>';


		<?php if($this->session->is_logged_in!=0) { ?>
		 buttonblock=buttonblock + '<span class="flex"></span>	<span class="fb-share-button" data-href="www.scholarfact.com/Communication/showQuestionAnswer/'+value.qid+'/'+value.answer.ansid+'" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></span>	<span class="share-btn" onclick="paperToast.open()" data-clipboard-text="www.scholarfact.com/Communication/showQuestionAnswer/'+value.qid+'/'+value.answer.ansid+'"><iron-icon icon="link"></iron-icon> Copy Link</span></div>';
		<?php }
		else
			{ ?>
		var buttonblock='<div class="ansButtonsRow"><span class="fb-share-button" data-href="www.scholarfact.com/Communication/showQuestionAnswer/'+value.qid+'/'+value.answer.ansid+'" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></span>	<span class="share-btn" onclick="paperToast.open()" data-clipboard-text="www.scholarfact.com/Communication/showQuestionAnswer/'+value.qid+'/'+value.answer.ansid+'"><iron-icon icon="link"></iron-icon> Copy Link</span></div>';
		<?php } ?>

		commentblock="";
		/*<?php if($this->session->is_logged_in!=0) {?>
		var commentblock='<div class="ansCommentDiv" id="topAnswerCommentDiv' + value.answer.ansid + '>';
		if(value.comments!=0)
		{
			for(var k=0;k<value.comments.length;k++)
			{
				var commentdata=value.comments[k];
				commentblock=commentblock+'<div class="comment-row"><span class="comment">'+commentdata.comment+'  -</span><span class="comment-author"><a href="www.scholarfact.com/User/profile/'+encode_id(value.CID)+'">'+commentdata.Display_Name+'</a></span><span class="comment-time"> '+commentdata.cr_dt+'</span></div>';
			}
		}
		commentblock=commentblock+'<div id="commenta_'+value.answer.ansid+'div" class="ansCommentInput flex-container">';

		commentblock=commentblock+'<div class="flex-5"><paper-textarea type="text" id="commenta_'+value.answer.ansid+'" placeholder="Start writing your comment" required="required"></paper-textarea></div>';

		commentblock=commentblock+'<div class="flex"><paper-button class="ansCommentBtn" onclick="saveanscomment('+value.answer.ansid+');">Comment</paper-button></div></div><p id="errorc_'+value.answer.ansid+'" style="color:red"></p></div></div>';
		<?php }
		else{
			?>
			var commentblock="";
			<?php
		} ?>*/
	}

		term=question+answer+buttonblock+commentblock+'</div></paper-card>';



		/*term = '<paper-card class="questionCard" data-upvotes="'+value.upvotes+'" data-views="'+value.views+'" data-date="'+value.cr_dt+'"><div class="card-content"><a href="/communication/showQuestion?qid='+value.qid+'"><div class="question">'+value.question+'</div></a><div class="author">'+value.Display_Name+'</div></div><div class="bottom-bar"><div class="time">On '+value.cr_dt+'</div></div><div id="voting-actions">Upvotes : '+value.upvotes+' Downvotes : '+value.downvotes+'</div></paper-card>';*/

		$('#'+div).append(term);
	});
		initializereadmore();
		FB.XFBML.parse();
}


function getMoreDiscussions(label,rank=-1,sort='default')
{
	var rank;
	var flag=0;
		$.ajax({
			type: "get",
			url: "/search/searchpage/default"+"/"+rank,
			cache: false,
			async:false,
			data: { 'query' : query },
			success: function(response)
			{
				response = JSON.parse(response);
				rank=response['rank'];
				if(response["questions"].length==0)
				{
					end="<p>That's all folks!</p>";
					flag=1;
					console.log(flag);
					$('#'+label).append(end);
				}
				else
				{
					addDiscussions(response["questions"],label);
					$('#showmoreBtn').remove();
					button='<button id="showmoreBtn" onclick="getMoreDiscussions(\'discussionsResults\','+response["rank"]+');">Load More</button>';
					$('#'+label).append(button);
				}
				if(label == "discussionsResults")
					currentDiscussionsPage = 0;
			}
		});
		if(flag!=1)
		{
			if(sort!='default')
			{
				$('#'+label).empty();
				sortQuestions(sort,rank);
			}
		}
}

function sortQuestions(sort,rank,label='discussionsResults')
{
	$.ajax({
		type: "get",
		url: "/search/searchpage/"+sort+"/"+rank,
		cache: false,
		data: { 'query' : query },
		success: function(response)
		{
			response = JSON.parse(response);
			$('#'+label).empty();
			button='<button onclick="sortQuestions(\'leastupvotes\','+response['rank']+'); return false;">Sort By Least upvotes</button><button onclick="sortQuestions(\'mostupvotes\','+response['rank']+'); return false;">Sort By Most upvotes</button>';
			$('#'+label).append(button);
			addDiscussions(response["questions"],label);
			//button='<button id="showmoreBtn" onclick="getMoreDiscussions(\'discussionsResults\','+response["rank"]+',\''+sort+'\');">Load More</button>';
			//$('#'+label).append(button);
		}
	});
}

function initialize()
{
	new Clipboard('.share-btn');
	initializereadmore();
	query = <?php echo "'".$queryTerm."'"?>;
	currentPage = -1;
	totalPages = -1;
	loadFilters();
	//getMoreDiscussions("allDiscussionsResults");
}

function getMoreProfiles()	//generic for questions and profiles -> Modify url
{
	if(currentProfilesPage < totalProfilesPage - 1)
	{
		$.ajax({
			type: "post",
			url: "/search/search_college_api/",
			cache: false,
			data: { 'query' : query , 'page' : parseInt(currentProfilesPage)+1, 'filters' : JSON.stringify(selectedFilters)},
			success: function(response)
			{
				response = JSON.parse(response);
				currentPage = response["page"];
				var collegeOrder = JSON.parse(response["jsonCollegeOrder"]);
				var collegeData = JSON.parse(response["jsonFinalData"]);
				totalProfilesPage = response["totalPages"];
				currentProfilesPage = response["page"];
				addProfiles(collegeOrder,collegeData,"profilesResults");
				if(currentProfilesPage < totalProfilesPage -1)
				{
					$('#loadMoreProfiles').html('<button onclick = "getMoreProfiles()">Load More Results</button>');
				}
				else
				{
					$('#loadMoreProfiles').html('That is all we have');
				}
			}
		});
	}
}

//Will Work Once Loading Works Fine
function applyFilter()
{
	node = document.getElementById('profilesResults');
	while(node.hasChildNodes())
		node.removeChild(node.lastChild);

	selectedFilters = [];
	var filtersDiv = document.getElementsByClassName("categoryLabel");
	var filters = [];
	for(i = 0;i<filtersDiv.length;i++)
		filters.push(filtersDiv[i].innerHTML);
	for(i = 0;i<filters.length;i++)
	{
		var options = [];
		var optionsDiv = document.getElementsByName(filters[i]);
		for(j = 0;j<optionsDiv.length;j++)
			if(optionsDiv[j].checked == true)
				options.push(optionsDiv[j].value);
		if(options.length > 0)
		{
			var filter = {};
			filter['label'] = filters[i];
			filter['checked'] = options;
			selectedFilters.push(filter);
		}
	}

	//got the filter, now call getMoreProfiles with updated
	currentProfilesPage = -1;
	totalProfilesPage = 1;
	getMoreProfiles();
}


function updateupvoteans(aid)
{
	var upvoteTexts = $('.ansUpvoteBtnText_'+aid);
	var text= $(upvoteTexts[0]).text();
	if(text==="Upvote")
	{
	  	var elements=$('.ansDownvoteBtnText_'+aid);
		text=$(elements[0]).text();
		if(text==="Downvoted")
		{
			for(i=0;i<elements.length;i++)
			{
				$(elements[i]).text("Downvote");
			}
		}

		var elements=$('.ansUpvoteBtnText_'+aid);
		for(i=0;i<elements.length;i++)
		{
			$(elements[i]).text("Upvoted");
		}

		var upvoteButtons = $('.upvoteansBtn_'+aid);
		var upvoteSeperators = $('.upvoteansBtn_'+aid + ' .votesSeperator');
		var downvoteButtons = $('.downvoteansBtn_'+aid);
		var downvoteSeperators = $('.downvoteansBtn_'+aid + ' .votesSeperator');

		for(f = 0;f < upvoteButtons.length;f++){
			$(upvoteButtons[f]).addClass("buttonUpvoted");
			$(upvoteSeperators[f]).addClass('votesSeperatorUpvoted');
			$(downvoteButtons[f]).removeClass("buttonDownvoted");
			$(downvoteSeperators[f]).removeClass('votesSeperatorDownvoted');
		}
	}
	else
	{
		var elements=$('.ansUpvoteBtnText_'+aid);
		for(i=0;i<elements.length;i++)
		{
			$(elements[i]).text("Upvote");
		}

		var upvoteButtons = $('.upvoteansBtn_'+aid);
		var upvoteSeperators = $('.upvoteansBtn_'+aid + ' .votesSeperator');

		for(f = 0;f < upvoteButtons.length;f++){
			$(upvoteButtons[f]).removeClass("buttonUpvoted");
			$(upvoteSeperators[f]).removeClass('votesSeperatorUpvoted');
		}
	}
	var cid=$('#cid').val();
	$.ajax({
		url: '<?php echo site_url('Communication/saveUpvotesAnswer'); ?>',
		method: 'GET',
		data: 'ansid='+aid+'&cid='+encode_id(cid),
		success: function(result)
		{
			var elements=$('.answerupvotes_'+aid);
			for(i=0;i<elements.length;i++)
			{
				$(elements[i]).text(result);
			}

		  $.ajax({
			url: '<?php echo site_url('Communication/getDownvotesAnswer'); ?>'+'/'+aid,
			success:function(result)
			{
				var elements=$('.answerdownvotes_'+aid);
				for(i=0;i<elements.length;i++)
				{
					$(elements[i]).text(result);
				}
			}
		  });
			  //document.getElementById('upvoteansBtn_'+aid).style.backgroundColor='#FFFFFF';
		}
	  });
}

function updatedownvoteans(aid)
{
	var downvoteTexts = $('.ansDownvoteBtnText_'+aid);
		var text=$(downvoteTexts[0]).text();
	if(text==="Downvote")
	{
		var elements=$('.ansUpvoteBtnText_'+aid);
		text=$(elements[0]).text();
		if(text==="Upvoted")
		{
			for(i=0;i<elements.length;i++)
			{
				$(elements[i]).text("Upvote");
			}
		}

		var elements=$('.ansDownvoteBtnText_'+aid);
		for(i=0;i<elements.length;i++)
		{
			$(elements[i]).text("Downvoted");
		}

		var upvoteButtons = $('.upvoteansBtn_'+aid);
		var upvoteSeperators = $('.upvoteansBtn_'+aid + ' .votesSeperator');
		var downvoteButtons = $('.downvoteansBtn_'+aid);
		var downvoteSeperators = $('.downvoteansBtn_'+aid + ' .votesSeperator');

		for(f = 0;f < upvoteButtons.length;f++){
			$(upvoteButtons[f]).removeClass("buttonUpvoted");
			$(upvoteSeperators[f]).removeClass('votesSeperatorUpvoted');
			$(downvoteButtons[f]).addClass("buttonDownvoted");
			$(downvoteSeperators[f]).addClass('votesSeperatorDownvoted');
		}
	}
	else
	{
		var elements=$('.ansDownvoteBtnText_'+aid);
		for(i=0;i<elements.length;i++)
		{
			$(elements[i]).text("Downvote");
		}

		var downvoteButtons = $('.downvoteansBtn_'+aid);
		var downvoteSeperators = $('.downvoteansBtn_'+aid + ' .votesSeperator');

		for(f = 0;f < downvoteButtons.length;f++){
			$(downvoteButtons[f]).removeClass("buttonDownvoted");
			$(downvoteSeperators[f]).removeClass('votesSeperatorDownvoted');
		}
	}
	var cid=$('#cid').val();
	$.ajax({
		url: '<?php echo site_url('Communication/saveDownvotesAnswer'); ?>',
		method: 'GET',
		data: 'ansid='+aid+'&cid='+encode_id(cid),
		success: function(result)
		{
			var elements=$('.answerdownvotes_'+aid);
			for(i=0;i<elements.length;i++)
			{
				$(elements[i]).text(result);
			}
			//$('answerdownvotes_'+aid).text(result);
			$.ajax({
			url: '<?php echo site_url('Communication/getUpvotesAnswer'); ?>'+'/'+aid,
			success:function(result)
			{
				var elements=$('.answerupvotes_'+aid);
				for(i=0;i<elements.length;i++)
				{
					$(elements[i]).text(result);
				}
				//$('#answerupvotes_'+aid).text(result);
			}
			});
		}

	});
}

function updateupvote(qid)
{
  //var qid=$('#qid').val();
  $.ajax({
	url: '<?php echo site_url('Communication/saveUpvotes'); ?>',
	method: 'POST',
	data: 'qid='+qid,
	success: function(result)
	{
	}
  });
}

function updatedownvote(qid)
{	//do the color inversion without waiting for ajax response (for better user experience)
	var flagArray = $('.flag-'+qid);
  if($(flagArray[0]).hasClass('flagged')){
  	for(var f = 0;f < flagArray.length; f++){
  		$(flagArray[f]).removeClass("flagged");
  	}
  }
  else{
  	for(var f = 0;f < flagArray.length; f++){
  		$(flagArray[f]).addClass("flagged");
  	}
  }

  $.ajax({
	url: '<?php echo site_url('Communication/saveDownvotes'); ?>',
	method: 'POST',
	data: 'qid='+qid,
	success: function(result)
	{
		{
			if(result==0 && !($(flagArray[0]).hasClass('flagged'))){
				for(var f = 0;f < flagArray.length; f++){
			  		$(flagArray[f]).addClass("flagged");
			  	}
			}
			else if(result == 1 && ($(flagArray[0]).hasClass('flagged'))){
				for(var f = 0;f < flagArray.length; f++){
			  		$(flagArray[f]).removeClass("flagged");
			  	}
			}
		}
	}
  });
}

function follow(qid)
{
	var followArray = $('.follow-'+qid);
	if($(followArray[0]).hasClass('followed'))
	{
		for(f = 0;f < followArray.length;f++){
			$(followArray[f]).removeClass("followed");
		}
	}
	else
	{
		for(f = 0;f < followArray.length;f++){
			$(followArray[f]).addClass("followed");
		}
  	}
	var flag=0;
	$.ajax({
		url: '<?php echo site_url('Communication/updateFollowPreference'); ?>',
		method: 'POST',
		data: 'qid='+qid+'&flag='+flag,
		async: false,
		success:function(result)
		{
			updateupvote(qid);
			$.ajax({
				url: '<?php echo site_url('Communication/getFollowPreference'); ?>',
				method: 'POST',
				data: 'qid='+qid,
				async: false,
				success:function(result)
				{
				  if(result==1 && !($(followArray[0]).hasClass('followed')))
				  {
				  	//user has followed the question
					for(f = 0;f < followArray.length;f++){
						$(followArray[f]).addClass("followed");
					}
				  }
				  else if(result==0 && ($(followArray[0]).hasClass('followed')))
				  {
				  	//user has unfollowedthe qustion.
					for(f = 0;f < followArray.length;f++){
						$(followArray[f]).removeClass("followed");
					}
				  }
				}
			  });
			// id of the element, +1 icon is follow-qid.
		 	//add classes here.
		}
	  });
}


function savequescomment()
{
  var comment=$('#comment').val();
  if(comment==="")
  {
	$("#errorcq").text("Comment cannot be blank");
	$('#commentqdiv').addClass("has-error");
  }
  else
  {
	$("#errorcq").text("");
	$('#commentqdiv').removeClass("has-error");
	var qid=$('#qid').val();
	$.ajax({
	  url:'<?php echo site_url('Communication/saveCommentsQuestion');?>'+"/"+qid+"/"+comment,
	  success:function(data)
	  {
		if(data==1)
		{
		  showCommentsQues(qid);
		}
		else
		{
		  alert("There is some error");
		}
	  }
	});
  }
}
function saveanscomment(aid)
{
  var comment=$('#commenta_'+aid).val();
  if(comment==="")
  {
	$('#commenta_'+aid+'div').addClass("has-error");
	$("#errorc_"+aid).text("Comment cannot be blank");
  }
  else
  {
	$('#commenta_'+aid+'div').removeClass("has-error");
	$("#errorc_"+aid).text("");
	$.ajax({
	  url:'<?php echo site_url('Communication/saveCommentsAnswer');?>'+"/"+aid+"/"+comment,
	  success:function(data)
	  {
		if(data==1)
		{

		  showCommentsAns(aid);
		  //location.reload();
		}
		else
		{
		  alert("There is some error");
		}
	  }
	  });
  }
}
function showCommentsQues(qid)
{
	$.ajax({
		url:'<?php echo site_url('Communication/getCommentsQuestion');?>'+"/"+qid,
		success:function(response)
		{
			response=JSON.parse(response);
			term="";
			for(var i=0;i<response.length;i++)
			{
				row=response[i];
				term=term+'<div class="comment-row"><span class="comment">'+row.comment+'  -</span><span class="comment-author"><a href="www.scholarfact.com/User/profile/'+encode_id(row.CID)+')">'+row.Display_Name+'</a></span><span class="comment-time"> '+row.cr_dt+'</span></div>';
			}
			term=term+'<div id="commentdiv" class="flex-container"><div class="flex-5"><paper-textarea type="text" id="comment" placeholder="Start writing your comment" required></paper-textarea></div><div class="flex"><paper-button id="commentBtn" onclick="savequescomment()">Comment</paper-button></div></div><p id="errorcq" style="color:red"></p></div>';
			$('#commentqdiv').html("");
			$('#commentqdiv').append(term);
		}
	});
}

function showCommentsAns(aid)
{
	$.ajax({
		url:'<?php echo site_url('Communication/getCommentsAnswer');?>'+"/"+aid,
		success:function(response)
		{
			response=JSON.parse(response);
			term="";
			for(var i=0;i<response.length;i++)
			{
				row=response[i];
				term=term+'<div class="comment-row"><span class="comment">'+row.comment+'  -</span><span class="comment-author"><a href="www.scholarfact.com/User/profile/'+encode_id(row.CID)+')">'+row.Display_Name+'</a></span><span class="comment-time"> '+row.cr_dt+'</span></div>';
			}

			term=term+'<div class="ansCommentInput flex-container">';

			term=term+'<div class="flex-5"><paper-textarea type="text" id="commenta_'+aid+'" placeholder="Start writing your comment" required="required"></paper-textarea></div>';

			term=term+'<div class="flex"><paper-button class="ansCommentBtn" onclick="saveanscomment('+aid+');">Comment</paper-button></div></div><p id="errorc_'+aid+'" style="color:red"></p></div></div>';
			$('#topAnswerCommentDiv'+aid).html("");
			$('#topAnswerCommentDiv'+aid).append(term);
		}
	});
}

function expandTopAnswer(span)
{
	$span = $(span);
	$span.next().css("display","inline");
	$span.css("display","none");
}

function initializereadmore()
	{
	  // Configure/customize these variables.
	  var showChar = 150; // How many characters are shown by default
	  var ellipsestext = "...";
	  var moretext = "(more)";
	  // var lesstext = "(less)";
	  $('.topAnswerText').each(function() {
	    var content = $(this).html();

	    if (content.length > showChar) {

	      var c = content.substr(0, showChar);
	      var h = content.substr(showChar, content.length - showChar);

	      var html = c + '<span><span class="morelink" onclick="expandTopAnswer(this)" style="cursor:pointer;color:#2b6dad"> ... ' + moretext + '</span><span class="morecontent" style="display:none">' + h + '</span>&nbsp;&nbsp;</span>';

	      $(this).html(html);
	    }

	  });
	}

  function toggle(selector) {
    document.querySelector(selector).toggle();
  }

  document.querySelector('template[is=dom-bind]').isExpanded = function(opened) {
    return String(opened);
  };
	
</script>
		
	
</body>
</html>