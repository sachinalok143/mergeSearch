<?php

/*
The Search Controller used for SERP
FUNCTIONS
1. index - Main Search URL takes the input query and calls the search result function
2. search_result() - Takes the query term and origin (Commn or Other) and generate initial result of All section
3. search_college_api() - API to search for colleges based on a given term
4. leastupvotes - @Akhilesh
5. maxupvotes
6. SearchPage
7. search_suggestions() - To generate suggestions while the user is typing
9. Updatefilters() 
8. SubStrings() - Generate multiple tags from search query
*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller
{
	
	/*
	Main Search Url, calls search_result() function with origin set on the basis of HTTP_REFERRER to differentiate between call from communication and call from normal platform.
	*/

	
	public function index()
	{		
	$queryTerm = htmlspecialchars($this->input->get('query'));
		$origin = $_SERVER['HTTP_REFERER'];
		if(strpos($origin,"Communication") === false)
			$origin = 1;	//Normal Search
		else
			$origin = 2;	//Communication First
		$this->search_result($queryTerm,$origin);
	
	}

	/*
		Function adds a search term to the current search query which is saved via Session. search_result function is then called with new query string;
	*/
	public function add_filter($term)
	{
		$_SESSION['searchQuery'] = $_SESSION['searchQuery']." ".$term;
		$this->search_result($_SESSION['searchQuery'],1);
	}

	/*
		Function removes a search term to the current search query which is saved via Session. search_result function is then called with new query string;
	*/
	public function remove_filter($term)
	{
		$_SESSION['searchQuery'] = str_replace($term," ",$_SESSION['searchQuery']);
		$this->search_result($_SESSION['searchQuery'],1);
	}



	/*
	Input : Query Term which has to be searched
	Output : Json encoded array containing college order, college data, discussions order.
	Logic :	It calls function search_college() and search_discussion() of search_model
	*/
	public function search_result($queryTerm,$origin = 1)
	{
		$_SESSION['psycho_node']= NULL;
		$_SESSION['psycho_flag']= NULL;
		$_SESSION['result_count'] = 1;
		$this->load->model('search_model');
		$this->load->model('Comms_model');
		
		$tag_name = 0;
        //Get query from query table
		if($queryTerm=='')
		{
			$tagsArray= array();
			                    $allfilters = array();
								$allfilters = $this->search_model->getAllfilters();
								$Parents = $allfilters[0];
								$Streams = $allfilters[1];
								$Degrees = $allfilters[2];
								$Majors = $allfilters[3];
								$Countries = $allfilters[4];
								$Fees = $allfilters[5];
								 $rank=$this->search_model->ranking($tagsArray,1);
			                     $fee =$this->search_model->getfield($rank,104);
			$data =  array( 'filter' => $filter, 'Parents' => $Parents,  'Streams' => $Streams,  'Degrees' => $Degrees, 'Majors' => $Majors, 'Countries' => $Countries , 'fees' => $fees,'rank'=>$rank,'fee'=>$fee,'tags'=> $tagsArray ,'tag_name' => $tag_name); 
							    
			$this->load->view('search_result',$data);
		}
			else
		{
        $getquery = $this->Comms_model->getquery_search($queryTerm);	
        		
		
         

		//Discussion and question searches not working 
	/*	$data['discussion'] = $this->search_model->search_discussion($queryTerm);
		$data['filters'] = $this->search_model->showFilters();
		$data['origin'] = $origin;  */
		$this->load->library('session');
		$_SESSION['searchQuery'] = $queryTerm;
		//print_r($this->session);
		if($this->session->is_logged_in!=0 || !isset($this->session->is_logged_in))
		{
			$cid=$this->session->cid;
		}
		else
		{
			$cid=-1;
		}
		//Currently this section is not active
	/*	foreach($data['discussion']['questions'] as $row)
		{
			$row->answer=$this->Comms_model->getTopRatedAnswer($row->qid,$cid);
			if(!empty($row->answer))
			{
				if($row->answer->comments==1)
				{
					$row->answer->comments=$this->Comms_model->getCommentsAnswer($row->answer->ansid);
				}
				$row->answer->images=$this->Comms_model->getImage(-1,$row->answer->ansid);
				$query=$this->Comms_model->getVotedUserAnswer($cid,$row->answer->ansid);
				if(empty($query->result()))
				{
					$row->answer->upvoted=2;
				}
				else
				{
					foreach ($query->result() as $key)
					{
						$flag=$key->upvoted;
					}
					$row->answer->upvoted=$flag;
				}
			}
			$query=$this->Comms_model->getFollowPreference($cid,$row->qid);
			if(empty($query->result()))
			{
				$row->followed=0;
			}
			else
			{
				foreach($query->result() as $pref)
				{
					$row->followed=$pref->follow;
				}
			}

			$query=$this->Comms_model->getVotedUser($cid,$row->qid);
			if(empty($query->result()))
			{
				$row->question_upvoted=2;
			}
			else
			{
				foreach ($query->result() as $key)
				{
					$row->question_upvoted=$key->upvoted;
				}
			}

		}   */
	   
	    if($getquery->num_rows())
		{
								$queryrow=$getquery->result(); 
								$filter = $queryrow[0]->filter;
								$tags = array();
								$temp_tag = $queryrow[0]->tag;
								$tags = explode(',',$temp_tag);
								$sortby = $queryrow[0]->sortby;
								//code for auto filters check
								
								$filter=preg_split("/[@,]/",$filter);
								//$filter = array_map('strtolower', $filter);
								$i=0;
								$key=array();
								$value=array();
								foreach($filter as $val)
								{
									if($i%2==1)
									{
										$value[]=$val;
									}
									else
									{
										$key[]=$val;
									}
									$i++;
								}
								$filter = array_combine($key, $value);
								$stream = array();$major = array();$degree = array();$country = array();
							    if($filter['Stream']!=NULL)	{$stream = explode( '#', $filter['Stream'] );}
								if($filter['Majors']!=NULL)	{$major = explode( '#', $filter['Majors'] );}
								if($filter['Degrees']!=NULL){$degree = explode( '#', $filter['Degrees'] );}
								if($filter['Country']!=NULL){$country = explode( '#', $filter['Country'] );}
								
								//code for all filters
								$allfilters = array();
								$allfilters = $this->search_model->getAllfilters();
								$Parents1 = $allfilters[0];
								$Streams1 = $allfilters[1];
								$Degrees1 = $allfilters[2];
								$Majors1 = $allfilters[3];
								$Countries1 = $allfilters[4];
								$Fees = $allfilters[5];
								
		
								
								
								$collegelistid=$this->search_model->filterslist(array_values($stream),array_values($major),array_values($degree),array_values($country));
								$rank=$this->search_model->filters_ranking(array(),array_values($collegelistid),1,'');
									
								if($queryrow[0]->psycho_node!=NULL)
								{
									//$_SESSION['search_query'] = $queryTerm;
									$_SESSION['psycho_node'] = $queryrow[0]->psycho_node;
									$_SESSION['psycho_flag'] = $queryrow[0]->psycho_flag;
									$rank = $this->search_model->psycho_ranking($rank,$queryrow[0]->psycho_node,$queryrow[0]->psycho_flag);
									//$tag_name = $this->search_model->get_tagname($queryrow[0]->psycho_node);
									$tag_name = 1;
								}
								$fee =$this->search_model->getfield($rank,104);
							     if($temp_tag!=NULL)
								 {
									 $data = array('rank'=>$rank,'fee'=>$fee,'tags'=>$tags, 'fees' => $fees, 'Parents' => $Parents1,'Streams'=>$Streams1,'Degrees'=>$Degrees1,'Majors'=>$Majors1,'Countries'=>$Countries1,'mark_stream'=>$stream,'mark_majors'=>$major,'mark_degree'=>$degree,'mark_country'=>$country,'tag_name' => $tag_name);	
			                     }
								 else
								 {
									  $data = array('rank'=>$rank,'fee'=>$fee, 'fees' => $fees, 'Parents' => $Parents1,'Streams'=>$Streams1,'Degrees'=>$Degrees1,'Majors'=>$Majors1,'Countries'=>$Countries1,'mark_stream'=>$stream,'mark_majors'=>$major,'mark_degree'=>$degree,'mark_country'=>$country,'tag_name' => $tag_name);	
			                     }
			$this->load->view('search_result',$data); 
		}
		 else 
		 {
			 //code for all filters
								$allfilters = array();
								$allfilters = $this->search_model->getAllfilters();
								$Parents = $allfilters[0];
								$Streams = $allfilters[1];
								$Degrees = $allfilters[2];
								$Majors = $allfilters[3];
								$Countries = $allfilters[4];
								$Fees = $allfilters[5];
								
			 $tagsstring = $this->search_model->removekeywords( $queryTerm);
			 $tagsArray = $this->SubStrings($tagsstring);
			   for($i=0;$i<sizeof($tagsArray);$i++)
			 {   
				 $temp = explode(" ",$tagsArray[$i]);
				 if(sizeof($temp)>1)
				 {
				 if($this->search_model->preposition($temp[0]))
				 {
					 array_shift($temp);
				 }
				  if($this->search_model->preposition($temp[sizeof($temp)-1]))
				 {
					 array_pop($temp);
				 }
				 $tagsArray[$i] = implode(' ',$temp);
				 }
				 if(sizeof($temp)==1)
				 {
					 if($this->search_model->preposition($temp[0]))
					$tagsArray[$i] = '';
				 }
			 }
			// array_unshift($ftags,$queryTerm);
			 //$rank=$this->search_model->ranking($tags);
			 $sorting = 1;
			  $temp = array_values($tagsArray);
			  $lengths = array_map('str_word_count', $temp);
			$maxLength = max($lengths);
			$index = array_search($maxLength, $lengths);
			$majorTag = $temp[$index];
			   $temp2 = array_values($tagsArray);
			  $tags2 = array();
			 for($i=0;$i<sizeof($temp2);$i++){
				$check_tag = $this->search_model->check_tags($temp2[$i]);
				if($check_tag==1)
				{
					$temp  = array_diff($temp, array($temp2[$i]));
				}
			 }
			 $rank=$this->search_model->ranking(array_values($temp),$sorting,$majorTag);
			 $fee =$this->search_model->getfield($rank,104);
			// $data = array('hi'=>$this->search_model->ranking($tags));
			//$tags=$this->Comms_model->getAllTags($queryTerm);
			//Get college result
			//$data = $this->search_model->search_college($queryTerm,0,6);
			$data =  array( 'filter' => $filter, 'Parents' => $Parents,  'Streams' => $Streams,  'Degrees' => $Degrees, 'Majors' => $Majors, 'Countries' => $Countries , 'fees' => $fees,'rank'=>$rank,'fee'=>$fee,'tags'=> $tagsArray,'tag_name' => $tag_name ); 
							    
			$this->load->view('search_result',$data);
		 }
	}
	}

	/*
	Input : POST variable Query, Page No. and Filters
	Output : Search results based on filters.
	Logic : Calls search_college() function in search_model
	*//*
	public function search_college_api()
	{
		$queryTerm = htmlspecialchars($this->input->post('query'));
		$page = htmlspecialchars($this->input->post('page'));
		$filters = $this->input->post('filters');
		$filters = json_decode($filters);
		$this->load->model('search_model');
		$data = $this->search_model->search_college($queryTerm,$page,9,$filters);
		//If clicks on getMore Then Use Existing Filters, else get New Filters, reset page and then search.
		echo json_encode($data);
	}*/

	public function leastupvotes($a, $b)
	{
		if($a->upvotes < $b->upvotes)
			return -1;
		else if($a->upvotes==$b->upvotes)
			return 0;
		else
			return 1;
	}
	public function mostupvotes($a, $b)
	{
		if($a->upvotes > $b->upvotes)
			return -1;
		else if($a->upvotes==$b->upvotes)
			return 0;
		else
			return 1;
	}

	public function SearchPage($sort='default',$rank=-1)
	{
		$this->load->model('search_model');
		$this->load->model('Comms_model');
		$this->load->library('session');
		if($this->session->is_logged_in!=0)
		{
			$cid=$this->session->cid;
		}
		else
		{
			$cid=-1;
		}
		$searchQuery = strtolower(htmlspecialchars($this->input->get('query')));
		if($sort=='default')
			$data=$this->search_model->search_discussion($searchQuery,$sort,$rank);
		else
		{
			if($sort=='leastupvotes')
			{
				$data=$this->search_model->search_discussion($searchQuery,$sort,$rank);
				usort($data['questions'],array("Search","leastupvotes"));
			}
			else if($sort=='mostupvotes')
			{
				$data=$this->search_model->search_discussion($searchQuery,$sort,$rank);
				usort($data['questions'],array("Search","mostupvotes"));
			}
		}
		//print_r($data);
		$comments=array();
		foreach ($data['questions'] as $row)
		{
			$row->answer=$this->Comms_model->getTopRatedAnswer($row->qid,$cid);
			if(!empty($row->answer))
			{
				if($row->answer->comments==1)
				{
					$query=$this->Comms_model->getCommentsAnswer($row->answer->ansid);
					foreach ($query->result() as $key)
					{
						array_push($comments,$key);
					}
					$row->comments=$comments;
				}

				$query=$this->Comms_model->getVotedUserAnswer($cid,$row->answer->ansid);
				if(empty($query->result()))
				{
					$row->answer->upvoted=2;
				}
				else
				{
					foreach ($query->result() as $key)
					{
						$flag=$key->upvoted;
					}
					$row->answer->upvoted=$flag;
				}
			}

			$query=$this->Comms_model->getFollowPreference($cid,$row->qid);
			if(empty($query->result()))
			{
				$row->followed=0;
			}
			else
			{
				foreach($query->result() as $pref)
				{
					$row->followed=$pref->follow;
				}
			}

			$query=$this->Comms_model->getVotedUser($cid,$row->qid);
			if(empty($query->result()))
			{
				$row->question_upvoted=2;
			}
			else
			{
				foreach ($query->result() as $key)
				{
					$row->question_upvoted=$key->upvoted;
				}
			}

		}
    
	
		echo json_encode($data);
	}


	/*
	Function that takes the search query and returns the search results.
	Input : POST Variable search
	Output : Array of search suggestion of 2 colleges 
	*/
	public function search_suggestions()
	{
		$origin = 1;
		$expected = array(2,2);
		

		$this->load->model('Comms_model');
		$this->load->model('Search_model');
		$this->load->model('College_model');
		$data=strtolower($this->input->post('search'));
		$search=array();
		$corpus=array();
		$query=array();
		$tags=$this->Comms_model->getAllTags($data);
	   // $country=$this->Comms_model->getCountry($data);
		//$getfilters=$this->Comms_model->getfilters($data);
		$getquery = $this->Comms_model->getquery($data);
		//$sortby=1;
		if($tags->num_rows())
		{
				foreach($tags->result() as $row)
						{
							array_push($corpus,strtolower($row->primary_college));
						}
				$i=0;
				//$query = $this->Search_model->search_suggestion_college($data);
				foreach($tags->result() as $row)
						{
							if($i>1) // Condition to check for first two colleges matching
								break;
							array_push($search, array("profile",$corpus[$i],site_url('College/details/'.$this->College_model->college_encode_id($corpus[$i]))));
							$i++;
						}
        }
		else{
				if($getquery->num_rows())
				{
					foreach($getquery->result() as $queryrow)
						{
								array_push($query,strtolower($queryrow->query));
						}
					$j=0;
					foreach($getquery->result() as $queryrow)
						{
							if($j>1) // Condition to check for first two colleges matching
								break;
							array_push($search, array("",$query[$j],base_url('search/?query='.$query[$j])));
							$j++;
						}
				}
				/*  if(!$getquery->num_rows())
				{
					//$savequery = $this->Comms_model->savequery($data,$getfilters,0,0);
							//array_push($search, array("profile",$data));
						
				}  */
		}
 
	/*	$match_results=$this->Search_model->get_similar_documents($data,$corpus);
		$found = 0;
		$i=0;
		/*
		foreach ($match_results as $key => $value) {
			if($i<$expected[0])
			{
				$tagid=$this->Comms_model->ifTagisCollege($corpus[$key]);
				foreach ($tagid->result() as $row)
				{
					$id=$row->colg_id;
					
				}
				$this->load->model("college_model");
				array_push($search, array("profile",$corpus[$key],site_url('College/details/'.$this->college_model->id_encode($id))));
				$i++;
			}
		}*/
		

		


		/*$found+=$i;

		$i=0;
		foreach ($match_results as $key => $value) {
			if($i<$expected[1])
			{
				array_push($search, array("topic",$corpus[$key],site_url('Communication/showTagPage?tid='.$corpus[$key])));
				$i++;
			}
		}

		$found+=$i;

		$quesids=array();
		$corpus=array();

		$ques=$this->Comms_model->getAllQuestions("");
		foreach($ques->result() as $row)
		{
			array_push($corpus,strtolower($row->question));
			array_push($quesids,$row->qid);
		}

		$match_results=$this->Search_model->get_similar_documents($data,$corpus);
		$i=0;

		foreach ($match_results as $key => $value) {
			if($i<(5-$found))	//Maximum is 5
			{
				array_push($search, array("question",$corpus[$key],site_url('Communication/showQuestion?qid='.$quesids[$key])));
				$i++;
			}
		}
		*/
		//if origin is Communication then reverse the array
		if($origin == 2)
			$search = array_reverse($search);

		echo json_encode($search);
	}
	
	/*
	Function to update college list while implementing tags
	Input : POST Variable tags through ajax
	Output : Array of colleges
	*/
	function UpdateCollegeList()
	{    
	    if($_POST['sorting'])
		{
			$sorting = $_POST['sorting'];
		}
		else
		{
			$sorting = 1;
		}
		$input = $_POST['tags'];
		
		
		$this->load->model("search_model");
		$rank=$this->search_model->ranking($tagsArray,$sorting);
		$fee =$this->search_model->getfield($rank,104);
		echo json_encode(array('rank'=>$rank,'fee'=>$fee));	
	}
	
	function UpdateCollegeListsorting()
	{
		$tag_name = 0;
		$this->load->model("search_model");
		if($_POST['sorting'])
		{
			$sorting = $_POST['sorting'];
		}
		else
		{
			$sorting = 1;
		}
		$tags  = $_POST['tags'];
		$lengths = array_map('str_word_count', $tags);
			$maxLength = max($lengths);
			$index = array_search($maxLength, $lengths);
			$majorTag = $tags[$index];
		$temp = array_values($tags);
			   $temp2 = array_values($tags);
			  $tags2 = array();
			/* for($i=0;$i<sizeof($temp2);$i++){
				$check_tag = $this->search_model->check_tags($temp2[$i]);
				if($check_tag==1)
				{
					$temp  = array_diff($temp, array($temp2[$i]));
				}
			 }*/
			 $tags = $temp;
		$rank=$this->search_model->ranking($tags,$sorting,$majorTag);
		if($_SESSION['psycho_node']!=NULL)
		{
			$tag_name=1;
			$rank = $this->search_model->psycho_ranking($rank,$_SESSION['psycho_node'],$_SESSION['psycho_flag']);
		}
		$fee =$this->search_model->getfield($rank,104);
		$size_rank = sizeof($rank);
		if($size_rank<10)
		{ 
	        $load_status=1;
	   }
	 else{
		  if(!$_POST['load'])
		{
	    $result_count = 1;
	    $_SESSION['result_count'] = $result_count;
		$rank = array_values(array_slice($rank, 0,  10)); 
		$fee = array_values(array_slice($fee, 0,  10));
		}
		
	}
	
		echo json_encode(array('rank'=>$rank,'fee'=>$fee, 'load_status'=>$load_status, 'tag_name'=>$tag_name));	
		//echo json_encode($sorting);
	}
	/*
	Function to update college list while implementing filters
	Input : POST Variable tags through ajax
	Output : Array of colleges
	*/
	function Updatefilters()
	{   
	   $tag_name = 0;
		$this->load->model("search_model");
		if($_POST['sorting'])
		{
			$sorting = $_POST['sorting'];
		}
		else
		{
			$sorting = 1;
		}
		$input  = $_POST['filter'];
		$tags  = $_POST['tags'];
		
		  $temp = array_values($tags);
		   $lengths = array_map('str_word_count', $temp);
			$maxLength = max($lengths);
			$index = array_search($maxLength, $lengths);
			$majorTag = $temp[$index];
			   //$temp2 = array_values($tags);
			   
			 // $tags2 = array();
			/* for($i=0;$i<sizeof($temp2);$i++){
				$check_tag = $this->search_model->check_tags($temp2[$i]);
				if($check_tag==1)
				{
					$temp  = array_diff($temp, array($temp2[$i]));
				}
			 }*/
			 $tags = $temp;
		$stream =  $input[0];
		$major = $input[1];
		$degree = preg_replace( "/[^a-zA-Z]/", "", $input[2]);
		array_shift($degree);
		$country = preg_replace( "/[^a-zA-Z]/", "", $input[3]);
		array_shift($country);
		//filter nested loop starts.
		$allfilters = array();
		$allfilters = $this->search_model->getAllfilters();
								$Parents1 = $allfilters[0];
								$Streams1 = $allfilters[1];
								$Degrees1 = $allfilters[2];
								$Majors1 = $allfilters[3];
								$Countries1 = $allfilters[4];
								
	if(sizeof($stream)>0)
		{
			$stream_nest  = $this->search_model->stream_nest($stream);
			if(sizeof($stream_nest[0])==0)
			{
				$stream_nest[0][0] = 'NOT_AVAILABLE';
			}
			if(sizeof($stream_nest[1])==0)
			{
				$stream_nest[1][0] = 'NOT_AVAILABLE';
			}
		}
		//degree clicked
		if(sizeof($degree)>0)
		{
			$degree_nest  = $this->search_model->degree_nest($degree);
			$temp_degree = $degree_nest[0];
			if(sizeof($temp_degree)>0){
			if(sizeof($stream_nest[1])>0)
			{
				$k = 0;$temp = array();
			$stream_nest[2]= array_intersect($stream_nest[2], $degree_nest[1]);
	
			}
			else{
				$stream_nest[1]=$temp_degree;
			}
			}
			else{
				$stream_nest[1][0] = 'NOT_AVAILABLE';
			}
			
		}
	if(sizeof($major)>0)
		{
			$major_nest  = $this->search_model->major_nest($major);
			if(sizeof($major_nest[0])>0){
			$stream_nest[0] = $major_nest[0];
			}
			else{
				$stream_nest[0] = 'NOT_AVAILABLE';
			}
			
			if(sizeof($major_nest[1])>0){
			$stream_major_nest =  $major_nest[1];
			}
			else{
				$stream_major_nest = 'NOT_AVAILABLE';
			}
		}	
		
			for($i=0;$i<sizeof($Streams1);$i++)
		{
			for($j=0;$j<sizeof($stream_major_nest);$j++)
			{
				if($stream_major_nest[$j]==$Streams1[$i]["Node_Name"])
				{
					$Streams1[$i]['value'] = 1;
					break;
				}
				else{
					$Streams1[$i]['value'] = 0;
				}
			}
		}
		for($i=0;$i<sizeof($Degrees1);$i++)
		{
			for($j=0;$j<sizeof($stream_nest[0]);$j++)
			{
				if($stream_nest[0][$j]==$Degrees1[$i]["Node_Name"])
				{
					$Degrees1[$i]['value'] = 1;
					break;
				}
				else{
					$Degrees1[$i]['value'] = 0;
				}
			}
		}
		for($i=0;$i<sizeof($Majors1);$i++)
		{
			for($j=0;$j<sizeof($stream_nest[1]);$j++)
			{
				if($stream_nest[2][$j]==$Majors1[$i]["Node_ID"])
				{
					$Majors1[$i]['value'] = 1;
					break;
				}
				else{
					$Majors1[$i]['value'] = 0;
				}
			}
		}
		  foreach ($Streams1 as $key => $row) 
			 {
					$value[$key]  = $row['value'];
			 }
			 array_multisort( $value, SORT_DESC,$Streams1);
			 
		  foreach ($Majors1 as $key => $row) 
			 {
					$value[$key]  = $row['value'];
			 }
			 array_multisort( $value, SORT_DESC,$Majors1);
			 
			 foreach ($Degrees1 as $key => $row) 
			 {
					$value[$key]  = $row['value'];
			 }
			 array_multisort( $value, SORT_DESC,$Degrees1);
		
		//filter nested loop ends
		$collegelistid=array_values($this->search_model->filterslist($stream,$major,$degree,$country));
		if((sizeof($collegelistid)==0)&&((sizeof($stream)+sizeof($major)+sizeof($degree)+sizeof($country))>0))
		{
			$rank = array();
			$fee = array();
		}
		else{
		if(sizeof($collegelistid))
		{
	    $rank=$this->search_model->filters_ranking($tags,$collegelistid,$sorting,$majorTag);
		if($_SESSION['psycho_node']!=NULL)
		{
			$tag_name = 1;
			$rank = $this->search_model->psycho_ranking($rank,$_SESSION['psycho_node'],$_SESSION['psycho_flag']);
		}
		$fee =$this->search_model->getfield($rank,104);
		
		}
		else{
		 $rank=$this->search_model->ranking($tags,$sorting,$majorTag);
		 if($_SESSION['psycho_node']!=NULL)
		{
			$tag_name = 1;
			$rank = $this->search_model->psycho_ranking($rank,$_SESSION['psycho_node'],$_SESSION['psycho_flag']);
		}
		 $fee =$this->search_model->getfield($rank,104);
		}
		}
		
     $size_rank = sizeof($rank);
		if($size_rank<10)
		{ 
	        $load_status=1;
	    }
	 else{
		 if(!$_POST['load'])
		{
	    $result_count = 1;
	    $_SESSION['result_count'] = $result_count;
		$rank = array_values(array_slice($rank, 0,  10)); 
		$fee = array_values(array_slice($fee, 0,  10));
		 $load_status=3;
		}
		else{
		if($_POST['load']==5){
		$result_count = $_SESSION['result_count'];
	    $result_count++;
	    $_SESSION['result_count'] = $result_count;
		
		$end = $result_count*10;
		if(($end<($size_rank)))
		{
		$rank = array_values(array_slice($rank, 0,  $end)); 
		$fee = array_values(array_slice($fee, 0,  $end)); 
		}
		else if(($end >=($size_rank)))
		{
		$rank = array_values(array_slice($rank, 0,$size_rank)); 
		$fee = array_values(array_slice($fee,0,$size_rank)); 
		$load_status=11;
		}
		}
		else if($_POST['load']==6){
		$result_count = $_SESSION['result_count'];
	    $result_count--;
	    $_SESSION['result_count'] = $result_count;
		$end = ($result_count)*10 ;
		$check = $end-10;
	    if(($check>0))
		{
		$rank = array_values(array_slice($rank, 0,  $end)); 
		$fee = array_values(array_slice($fee, 0, $end)); 
		}
		else if($check==0)
		{
	    $rank = array_values(array_slice($rank, 0,  $end)); 
		$fee = array_values(array_slice($fee, 0, $end)); 
		$load_status=3;
		}
		else
		{
	    $load_status=3;
		}
		
		}
	 }
	}
	   echo json_encode(array('rank'=>$rank,'fee'=>$fee,'Streams'=>$Streams1,'Degrees'=>$Degrees1,'Majors'=>$Majors1,'Countries'=>$Countries1,'mark_stream'=>$stream,'mark_majors'=>$major,'mark_degree'=>$degree,'load_status'=>$load_status,'count'=>$result_count, 'tag_name'=>$tag_name));	
		//echo json_encode(array('mark_stream'=>$stream,'mark_majors'=>$major,'mark_degree'=>$degree,'country'=>$country));
	}
	
	/*
	Function that takes the search query and returns all of its substrings.
	Input : POST Variable search
	Output : Array of substrings
	*/
	function SubStrings($input)
	{
	$arr = explode(' ', $input);
    $out = array();
    for ($i = 0; $i < count($arr); $i++) 
	{
        for ($j = $i; $j < count($arr); $j++) 
		{
            $out[] = implode(' ', array_slice($arr, $i, $j - $i + 1));
        }       
    }
    return $out;
	}

}
