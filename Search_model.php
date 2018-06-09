<?php

/*
FUNCTIONS
1. showFilters() - Loads the filters available
2. search_college() - Searches college based on given search query
3. comparison() - A custom comparison function to include effect of views in case equal count
4. get_corpus_index() - @Akhilesh
5. get_similar_documents()
6. search_discussions() 
7. search_suggestion_college()
8. getAllfilters()
9. getfee()
10. ranking()
11. filters_ranking()
12. preposition()
13. removekeywords()
14. filterslist()
*/

class Search_model extends CI_Model {

	/*
	Input : query term
	Output : College list that matches term
	*/
	public function search_suggestion_college($term)
	{
		$this->db->select('colg_id');
		$this->db->like('synonym',$term);
		$this->db->group_by('colg_id');
		$data = $this->db->get('synonyms');
		return $data;
	}

	/*
	Input : None
	Output : Json encoded array of various filters along with options available.
	Logic :
	This function selects all the distinct NODE_VALUE(s) for the NODE_NAME specified in the display array.
	*/
	public function newSrh($coll) {
		$result = array();
		$this->db->select('Coll_Name, coll_ID');
		$this->db->order_by('date','desc');
		// $this->db->limit(1);
		$this->db->like('Coll_Name', $coll, 'after');
		$id = $this->db->get('Log_Table');
		$prevId='';
		foreach ($id->result_array() as $row) {
			if ($row['coll_ID']==$prevId) {
				continue;
			}
			if($row['Coll_Name'] != NULL) {
				$val = trim($row['Coll_Name']) . " (" . $row['coll_ID'] . ")" . "<br>" ;
				array_push($result,$val);
				$prevId=$row['coll_ID'];
			}
		}

		/*$this->db->select('primary_college, colg_id');
		$this->db->order_by('date','desc');
		// $this->db->limit(1);
		$this->db->like('Coll_Name', $coll, 'after');
		$id = $this->db->get('Log_Table');
		/*foreach ($id->result_array() as $row) {
			if($row['Coll_Name'] != NULL) {
				$val = trim($row['Coll_Name']) . " (" . $row['coll_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}*/
		return $result;
	}
	public function findAttribute($coll) {
		$result = array();
		$this->db->select('node_Name, node_ID');
		$this->db->where('node_ID >',0);
		$this->db->like('node_Name', $coll, 'after');
		$id = $this->db->get('temp_attr_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['node_Name'] != NULL) {
				$val = $row['node_Name'] . " (" . $row['node_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function mergeStr($search) {
		$result = array();
		$this->db->select('Spath, Slevel, Snode_ID, Snode_Name');
		$this->db->like('Spath', $search, 'both');
		$this->db->order_by('Slevel','asc');
		$id = $this->db->get('Temp_struct_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['Spath'] != NULL) {
				$val = $row['Spath'] . " (" . $row['Snode_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function mergeAttr($search) {
		$result = array();
		$this->db->select('node_Name, level, parent_ID, node_ID');
		$this->db->like('node_Name', $search, 'both');
		$this->db->order_by('level','asc');
		$id = $this->db->get('temp_attr_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['parent_ID'] != NULL) {
				$val = $row['node_Name'] . " (" . $row['node_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function srhOptn($coll) {
		$result = array();
		$this->db->select('*');
		$this->db->like('OP_Text', $coll, 'both');
		$id = $this->db->get('OPTIONTABLE');
		foreach ($id->result_array() as $row) {
			if($row['OP_Text'] != NULL) {
				$val = " (" . $row['OP_ID'] . ") " . $row['OP_Text'] . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function srhQust($coll) {
		$result = array();
		$this->db->select('*');
		$this->db->like('Q_Text', $coll, 'both');
		$this->db->order_by('Q_ID','DESC');
		$id = $this->db->get('QUESTIONTABALE');
		foreach ($id->result_array() as $row) {
			if($row['Q_Text'] != NULL) {
				$val = " (" . $row['Q_ID'] . ") " . $row['Q_Text'];
				$k = $row['Option_Num'];
				for($l=1;$l<=$k;$l++) {
					if($l==1) {
						$a1 = $row['Op1'];
						$this->db->select('*');
						$this->db->where('OP_ID', $a1);
						$id2 = $this->db->get('OPTIONTABLE');
						foreach ($id2->result_array() as $row2) {
							$f1 = $row2['OP_Text'];
							$val = $val . '<span style="font-weight: 700; color: #01579b;"> ' . $f1;
						}
					}
					else {
						$f = 'Op'.$l;
						$a1 = $row[$f];
						$this->db->select('*');
						$this->db->where('OP_ID', $a1);
						$id2 = $this->db->get('OPTIONTABLE');
						foreach ($id2->result_array() as $row2) {
							$f1 = $row2['OP_Text'];
							$val = $val . ', <span style="font-weight: 700; color: #01579b;"> ' . $f1;
						}	
					}
				}
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function linkStr($coll) {
		$result = array();
		$this->db->select('Spath, Snode_ID');
		$this->db->like('Spath', $coll, 'both');
		$this->db->where('Trigger_Ques', NULL);
		$id = $this->db->get('Temp_struct_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['Spath'] != NULL && $row['Snode_ID'] != 0) {
				$val = $row['Spath'] . " (" . $row['Snode_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function linkStrAll($coll) {
		$result = array();
		$this->db->select('Spath, Snode_ID, Trigger_Ques');
		$this->db->like('Spath', $coll, 'both');
		$id = $this->db->get('Temp_struct_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['Spath'] != NULL && $row['Snode_ID'] != 0 && $row['Trigger_Ques']!=NULL) {
				$val = $row['Spath'] . " (" . $row['Snode_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
			else if($row['Spath'] != NULL && $row['Snode_ID'] != 0 && $row['Trigger_Ques']==NULL) {
				$val = $row['Spath'] . " (" . $row['Snode_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function linkAtr($coll) {
		$result = array();
		$this->db->select('node_Name, node_ID');
		$this->db->like('node_Name', $coll, 'both');
		$this->db->where('Trigger_Ques', NULL);
		$id = $this->db->get('temp_attr_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['node_Name'] != NULL && $row['node_ID'] != 0) {
				$val = $row['node_Name'] . " (" . $row['node_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function linkAtrAll($coll) {
		$result = array();
		$this->db->select('node_Name, node_ID, Trigger_Ques');
		$this->db->like('node_Name', $coll, 'both');
		$id = $this->db->get('temp_attr_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['node_Name'] != NULL && $row['node_ID'] != 0 && $row['Trigger_Ques']!=NULL) {
				$val = $row['node_Name'] . " (" . $row['node_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
			else if($row['node_Name'] != NULL && $row['node_ID'] != 0 && $row['Trigger_Ques']==NULL) {
				$val = $row['node_Name'] . " (" . $row['node_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function mapStr($coll) {
		$result = array();
		$this->db->select('Snode_Name, Snode_ID');
		$this->db->where('Slevel',1);
		$id = $this->db->get('Temp_struct_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['Snode_Name'] != NULL) {
				$val = $row['Snode_Name'] . " (" . $row['Snode_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function mapDeg($coll) {
		$result = array();
		$this->db->select('Snode_Name');
		$this->db->distinct();
		$this->db->where('Slevel',2);
		$id = $this->db->get('Temp_struct_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['Snode_Name'] != NULL) {
				$name = $row['Snode_Name'];
				$this->db->select('Snode_ID');
				$this->db->where('Snode_Name',$name);
				$id2 = $this->db->get('Temp_struct_nodetable');
				foreach ($id2->result_array() as $row2) {
					$val = $row['Snode_Name'] . " (" . $row2['Snode_ID'] . ")" . "<br>" ;
				}
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function mapMajor($coll) {
		$result = array();
		$this->db->select('Spath, Snode_ID');
		$this->db->where('Slevel',3);
		$id = $this->db->get('Temp_struct_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['Spath'] != NULL) {
				$val = $row['Spath'] . " (" . $row['Snode_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function majorSrh($major) {
		$result = array();
		$this->db->select('*');
		$this->db->like('Spath', $major, 'both');
		$id = $this->db->get('Temp_struct_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['Spath'] != NULL) {
				$val = $row['Spath'] . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function majorSrhPath($major) {
		$result = array();
		$this->db->select('*');
		$this->db->where('Slevel',2);
		$this->db->like('Spath', $major, 'both');
		$id = $this->db->get('Temp_struct_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['Spath'] != NULL) {
				$val = $row['Spath'] . " (" . $row['Snode_ID'] . ")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}

	public function majorSrh2($major) {
		$result = array();
		$i=0;
		$this->db->select('*');
		$this->db->like('major_Name', $major, 'after');
		$id = $this->db->get('dummy_temp_struct');
		foreach ($id->result_array() as $row) {
			if($row['major_Name'] != NULL) {
				$val = $row['node_Name'] . " >> " . $row['degree_Name'] . " >> " . $row['major_Name'] . "<br>" ;
				array_push($result,$val);
			}
			$i++;
		}
		if($i>0) {
			return $result;
		}
		else {
			$this->db->select('*');
			$this->db->like('node_Name', $major, 'after');
			$id = $this->db->get('dummy_temp_struct');
			foreach ($id->result_array() as $row) {
				if($row['node_Name'] != NULL) {
					$val = $row['node_Name'] . " >> " . $row['degree_Name'] . " >> " . $row['major_Name'] . "<br>" ;
					array_push($result,$val);
				}
				$i++;
			}
			if($i>0) {
				return $result;
			}
			else {
				$this->db->select('*');
				$this->db->like('degree_Name', $major, 'after');
				$id = $this->db->get('dummy_temp_struct');
				foreach ($id->result_array() as $row) {
					if($row['degree_Name'] != NULL) {
						$val = $row['node_Name'] . " >> " . $row['degree_Name'] . " >> " . $row['major_Name'] . "<br>" ;
						array_push($result,$val);
					}
					$i++;
				}
				if($i>0) {
					return $result;
				}
			}
		}
	}
	public function attrSrh($attr) {
		$result = array();
		$this->db->select('*');
		$this->db->like('node_Name', $attr, 'both');
		$id = $this->db->get('temp_attr_nodetable');
		foreach ($id->result_array() as $row) {
			if($row['node_Name'] != NULL) {
				$val = $row['node_Name'] . " (" . $row['node_ID'] .")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function secSrh($sec) {
		$result = array();
		$this->db->select('*');
		$this->db->like('section_Name', $sec, 'after');
		$id = $this->db->get('section');
		foreach ($id->result_array() as $row) {
			if($row['section_Name'] != NULL) {
				$val = $row['section_Name'] . " (" . $row['section_ID'] .")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function subsecSrh($subsec, $sec2) {
		$result = array();
		$this->db->select('*');
		$this->db->where('section_Name',$sec2);
		$this->db->like('subsection_Name', $subsec, 'after');
		$id = $this->db->get('section');
		foreach ($id->result_array() as $row) {
			if($row['subsection_Name'] != NULL) {
				$val = $row['subsection_Name'] . " (" . $row['subsection_ID'] .")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}
	public function parsecSrh($parsec) {
		$result = array();
		$this->db->select('*');
		$this->db->like('section_Name', $parsec, 'after');
		$id = $this->db->get('section');
		foreach ($id->result_array() as $row) {
			if($row['section_Name'] != NULL) {
				$val = $row['section_Name'] . " (" . $row['section_ID'] .")" . "<br>" ;
				array_push($result,$val);
			}
		}
		return $result;
	}

	public function showfilters()
	{
		$finalResult = array();
		$display = ["Streams/Schools","Degree","Majors"];	//Columns to display
		foreach ($display as $response)
		{
			$result = array();
			$result['label'] = $response;
			$result['options'] = array();
			//array_push($result['options'],"All");
			
			$this->db->distinct();
			$this->db->select('NODE_VALUE');
			$this->db->where('NODE_NAME',$response);
			$query = $this->db->get('table2');
			foreach($query->result() as $row)
			{
				if($row->NODE_VALUE != NULL)
					array_push($result['options'],$row->NODE_VALUE);
			}
			array_push($finalResult,$result);
		}

		$result = array();
		$result['label'] = "Country";
		$result['options'] = array();
		//array_push($result['options'],"All");
		$this->db->distinct();
		$this->db->select('CountryCode');
		$this->db->order_by('CountryCode','DESC');

		$data = $this->db->get('college')->result();
		$this->load->model('college_model');
		foreach($data as $row)
			array_push($result['options'],$this->college_model->getCountry($row->CountryCode));
		array_push($finalResult,$result);
		
		return $finalResult;
	}

	/*
	Input : Search Query and Page No.
	Output : Data of Colleges and Their Order
	Logic : First it searches in NODE_VALUE
	Then it searches in COLLEGE_NAME
	Then it searches in NODE_NAME
	Then it searches in COUNTRY TABLE
	The data corresponding to these colleges is then retrieved.
	*/
	public function search_college($queryTerm,$page = 0,$resultsPerPage = 6,$filters )
	{
		$this->load->model('college_model');
		$this->load->library('session');
		$searchQuery = preg_split('/ /',$queryTerm,-1,PREG_SPLIT_NO_EMPTY);	//Splitting the query term
		$new_search_Query = $searchQuery;
		foreach($searchQuery as $term)
		{
			$query = $this->db->get_where('ignored_keywords',array('keyword' => $term));
			if($query->num_rows())
			{
				$new_search_Query = array_diff($new_search_Query, array($term));
			}

			$this->db->select('primary_name');
			$this->db->where('name',$term);
			$query = $this->db->get('search_synonyms')->row();
			if(isset($query))
			{
				for ($i=0; $i < count($new_search_Query); $i++) { 
					if($new_search_Query[$i] == $term)
						$new_search_Query[$i] = $query->primary_name;
				}
			}
		}
		$searchQuery = $new_search_Query;
		if(sizeof($searchQuery) > 1 )
		{
			array_push($searchQuery, $queryTerm);
		}
		$bitString = str_repeat("0", count($searchQuery));	//Default Found String - All 0's
		$collegeOrder = array();	//Would Store the Score of each college
		$collegeData = array();	//Would Store Data of colleges relevant to us

		$mail=$this->session->email;	//Add To Log Only When Some User is Logged In
		if(!empty($mail))
		{
			$this->load->model('log_model');
			foreach($searchQuery as $term)
			{
				$this->db->like('Node_Name','_'.$term,'before');
				$num = $this->db->count_all_results('NODETABLE');
				if($num>0)
					$this->log_model->addToLogStatic($term,SEARCH_QUERY,TYPE_STRUCTURE);
				else
				{
					$this->db->like('Node_Name','_'.$term,'before');
					$num = $this->db->count_all_results('AttributeNodeTable');
					if($num>0)
						$this->log_model->addToLogStatic($term,SEARCH_QUERY,TYPE_ATTRIBUTE);
					else
					{
						$this->db->where('COLL_NAME',$term);
						$num = $this->db->count_all_results('college');
						if($num>0)
							$this->log_model->addToLogStatic($term,SEARCH_QUERY,TYPE_COLLEGE);
					}
				}
			}
		}

		$colleges = array();
		if(isset($filters) && count($filters)>0)
		{
			$numOfFilters = count($filters);
			foreach($filters as $filter)
			{
				if($filter->label != 'Country')
				{
					$this->db->select('COLL_ID');
					$this->db->where_in('NODE_VALUE',$filter->checked);
					$this->db->where('NODE_NAME',$filter->label);
					$this->db->group_by('COLL_ID');
					$data = $this->db->get('table2')->result();
					foreach($data as $row)
					{
						if(isset($collegeValid[$row->COLL_ID]))
							$collegeValid[$row->COLL_ID] += 1;
						else
							$collegeValid[$row->COLL_ID] = 1;
					}
				}
				else
				{
					$this->db->select('Country_Code');
					$this->db->where_in('Country_Name',$filter->checked);				
					$data = $this->db->get('Country')->result();
					foreach($data as $row)
					{
						$this->db->select('COLL_ID');
						$this->db->where_in('CountryCode',$row->Country_Code);
						$data2 = $this->db->get('college')->result();
						foreach($data2 as $row)
						{
							if(isset($collegeValid[$row->COLL_ID]))
								$collegeValid[$row->COLL_ID] += 1;
							else
								$collegeValid[$row->COLL_ID] = 1;
						}
					}
				}
			}

			foreach($collegeValid as $key=>$value)
			{
				if($value == $numOfFilters)
				{
					array_push($colleges,$key);
				}
			}
		}
		else		//if no filter then all colleges are valid
		{
			$this->db->select('COLL_ID');
			$data = $this->db->get('college')->result();			
			foreach($data as $row)
			{
				array_push($colleges,$row->COLL_ID);
			}		
		}

		//Initialize collegeOrder of each college to 0
		foreach($colleges as $row)
		{
			$collegeOrder[$row] = 0;
			$found[$row] = $bitString;
		}
		if(count($colleges)>0)
		{
			//Search in NODE_VALUE
			$i = 0;
			foreach($searchQuery as $term)
			{
				$this->db->select('COLL_ID,COUNT(COLL_ID) AS COUNT');
				$this->db->like('NODE_VALUE',$term);
				$this->db->where_in('COLL_ID',$colleges);
				$this->db->group_by('COLL_ID');
				$data = $this->db->get('table2')->result();
				foreach($data as $row)
				{
					$found[$row->COLL_ID][$i] = "1";
					$collegeOrder[$row->COLL_ID] =$collegeOrder[$row->COLL_ID] + (count($searchQuery) - $i) + ($row->COUNT*strlen($term));
				}
				$i++;
			}

			//Search in COLLEGE_NAME
			$i=0;
			foreach($searchQuery as $term)
			{
				$this->db->select('colg_id');
				$this->db->like('synonymsym',$term);
				$this->db->where_in('colg_id',$colleges);
				$this->db->group_by('colg_id');
				$data = $this->db->get('synonyms')->result();
				foreach($data as $row)
				{
					$found[$row->colg_id][$i] = "1";
					 $collegeOrder[$row->colg_id]= $collegeOrder[$row->colg_id] + (count($searchQuery) - $i) +strlen($term)*3;
				}
				$i++;
			}
			//Search in NODE_NAME
			$i=0;
			foreach($searchQuery as $term)
			{
				$this->db->select('COLL_ID,COUNT(COLL_ID) AS COUNT');
				$this->db->like('NODE_NAME',$term);
				$this->db->where_in('COLL_ID',$colleges);
				$this->db->group_by('COLL_ID');
				$nodeNameData = $this->db->get('table2')->result();
				foreach($nodeNameData as $row)
				{
					$found[$row->COLL_ID][$i] = "1";
					if(isset($collegeOrder[$row->COLL_ID]))
						$collegeOrder[$row->COLL_ID] = $collegeOrder[$row->COLL_ID] + (count($searchQuery) - $i) + strlen($term)*$row->COUNT;
					else
						$collegeOrder[$row->COLL_ID] = strlen($term)*$row->COUNT;	
				}
				$i++;
			}

			//Search in Country Table
			$i=0;
			foreach($searchQuery as $term)
			{
				$this->db->like('Country_Name',$term);
				$data = $this->db->get('Country')->result();			
				foreach($data as $country)
				{
					$countryCode = $country->Country_Code;
					$this->db->select('COLL_ID');
					$this->db->where_in('COLL_ID',$colleges);
					$this->db->where('CountryCode',$countryCode);
					$data2 = $this->db->get('college')->result();
					foreach($data2 as $row)
					{
						$found[$row->COLL_ID][$i] = "1";
						$collegeOrder[$row->COLL_ID] += (count($searchQuery) - $i) + strlen($term);
					}
				}
				$i++;
			}


		}

		//Sorting the colleges and getting the final Order
		$collegeOrder = array_filter($collegeOrder);	//Removed Zero Entries
		if(count(array_keys($collegeOrder))>0)
		{
			$this->db->where_in('COLL_ID',array_keys($collegeOrder));
			$data = $this->db->get('college')->result();			
		}
		foreach($data as $row)
		{
			$collegeOrder[$row->COLL_ID] = array('count'=>$collegeOrder[$row->COLL_ID],'views' => $row->Views);
		}
		uasort($collegeOrder,array($this,'comparison'));	//Sorting The array -> Based on Views if same count
		$collegeOrderKeysComp = array_keys($collegeOrder);	//All Keys i.e. College ID's
		$totalPages = count($collegeOrderKeysComp)/$resultsPerPage;
		$collegeOrderKeys = array_slice($collegeOrderKeysComp,$page*$resultsPerPage,$resultsPerPage,true);
		$collegeOrderKeys = array_values($collegeOrderKeys);
		$finalResult = array();
		$display = ["Streams/Schools","Degree","Majors"];	//Columns to display
		if(count($collegeOrderKeys)>0)
		{
			foreach($display as $response)
			{
				$label = $response;
				$this->db->select('COLL_ID,NODE_VALUE');
				$this->db->where('NODE_NAME',$response);
				$this->db->where_in('COLL_ID',$collegeOrderKeys);
				$this->db->order_by('COLL_ID','DESC');
				$queryData = $this->db->get('table2')->result();
				$prev = -1;
				$result = array();
				foreach($queryData as $row)
				{
					if($row->COLL_ID != $prev)
					{
						if($prev != -1)
						{
							$finalResult[$prev]["data"][$label] = $result;
							$result = array();
						}
					}
					if($row->NODE_VALUE != NULL)
						array_push($result,$row->NODE_VALUE);
					$prev = $row->COLL_ID;
				}
				if($prev !=-1)
				{
					$finalResult[$prev]["data"][$label] = $result;
				}
			}

			//Country and Name info.
			$this->db->where_in('COLL_ID',$collegeOrderKeys);
			$data = $this->db->get('college')->result();
			foreach($data as $row)
			{
				$finalResult[$row->COLL_ID]["data"]["Country"] = $this->college_model->getCountry($row->CountryCode);
				$finalResult[$row->COLL_ID]["data"]["College_Name"] = $row->COLL_NAME;
			}
		}
		for($i=0;$i<count($collegeOrderKeys);$i++)
			$finalResult[$collegeOrderKeys[$i]]["found"] = $found[$collegeOrderKeys[$i]];
		
		$data = array();
		$data['jsonFinalData'] = json_encode($finalResult);
		$data['jsonCollegeOrder'] = json_encode(array_values($collegeOrderKeys));
		$data['queryTerm'] = $queryTerm;
		$data['query_filters'] = $new_search_Query;
		if($page != 0)
			$data['page'] = $page;
		else
			$data['page'] = 0;
		$data['totalPages'] = $totalPages;

		return $data;
	}

	public function comparison($a,$b)
	{
		if($a['count'] == $b['count'])
		{
			if($a['views'] == $b['views'])
				return 0;
			else
			{
				if($a['views']<$b['views'])
					return 1;
				else
					return -1;
			}
		}
		else
		{
			if($a['count'] < $b['count'])
				return 1;
			else
				return -1;
		}
	}

	function get_corpus_index($corpus = array(), $separator=' ') {

       $dictionary = array();

       $doc_count = array();

       foreach($corpus as $doc_id => $doc) {

           $terms = explode($separator, $doc);

           $doc_count[$doc_id] = count($terms);

           // tf–idf, short for term frequency–inverse document frequency, 
           // according to wikipedia is a numerical statistic that is intended to reflect 
           // how important a word is to a document in a corpus

           foreach($terms as $term) {

               if(!isset($dictionary[$term])) {

                   $dictionary[$term] = array('document_frequency' => 0, 'postings' => array());
               }
               if(!isset($dictionary[$term]['postings'][$doc_id])) {

                   $dictionary[$term]['document_frequency']++;

                   $dictionary[$term]['postings'][$doc_id] = array('term_frequency' => 0);
               }

               $dictionary[$term]['postings'][$doc_id]['term_frequency']++;
           }
       }

       return array('doc_count' => $doc_count, 'dictionary' => $dictionary);
   }

   function get_similar_documents($query='', $corpus=array(), $separator=' '){
       $similar_documents=array();

       if($query!=''&&!empty($corpus)){

           $words=explode($separator,$query);
           $stopwords = array("the","and","an","of");
           $words=array_diff($words, $stopwords);

           $corpus=$this->get_corpus_index($corpus, $separator);

           $doc_count=count($corpus['doc_count']);

           foreach($words as $word) {

               if(isset($corpus['dictionary'][$word])){

                   $entry = $corpus['dictionary'][$word];


                   foreach($entry['postings'] as $doc_id => $posting) {

                       //get term frequency–inverse document frequency
                       $score=$posting['term_frequency'] * log($doc_count + 1 / $entry['document_frequency'] + 1, 2);

                       if(isset($similar_documents[$doc_id])){

                           $similar_documents[$doc_id]+=$score;

                       }
                       else{

                           $similar_documents[$doc_id]=$score;

                       }
                   }
               }
           }

           // length normalise
           foreach($similar_documents as $doc_id => $score) {

               $similar_documents[$doc_id] = $score/$corpus['doc_count'][$doc_id];

           }

           // sort fro  high to low

           arsort($similar_documents);

       }   
      return $similar_documents;
   }
   
   function search_discussion($searchQuery,$sort='default',$rank=-1)
   {
      $quesids=array();
      $corpus=array();
      $data['questions']=array();

      $this->load->library('session');
      $this->load->helper('url');
      $this->load->model('Comms_model');

      $ques=$this->Comms_model->getAllQuestions("");
      foreach($ques->result() as $row)
      {
        	array_push($corpus,strtolower($row->question));
         	array_push($quesids,$row->qid);
      }
      $match_results=$this->get_similar_documents($searchQuery,$corpus);

      if($sort=='default')
      {
         if($rank==-1)
         {
            $i=0;
            foreach ($match_results as $key => $value) 
            {
               if($i<5)
               {
                  $this->session->set_userdata('searchrank',$value);
                  $quesData=$this->Comms_model->getQuestionData($quesids[$key]);
                  foreach ($quesData->result() as $row) 
                  {
                  	array_push($data['questions'], $row);
                  }
                  $i++;
               }
            }
         }
         else
         {
            $i=0;

            foreach ($match_results as $key => $value) 
            {
               if($i<10)
               {
                  if($value<$rank)
                  {
                     $this->session->set_userdata('searchrank',$value);
                     $quesData=$this->Comms_model->getQuestionData($quesids[$key]);
                     foreach ($quesData->result() as $row) 
                     {
                        array_push($data['questions'], $row);
                     }
                     $i++;
                  }
               }
            }
         }
      }
      else
      {
         $i=0;
            foreach ($match_results as $key => $value) 
            {
               if($i<10)
               {
                  if($value<=$rank)
                  {
	                 $this->session->set_userdata('searchrank',$value);
	                 $quesData=$this->Comms_model->getQuestionData($quesids[$key]);
	                 foreach ($quesData->result() as $row) 
	                 {
	                    array_push($data['questions'], $row);
	                 }
	                 $i++;
                  }
               }
            }
      }

      $data['rank']=$this->session->searchrank;
      $data['queryTerm']=$searchQuery;
      return $data;
   }
   
   function getAllfilters()
   {
	   $Streams = $this->db->query("select  Node_Name , Node_ID from NODETABLE where Node_ID IN (select Node_ID + 1  from  NODETABLE  where (Node_Tier %2=1 AND Node_Name = (select Distinct Node_Name from  NODETABLE  where Node_Tier %2=1 LIMIT 0,1)  )) ");
	   $Degrees = $this->db->query("select DISTINCT Node_Name  from  NODETABLE  where Node_ID IN (select Node_ID + 1  from  NODETABLE  where (Node_Tier %2=1 AND Node_Name = (select Distinct Node_Name from  NODETABLE  where Node_Tier %2=1 LIMIT 1,1)  )) ");
	   $Majors = $this->db->query("select  Node_Name  , Node_ID from  NODETABLE  where Node_ID IN (select Node_ID + 1  from  NODETABLE  where (Node_Tier %2=1 AND Node_Name = (select Distinct Node_Name from  NODETABLE  where Node_Tier %2=1 LIMIT 2,1)  )) ");
	   $Parents = $this->db->query("select Distinct Node_Name from  NODETABLE  where Node_Tier %2=1 ");
	   $Countries = $this->db->query("select Distinct Country_Name from Country where Country_Name !='International'");
	   $Fees = $this->db->query("select Node_Name from AttributeNodeTable where Node_ID = 1");
	   return array($Parents->result_array(),$Streams->result_array(),$Degrees->result_array(),$Majors->result_array(),$Countries->result_array(),$Fees->result_array());
   }
   
 /*  function ranking_V1($tags)
   {
	   $query = $this->db->query("SELECT COLL_NAME, MATCH(COLL_NAME) AGAINST('$tags[0]') AS score FROM college WHERE MATCH(COLL_NAME) AGAINST('$tags[0]') ORDER BY score DESC, views DESC");
	   //$result = $query->result_array();
	   
	  for($i=0;$i<=$query->num_rows();$i++)
		
	   {
		   $q1=$query->row($i);
		    $q2=$query->row($i+1);
			$query1 = $this->db->query("SELECT synonym FROM synonyms where primary_college LIKE '$q1->COLL_NAME' ");
		  if ($q1->score == $q2->score)
		   {
			   //query for ith row
			   $query1 = $this->db->query("SELECT synonym FROM synonyms where primary_college LIKE '$q1->COLL_NAME' ");
			   $string1='';
			   for($k=0;$k<=$query->num_rows();$k++)
			   {
				    $k1=$query1->row($k);
				    $string1 = $k1->synonym;
			   }
			   for($k=0;$k<=sizeof($tags);$k++)
			   {
				   if(stripos(strtolower($string1),strtolower($tags[$k]))!== false)
				   {
					  $query->row($i)->score = $query->row($i)->score + 1;
				   }
			   }
			   //query for (i + 1)th row
			   $query2 = $this->db->query('SELECT synonym FROM synonyms where primary_college LIKE "$q2->COLL_NAME"');
			   $string2='';
			   for($k=0;$k<=$query->num_rows();$k++)
			   {
				    $k2=$query1->row($k);
				    $string2 = $k2->synonym;
			   }
			   for($k=0;$k<=sizeof($tags);$k++)
			   {
				   if(stripos(strtolower($string2),strtolower($tags[$k]))!== false)
				   {
					  $query->row($i+1)->score = $query->row($i+1)->score + 1;
				   }
			   }
			   
		   } 
	   }
	//   $query4 = $this->db->query("SELECT Views, MATCH(COLL_NAME) AGAINST('$tags[0]') FROM college ORDER BY score DESC");
	

		 
	   return  $query->result_array();
   } */
  
 //one common function for any type of pnode
 function getfield($rank,$pnode) 
 {
 	$this->load->model('college_model');
   $result=array();

	for($i=0;$i<sizeof($rank);$i++)
	{
	$query = $this->db->query("SELECT * FROM college where COLL_NAME LIKE '".$rank[$i]['COLL_NAME']."'");
	$q1=$query->row();
	$query2 = $this->db->query("SELECT NODE_VALUE FROM table2 where S_Node=0 AND P_NODE = '$pnode' AND COLL_ID = '$q1->COLL_ID' ");
	$sum = 0;$average=0;
	if($query2->num_rows()){
	for($j=0;$j<$query2->num_rows();$j++)
	{
		$sum = $sum + $query2->row($j)->NODE_VALUE;
	}
		$average  = $sum / $query2->num_rows();
		$currency = $this->college_model->get_currency($q1->CountryCode);
		$average  = $currency['currency'].' '.$average;
	}
	else{
		$average='NA';
	}
	array_push($result,$average);
	}
	return $result;
 }
  /*
 function getfee($rank)
 {
 	$this->load->model('college_model');
   $result=array();

	for($i=0;$i<sizeof($rank);$i++)
	{
	$query = $this->db->query("SELECT * FROM college where COLL_NAME LIKE '".$rank[$i]['COLL_NAME']."'");
	$q1=$query->row();
	$query2 = $this->db->query("SELECT NODE_VALUE FROM table2 where S_Node=0 AND P_NODE = 104 AND COLL_ID = '$q1->COLL_ID' ");
	$sum = 0;$average=0;
	if($query2->num_rows()){
	for($j=0;$j<$query2->num_rows();$j++)
	{
		$sum = $sum + $query2->row($j)->NODE_VALUE;
	}
		$average  = $sum / $query2->num_rows();
		$currency = $this->college_model->get_currency($q1->CountryCode);
		$average  = $currency['currency'].' '.$average;
	}
	else{
		$average='NA';
	}
	array_push($result,$average);
	}
	return $result;
 }

 function getsalary($rank)
 {
 	$this->load->model('college_model');
   $result=array();

	for($i=0;$i<sizeof($rank);$i++)
	{
	$query = $this->db->query("SELECT * FROM college where COLL_NAME LIKE '".$rank[$i]['COLL_NAME']."'");
	$q1=$query->row();
	$query2 = $this->db->query("SELECT NODE_VALUE FROM table2 where S_Node=0 AND P_NODE = 143 AND COLL_ID = '$q1->COLL_ID' ");
	$sum = 0;$average=0;
	if($query2->num_rows()){
	for($j=0;$j<$query2->num_rows();$j++)
	{
		$sum = $sum + $query2->row($j)->NODE_VALUE;
	}
		$average  = $sum / $query2->num_rows();
		$currency = $this->college_model->get_currency($q1->CountryCode);
		$average  = $currency['currency'].' '.$average;
	}
	else{
		$average='NA';
	}
	array_push($result,$average);
	}
	return $result;
 }
 */
 /* function ranking_V2($tags)
   {  $rank =array();
	  $query = $this->db->query("SELECT DISTINCT primary_college FROM synonyms");
		 for($i=0;$i<sizeof($query->result());$i++)
		 {
			 $q1=$query->row($i);
			 $rank[$i]['COLL_NAME'] = $q1->primary_college;
			 $rank[$i]['score'] = 0;
			
			  $query2 = $this->db->query("SELECT synonym FROM synonyms WHERE primary_college LIKE '$q1->primary_college'");
			  for($j=0;$j<sizeof($query2->result());$j++)
			  {
				  $q2=$query2->row($j);
				  //if(count(array_filter($tags, create_function('$e','return strstr("'.$q2->synonym.'", $e);')))>0)
					  if(in_array($q2->synonym, $tags))
				{

					  $rank[$i]['score'] = $rank[$i]['score'] + 1;
				}
			  } 
		 }
		 foreach ($rank as $key => $row) 
		 {
				$score[$key]  = $row['score'];
		 }
		 array_multisort( $score, SORT_DESC,$rank);
	   return $rank ;
   }
   */
  
  /* function ranking_V3($tags,$sorting)
   {
	   $rank = array();
	   $cluster = array();
	     //longest tag
			$lengths = array_map('strlen', $tags);
			$maxLength = max($lengths);
			$index = array_search($maxLength, $lengths);
			$majorTag =  $tags[$index];
		
	    $perfectMatch=0;
	   $query = $this->db->query("SELECT DISTINCT primary_college FROM synonyms");
	   for($i=0;$i<sizeof($query->result());$i++)
		 {
			 $q1=$query->row($i);
			 $rank[$i]['COLL_NAME'] = $q1->primary_college;
			 $rank[$i]['score'] = 0;
             $rank[$i]['views']=0;
			  $query2 = $this->db->query("SELECT synonym FROM synonyms WHERE primary_college LIKE '".$q1->primary_college."'");
			  $query3 = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$q1->primary_college."'");
			   $rank[$i]['views'] = $query3->row(0)->Views;
			   $rank[$i]['id'] = $query3->row(0)->COLL_ID;
			   $this->load->model("College_model");
			   $rank[$i]['encoded_id'] = $this->College_model->id_encode($rank[$i]['id']);
			  for($j=0;$j<sizeof($query2->result());$j++)
			  {
				  $q2=$query2->row($j);
				  if(strtolower($q2->synonym) ==  strtolower($majorTag))
				  {
					$perfectMatch = $rank[$i];
				  }
				  
				  for($k=0;$k<sizeof($tags);$k++)
				  {
					  
						$synonyms_substrings = $this->SubStrings(strtolower($q2->synonym));
						for($l=0;$l<sizeof($synonyms_substrings);$l++)
						{
							if(strtolower($synonyms_substrings[$l]) == strtolower($tags[$k]))
							{
								  $rank[$i]['score'] = $rank[$i]['score'] + str_word_count($tags[$k])*10;
								  break 2;
							}
						}
				  }
			  } 
		 }
		 //sorting
	
		 $c1=0;$c2=0;$c3=0;
		 for($i=0;$i<sizeof($query->result());$i++)
		 {
			 if($rank[$i]['score'] > 10)
			 {
				 $cluster[0][$c1] = $rank[$i]; 
				 $c1++;
			 }
			 else if(($rank[$i]['score'] >= 1) AND ($rank[$i]['score'] <= 10) )
			 {
				 $cluster[1][$c2] = $rank[$i]; 
				 $c2++;
			 }
			 else
			 {
				 $cluster[2][$c3] = $rank[$i]; 
				 $c3++;
			 }
		 }
		 if($sorting == 1)
		 {
			 for($i=0;$i<3;$i++)
			 {
			  foreach ($cluster[$i] as $key => $row) 
			 {
					$views[$key]  = $row['views'];
			 }
			 array_multisort( $views, SORT_DESC,$cluster[$i]);
			 }
		 }
		 else if($sorting == 2)
		 {
			 for($i=0;$i<3;$i++)
			 {
				$fee = $this->getfee($cluster[$i]);
				for($j=0;$j<sizeof($fee);$j++)
				{
					$cluster[$i][$j]['fee'] = $fee[$j];
				}
				foreach ($cluster[$i] as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
				 }
				array_multisort( $fee, SORT_ASC,$cluster[$i]);
				 
			 }
		 }
		else if($sorting == 3)
		 {
			 for($i=0;$i<3;$i++)
			 {
				$fee = $this->getfee($cluster[$i]);
				for($j=0;$j<sizeof($fee);$j++)
				{
					$cluster[$i][$j]['fee'] = $fee[$j];
				}
				foreach ($cluster[$i] as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
				 }
				array_multisort( $fee, SORT_DESC,$cluster[$i]);
				 
			 }
		 }
		 if($perfectMatch)
		 {
			 for($i=0;$i<3;$i++)
			 {
			  for($j=0;$j<sizeof($cluster[$i]);$j++)
			  {
				  if($perfectMatch['COLL_NAME'] == $cluster[$j]['COLL_NAME'])
				  {
				   unset($cluster[$j]);
				   break 2;
				  }
			  }
		     }		  
		 }
		
		
		return  array_merge((array)$cluster[0],(array)$cluster[1],(array)$cluster[2]);
   }*/
   
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

   
  /*  function filters_ranking_old($tags,$id,$sorting)
   {
	   $rank = array();
	   $cluster = array();
	     //longest tag
			$lengths = array_map('strlen', $tags);
			$maxLength = max($lengths);
			$index = array_search($maxLength, $lengths);
			$majorTag =  $tags[$index];
		
	    $perfectMatch=0;
	   $query = $this->db->query("SELECT DISTINCT primary_college FROM synonyms where colg_id IN(".implode(',',$id).")");
	   for($i=0;$i<sizeof($query->result());$i++)
		 {
			 $q1=$query->row($i);
			 $rank[$i]['COLL_NAME'] = $q1->primary_college;
			 $rank[$i]['score'] = 0;
             $rank[$i]['views']=0;
			  $query2 = $this->db->query("SELECT synonym FROM synonyms WHERE primary_college LIKE '".$q1->primary_college."'");
			  $query3 = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$q1->primary_college."'");
			   $rank[$i]['views'] = $query3->row(0)->Views;
			   $rank[$i]['id'] = $query3->row(0)->COLL_ID;
			   $this->load->model("College_model");
			   $rank[$i]['encoded_id'] = $this->College_model->id_encode($rank[$i]['id']);
			  for($j=0;$j<sizeof($query2->result());$j++)
			  {
				  $q2=$query2->row($j);
				  if(strtolower($q2->synonym) ==  strtolower($majorTag))
				  {
					$perfectMatch = $rank[$i];
				  }
				  
				  for($k=0;$k<sizeof($tags);$k++)
				  {
					  	$synonyms_substrings = $this->SubStrings(strtolower($q2->synonym));
						for($l=0;$l<sizeof($synonyms_substrings);$l++)
						{
							if(strtolower($synonyms_substrings[$l]) == strtolower($tags[$k]))
							{
								  $rank[$i]['score'] = $rank[$i]['score'] + str_word_count($tags[$k])*10;
								  break 2;
							}
						}
				  }
			  } 
		 }
		 //sorting
	
		 $c1=0;$c2=0;$c3=0;
		 for($i=0;$i<sizeof($query->result());$i++)
		 {
			 if($rank[$i]['score'] > 10)
			 {
				 $cluster[0][$c1] = $rank[$i]; 
				 $c1++;
			 }
			 else if(($rank[$i]['score'] >= 1) AND ($rank[$i]['score'] <= 10) )
			 {
				 $cluster[1][$c2] = $rank[$i]; 
				 $c2++;
			 }
			 else
			 {
				 $cluster[2][$c3] = $rank[$i]; 
				 $c3++;
			 }
		 }
		 if($sorting == 1)
		 {
			 for($i=0;$i<3;$i++)
			 {
			  foreach ($cluster[$i] as $key => $row) 
			 {
					$views[$key]  = $row['views'];
			 }
			 array_multisort( $views, SORT_DESC,$cluster[$i]);
			 }
		 }
		 else if($sorting == 2)
		 {
			 for($i=0;$i<3;$i++)
			 {
				$fee = $this->getfee($cluster[$i]);
				for($j=0;$j<sizeof($fee);$j++)
				{
					$cluster[$i][$j]['fee'] = $fee[$j];
				}
				foreach ($cluster[$i] as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
				 }
				array_multisort( $fee, SORT_ASC,$cluster[$i]);
				 
			 }
		 }
		else if($sorting == 3)
		 {
			 for($i=0;$i<3;$i++)
			 {
				$fee = $this->getfee($cluster[$i]);
				for($j=0;$j<sizeof($fee);$j++)
				{
					$cluster[$i][$j]['fee'] = $fee[$j];
				}
				foreach ($cluster[$i] as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
				 }
				array_multisort( $fee, SORT_DESC,$cluster[$i]);
				 
			 }
		 }
		 if($perfectMatch)
		 {
			 for($i=0;$i<3;$i++)
			 {
			  for($j=0;$j<sizeof($cluster[$i]);$j++)
			  {
				  if($perfectMatch['COLL_NAME'] == $cluster[$j]['COLL_NAME'])
				  {
				   unset($cluster[$j]);
				   break 2;
				  }
			  }
		     }		  
		 }
		
		
		return  array_merge((array)$cluster[0],(array)$cluster[1],(array)$cluster[2]);
		//return $query->num_rows();
   }*/
   
   function preposition($val)
   {
	    $query = $this->db->query('SELECT * FROM preposition where Word LIKE "'.$val.'"');
		if($query->num_rows())
			return true;
		else
			return false;
   }
   
   function removekeywords( $string )
   {
	   $string = explode(' ', $string);
	   for($i=0;$i<sizeof($string);$i++)
	   {
	   $query = $this->db->query('SELECT * FROM ignored_keywords where keyword LIKE "'.$string[$i].'"');
	   if($query->num_rows())
	   { 
		unset($string[$i]);
		$string = array_values($string);
	   }
	   }
	  $string = implode(' ',$string);
	  return $string;
   }
 
   function filterslist($stream,$major,$degree,$country)
   {
	   $college_stream = array();
	   $college_major = array();
	   $college_degree = array();
	   $college_country = array();
	   $list = array();
	   $temporary = array();
	    $k=0;
		//JUST CHANGE college TO table2
		    $query = $this->db->query("SELECT DISTINCT COLL_ID FROM college ");
			 for($j=0;$j<sizeof($query->result());$j++)
		 {
			 $q=$query->row($j);
			 $temporary[$k] =  $q->COLL_ID;
			 $k++;
		 }
	   
	   
	   $k=0;
	    if(sizeof($stream)==0)
	   {
		    
			 $college_stream = $temporary;
		
	   }else{
		   for($i=0;$i<sizeof($stream);$i++)
		   {
			    $query = $this->db->query("SELECT Node_Name FROM NODETABLE where Node_ID LIKE '".$stream[$i]."' ");
				$query = $query->result();
				$stream[$i] = $query[0]->Node_Name;
				$stream[$i] = str_replace("Yes_", "", $stream[$i]);
		   }
		   
	   for($i=0;$i<sizeof($stream);$i++)
	   {
	   $query = $this->db->query("SELECT * FROM table2 where NODE_VALUE LIKE  '".$stream[$i]."' ");
		 for($j=0;$j<sizeof($query->result());$j++)
		 {
			 $q=$query->row($j);
			 $college_stream[$k] =  $q->COLL_ID;
			 $k++;
		 }
	   }}
	 $college_stream = array_unique($college_stream);
	 $list  = $college_stream;
	   $k=0;
	    if(sizeof($major)==0)
	   {
		    
			 $college_major = $temporary;
			 
		 
	   }else{
		    for($i=0;$i<sizeof($major);$i++)
		   {
			    $query = $this->db->query("SELECT Node_Name FROM NODETABLE where Node_ID LIKE '".$major[$i]."' ");
				$query = $query->result();
				$major[$i] = $query[0]->Node_Name;
				$major[$i] = str_replace("Yes_", "", $major[$i]);
		   }
	    for($i=0;$i<sizeof($major);$i++)
	   {
	    $query = $this->db->query("SELECT * FROM table2 where NODE_VALUE LIKE  '".$major[$i]."' ");
		 for($j=0;$j<sizeof($query->result());$j++)
		 {
			 $q=$query->row($j);
			 $college_major[$k] =  $q->COLL_ID;
			 $k++;
		 }
	   }}
	  $college_major= array_unique($college_major);
	 $list  = array_intersect($list, $college_major);
	 
	   $k=0;
	   if(sizeof($degree)==0)
	   {
		   
			 $college_degree =  $temporary;
		
	   }
	   else{
	     for($i=0;$i<sizeof($degree);$i++)
	   {
	    $query = $this->db->query("SELECT * FROM table2 where NODE_VALUE LIKE  '".$degree[$i]."' ");
		 for($j=0;$j<sizeof($query->result());$j++)
		 {
			 $q=$query->row($j);
			 $college_degree[$k] =  $q->COLL_ID;
			 $k++;
		 }
	   }}
	   
	  $college_degree = array_unique($college_degree);
	   $list  = array_intersect($list, $college_degree);
	   $k=0;
	    if(sizeof($country)==0)
	   {
		  
			 $college_country = $temporary;
			
	   }else{
	     for($i=0;$i<sizeof($country);$i++)
	   {
		$query = $this->db->query("SELECT * FROM Country where Country_Name = '".$country[$i]."' ");
		$query = $query->result();$query = $query[0]->Country_Code;
	    $query = $this->db->query("SELECT DISTINCT COLL_ID FROM  college where CountryCode = '".$query."' ");
		 for($j=0;$j<sizeof($query->result());$j++)
		 {
			 $q=$query->row($j);
			 $college_country[$k] =  $q->COLL_ID;
			 $k++;
		 }
	   }}
	   $college_country = array_unique($college_country);
	   $list  = array_intersect($list, $college_country);
	   $temp = $college_degree;
//	 return  array_intersect($college_stream1,$college_major1,$college_degree1,$college_country1);
	return $list;
   }
   
 function ranking($tags,$sorting,$majorTag)
   {
	    $this->load->model("College_model");
		$rank = array();
	    $cluster = array();
		if(count($tags) == 0)
		{
			$query = $this->db->query("SELECT * FROM college ORDER BY Views DESC");
			$query = $query->result(); 
			$rank = array();
			for($i=0;$i<sizeof($query);$i++)
			{
				$rank[$i]['COLL_NAME'] =  $query[$i]->COLL_NAME;
				$rank[$i]['score']     =  0;
				$rank[$i]['views']     =  $query[$i]->Views;
				$rank[$i]['id']        =  $query[$i]->COLL_ID;
	            $rank[$i]['streams']   =  "";
	            $rank[$i]['degree']    =  "";
	            $rank[$i]['city']	   =  $query[$i]->city;
	            $rank[$i]['encoded_id'] = $this->College_model->id_encode($rank[$i]['id']);
		        $stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$i]['id'],
		             											    'NODE_NAME   =' => 'Streams/Schools',
		             											    'NODE_VALUE !=' => NULL));
		        foreach($stream_query->result() as $stream_row)
		        {
		        	 $rank[$i]['streams'] =  $rank[$i]['streams'].$stream_row->NODE_VALUE.', ';
		        }
		        /*
					For the undergraduate ,postgraduate and doctoral to count only once.
		        */
				$undergraduate = 0;
				$doctoral = 0;
				$masters = 0;
		        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$i]['id'],
		             											    'NODE_NAME   =' => 'Degrees',
		             											    'NODE_VALUE !=' => NULL));
		        foreach($degree_query->result() as $degree_row)
		        {
		        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
		        	{
		        		$undergraduate = 1;
		        	 	$rank[$i]['degree'] =  $rank[$i]['degree'].$degree_row->NODE_VALUE.', ';
		        	}
		        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
		        	{
		        		$masters = 1;
		        	 	$rank[$i]['degree'] =  $rank[$i]['degree'].$degree_row->NODE_VALUE.', ';
		        	}
		        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
		        	{
		        		$doctoral = 1;
		        	 	$rank[$i]['degree'] =  $rank[$i]['degree'].$degree_row->NODE_VALUE.', ';
		        	}
		        }
					 
			  }
		}
		else
		{
			
	//	array_unshift($tags, "tag");
	     //longest tag
			/*$lengths = array_map('str_word_count', $tags);
			$maxLength = max($lengths);
			$index = array_search($maxLength, $lengths);
			$majorTag = $tags[$index]; */
		//	$majorTag = $val;
	     $queryMajorTag = $this->db->query("SELECT primary_college FROM synonyms where synonym LIKE '".$majorTag."'");
		if($queryMajorTag->num_rows()>0)
		{    
	        //Main query tag
			 $queryMajorTag=$queryMajorTag->result();
			 $queryMajorTagViews = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$queryMajorTag[0]->primary_college."'");
			 $queryMajorTagViews = $queryMajorTagViews->result(); 
			 $rank[0]['COLL_NAME'] = $queryMajorTag[0]->primary_college;
			 $rank[0]['score'] = 40;
             $rank[0]['views']= $queryMajorTagViews[0]->Views;
			  $rank[0]['id'] = $queryMajorTagViews[0]->COLL_ID;
			 $rank[0]['encoded_id'] = $this->College_model->id_encode($rank[0]['id']);
			 $rank[0]['streams']   =  "";
   			$rank[0]['degree']    =  "";
   			$rank[0]['city']      = $queryMajorTagViews[0]->city;
			$stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[0]['id'],
         											    'NODE_NAME   =' => 'Streams/Schools',
         											    'NODE_VALUE !=' => NULL));
	        foreach($stream_query->result() as $stream_row)
	        {
	        	 $rank[$k]['streams'] =  $rank[$k]['streams'].$stream_row->NODE_VALUE.', ';
	        }
	        /*
				For the undergraduate ,postgraduate and doctoral to count only once.
	        */
			$undergraduate = 0;
			$doctoral = 0;
			$masters = 0;
	        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[0]['id'],
	             											    'NODE_NAME   =' => 'Degrees',
	             											    'NODE_VALUE !=' => NULL));
	        foreach($degree_query->result() as $degree_row)
	        {
	        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
	        	{
	        		$undergraduate = 1;
	        	 	$rank[0]['degree'] =  $rank[0]['degree'].$degree_row->NODE_VALUE.', ';
	        	}
	        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
	        	{
	        		$masters = 1;
	        	 	$rank[0]['degree'] =  $rank[0]['degree'].$degree_row->NODE_VALUE.', ';
	        	}
	        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
	        	{
	        		$doctoral = 1;
	        	 	$rank[0]['degree'] =  $rank[0]['degree'].$degree_row->NODE_VALUE.', ';
	        	}
	        }

			
			 //phrase
			 $phrase= $this->db->query("SELECT primary_college FROM synonyms where synonym LIKE '% ".$majorTag." %' OR synonym LIKE '".$majorTag." %' OR synonym LIKE '".$majorTag."'");
			 if(sizeof($phrase->result())>0)
			 {  
		      $phrase=$phrase->result();
		        $k = 1;
				  for($i=0;$i<sizeof( $phrase);$i++)
				  {
					$phraseViews = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$phrase[$i]->primary_college."'");
					 $phraseViews = $phraseViews->result();
					
							   if($rank[0]['COLL_NAME']==$phrase[$i]->primary_college)
							   {
								    $rank[0]['score'] = $rank[0]['score'] + 10;
							       
							   }
							
					else
					 {
						 $rank[$k]['COLL_NAME'] =  $phrase[$i]->primary_college;
						 $rank[$k]['score'] = 10;
						 $rank[$k]['views']=  $phraseViews[0]->Views;
						 $rank[$k]['id'] =  $phraseViews[0]->COLL_ID;
			             $rank[$k]['encoded_id'] = $this->College_model->id_encode($rank[$k]['id']);
			             $rank[$k]['streams']   =  "";
	           			 $rank[$k]['degree']    =  "";
	           			 $rank[$k]['city']      = $phraseViews[0]->city;
						$stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
		             											    'NODE_NAME   =' => 'Streams/Schools',
		             											    'NODE_VALUE !=' => NULL));
				        foreach($stream_query->result() as $stream_row)
				        {
				        	 $rank[$k]['streams'] =  $rank[$k]['streams'].$stream_row->NODE_VALUE.', ';
				        }
				        /*
							For the undergraduate ,postgraduate and doctoral to count only once.
				        */
						$undergraduate = 0;
						$doctoral = 0;
						$masters = 0;
				        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
				             											    'NODE_NAME   =' => 'Degrees',
				             											    'NODE_VALUE !=' => NULL));
				        foreach($degree_query->result() as $degree_row)
				        {
				        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
				        	{
				        		$undergraduate = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
				        	{
				        		$masters = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
				        	{
				        		$doctoral = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        }
						 $k++;
					 }		
				  }
			 }
	
			 
		}
		else{
		$phrase= $this->db->query("SELECT primary_college FROM synonyms where synonym LIKE '% ".$majorTag." %' OR synonym LIKE '".$majorTag." %' OR synonym LIKE '% ".$majorTag."' OR synonym LIKE '".$majorTag."'");
			 if($phrase->num_rows()>0)
			 {  
		      $phrase=$phrase->result();
		        $k = 0;
				  for($i=0;$i<sizeof( $phrase);$i++)
				  {
					$phraseViews = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$phrase[$i]->primary_college."'");
					 $phraseViews = $phraseViews->result();
					
					 
						 $rank[$k]['COLL_NAME'] =  $phrase[$i]->primary_college;
						 $rank[$k]['score'] = 10;
						 $rank[$k]['views']=  $phraseViews[0]->Views;
						 $rank[$k]['id'] =  $phraseViews[0]->COLL_ID;
			             $rank[$k]['encoded_id'] = $this->College_model->id_encode($rank[$k]['id']);
			             $rank[$k]['streams']   =  "";
	           			 $rank[$k]['degree']    =  "";
	           			 $rank[$k]['city']      =$phraseViews[0]->city;
						$stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
		             											    'NODE_NAME   =' => 'Streams/Schools',
		             											    'NODE_VALUE !=' => NULL));
				        foreach($stream_query->result() as $stream_row)
				        {
				        	 $rank[$k]['streams'] =  $rank[$k]['streams'].$stream_row->NODE_VALUE.', ';
				        }
				        /*
							For the undergraduate ,postgraduate and doctoral to count only once.
				        */
						$undergraduate = 0;
						$doctoral = 0;
						$masters = 0;
				        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
				             											    'NODE_NAME   =' => 'Degrees',
				             											    'NODE_VALUE !=' => NULL));
				        foreach($degree_query->result() as $degree_row)
				        {
				        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
				        	{
				        		$undergraduate = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
				        	{
				        		$masters = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
				        	{
				        		$doctoral = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        }
						 $k++;
					 		
				  }
			 }
		}
		
			 //subtags
			// array_push($tags,"blue","jyttj");
			 $k = sizeof($rank);
			 for($i=0;$i<(sizeof($tags));$i++)
			 {
				 if(1)
				 {
					$tag =$tags[$i];
				  $subtag = $this->db->query("SELECT primary_college FROM synonyms where synonym LIKE '% ".$tag." %' OR synonym LIKE '".$tag." %' OR synonym LIKE '% ".$tag."' OR synonym LIKE '".$tag."'");
				 if(sizeof($subtag->result)>=0){
				 $subtag=$subtag->result();
				  for($j=0;$j<sizeof($subtag);$j++)
				  {
					
					 
					 $subtagViews = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$subtag[$j]->primary_college."'");
					 $subtagViews =$subtagViews->result();
					  $flag_college=-2;
					if(sizeof($rank)>0){
						   for($l=0;$l<sizeof($rank);$l++)
						   {
							   if($rank[$l]['COLL_NAME']==$subtag[$j]->primary_college)
							   {
								   $flag_college = $l;
							       break;
							   }
							  else
								  $flag_college=-2;
						   }
					}
					 if($flag_college>=0)
					 {
						 
					    $rank[$flag_college]['score'] = $rank[$flag_college]['score'] + str_word_count($tags[$i]);
					 }
					else
					 {
						 
						 $rank[$k]['COLL_NAME'] = $subtag[$j]->primary_college;
						 $rank[$k]['score'] = str_word_count($tags[$i]);
						 $rank[$k]['views']= $subtagViews[0]->Views;
						 $rank[$k]['id'] = $subtagViews[0]->COLL_ID;
			             $rank[$k]['encoded_id'] = $this->College_model->id_encode($rank[$k]['id']);
			             $rank[$k]['streams']   =  "";
	           			 $rank[$k]['degree']    =  "";
	           			 $rank[$k]['city']      = $subtagViews[0]->city;
						$stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
		             											    'NODE_NAME   =' => 'Streams/Schools',
		             											    'NODE_VALUE !=' => NULL));
				        foreach($stream_query->result() as $stream_row)
				        {
				        	 $rank[$k]['streams'] =  $rank[$k]['streams'].$stream_row->NODE_VALUE.', ';
				        }
				        /*
							For the undergraduate ,postgraduate and doctoral to count only once.
				        */
						$undergraduate = 0;
						$doctoral = 0;
						$masters = 0;
				        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
				             											    'NODE_NAME   =' => 'Degrees',
				             											    'NODE_VALUE !=' => NULL));
				        foreach($degree_query->result() as $degree_row)
				        {
				        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
				        	{
				        		$undergraduate = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
				        	{
				        		$masters = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
				        	{
				        		$doctoral = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        }
						 
						 $k++;
					 }					 
				  }
				 }
				 }
				  
			 }
			 
	}
		 //sorting
		$fee = $this->getfield($rank,104);
		$salary = $this->getfield($rank,143);
		$class_size = $this->getfield($rank,1);
		for($i=0;$i<sizeof($rank);$i++)
		{
			if($fee[$i] == "NA" && $sorting == 2)
			{
				$fee[$i] = INT_MAX;
			}
			else if($fee[$i] == "NA" && $sorting == 3)
			{
				$fee[$i] = -1;
			} 
			if($salary[$i] == "NA" && $sorting == 4)
			{
				$salary[$i] = INT_MAX;
			}
			else if($salary[$i] == "NA" && $sorting == 5)
			{
				$salary[$i] = -1;
			}
			if($class_size[$i] == "NA" && $sorting == 6)
			{
				$class_size[$i] = INT_MAX;
			}
			else if($class_size[$i] == "NA" && $sorting == 7)
			{
				$class_size[$i] = -1;
			}

			$rank[$i]['fee'] = $fee[$i];
			$rank[$i]['salary'] = $salary[$i];
			$rank[$i]['class_size'] = $class_size[$i];
		}
		  if($sorting==2)
		 {
			  foreach ($rank as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
						$views[$key]  = $row['views'];
						$salary[$key] = $row['salary'];
						$class_size = $row['class_size'];
				 }
				array_multisort( $fee, SORT_ASC,$rank);
		 }
		 else if($sorting==3){
			  foreach ($rank as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
						$views[$key]  = $row['views'];
				 		$salary[$key] = $row['salary'];
				 		$class_size = $row['class_size'];
				 }
				array_multisort( $fee, SORT_DESC,$views,SORT_DESC,$rank);
		 }
		 else if($sorting ==4){
		 	foreach ($rank as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
						$views[$key]  = $row['views'];
						$salary[$key] = $row['salary'];
						$class_size = $row['class_size'];
				 }
				array_multisort( $salary, SORT_ASC,$views,SORT_DESC,$rank);
		 }
		 else if($sorting ==5){
		 	foreach ($rank as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
						$views[$key]  = $row['views'];
						$salary[$key] = $row['salary'];
						$class_size = $row['class_size'];
				 }
				array_multisort( $salary, SORT_DESC,$views,SORT_DESC,$rank);
		 }
		 else if($sorting == 6){ //edit
		 	foreach ($rank as $key => $row)
			 	{
			 			$fee[$key]  = $row['fee'];
				 		$class_size[$key] = $row['class_size'];
				 		$views[$key] = $row['views'];
				 		$salary[$key] = $row['salary'];
			 	}
			 	array_multisort($class_size, SORT_ASC,$views,SORT_DESC,$rank);
		 }
		else if($sorting == 7){ //edit
		 	foreach ($rank as $key => $row)
			 	{
			 			$fee[$key]  = $row['fee'];
				 		$class_size[$key] = $row['class_size'];
				 		$views[$key] = $row['views'];
				 		$salary[$key] = $row['salary'];
			 	}
			 	array_multisort($class_size, SORT_DESC,$views,SORT_DESC,$rank);
		 }
		 else
		 {
	      foreach ($rank as $key => $row) 
				 {
						$score[$key]  = $row['score'];
						$views[$key]  = $row['views'];
				 }
				array_multisort( $score, SORT_DESC,$views,SORT_DESC,$rank);
		 }
		
	return array_values($rank);
	
		
   } 
   
       function filters_ranking($tags,$id,$sorting,$majorTag)
   {
	    $this->load->model("College_model");
		$rank = array();
	      $cluster = array();
		if(count($tags) == 0)
		{ 
	     if(count($id) > 0 ){
			$query = $this->db->query("SELECT * FROM college where COLL_ID IN(".implode(',',$id).")");
			 $query = $query->result(); 
			 
			
			for($i=0;$i<sizeof($query);$i++)
			{
				 
				$rank[$i]['COLL_NAME'] =  $query[$i]->COLL_NAME;
				$rank[$i]['score'] = 0;
				$rank[$i]['views']=  $query[$i]->Views;
				$rank[$i]['id'] =  $query[$i]->COLL_ID;
	            $rank[$i]['encoded_id'] = $this->College_model->id_encode($rank[$i]['id']);
	            $rank[$i]['streams']   =  "";
        		$rank[$i]['degree']    =  "";
	         	$rank[$i]['city']      = $query[$i]->city;

				$stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$i]['id'],
		             											    'NODE_NAME   =' => 'Streams/Schools',
		             											    'NODE_VALUE !=' => NULL));
		        foreach($stream_query->result() as $stream_row)
		        {
		        	 $rank[$i]['streams'] =  $rank[$i]['streams'].$stream_row->NODE_VALUE.', ';
		        }
		        /*
					For the undergraduate ,postgraduate and doctoral to count only once.
		        */
				$undergraduate = 0;
				$doctoral = 0;
				$masters = 0;
		        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$i]['id'],
		             											    'NODE_NAME   =' => 'Degrees',
		             											    'NODE_VALUE !=' => NULL));
		        foreach($degree_query->result() as $degree_row)
		        {
		        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
		        	{
		        		$undergraduate = 1;
		        	 	$rank[$i]['degree'] =  $rank[$i]['degree'].$degree_row->NODE_VALUE.', ';
		        	}
		        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
		        	{
		        		$masters = 1;
		        	 	$rank[$i]['degree'] =  $rank[$i]['degree'].$degree_row->NODE_VALUE.', ';
		        	}
		        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
		        	{
		        		$doctoral = 1;
		        	 	$rank[$i]['degree'] =  $rank[$i]['degree'].$degree_row->NODE_VALUE.', ';
		        	}
		        }				 
						 
			}
		}
		}
		else
		{
				
	//	array_unshift($tags, "tag");
	     //longest tag
			/*$lengths = array_map('str_word_count', $tags);
			$maxLength = max($lengths);
			$index = array_search($maxLength, $lengths);
			$majorTag = $tags[$index]; */
		//	$majorTag = $val;
	     $queryMajorTag = $this->db->query("SELECT primary_college FROM synonyms where (synonym LIKE '".$majorTag."') AND colg_id IN (".implode(',',$id).") ");
		if($queryMajorTag->num_rows()>0)
		{    
	        //Main query tag
			 $queryMajorTag=$queryMajorTag->result();
			 $queryMajorTagViews = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$queryMajorTag[0]->primary_college."'");
			 $queryMajorTagViews = $queryMajorTagViews->result(); 
			 $rank[0]['COLL_NAME'] = $queryMajorTag[0]->primary_college;
			 $rank[0]['score'] = 40;
             $rank[0]['views']= $queryMajorTagViews[0]->Views;
			 $rank[0]['id'] = $queryMajorTagViews[0]->COLL_ID;
			 $rank[0]['encoded_id'] = $this->College_model->id_encode($rank[0]['id']);
			 $rank[0]['streams']   =  "";
   			 $rank[0]['degree']    =  "";
	         $rank[0]['city']      = $queryMajorTagViews[0]->city;

			$stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[0]['id'],
         											    'NODE_NAME   =' => 'Streams/Schools',
         											    'NODE_VALUE !=' => NULL));
	        foreach($stream_query->result() as $stream_row)
	        {
	        	 $rank[$k]['streams'] =  $rank[$k]['streams'].$stream_row->NODE_VALUE.', ';
	        }
	        /*
				For the undergraduate ,postgraduate and doctoral to count only once.
	        */
			$undergraduate = 0;
			$doctoral = 0;
			$masters = 0;
	        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[0]['id'],
	             											    'NODE_NAME   =' => 'Degrees',
	             											    'NODE_VALUE !=' => NULL));
	        foreach($degree_query->result() as $degree_row)
	        {
	        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
	        	{
	        		$undergraduate = 1;
	        	 	$rank[0]['degree'] =  $rank[0]['degree'].$degree_row->NODE_VALUE.', ';
	        	}
	        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
	        	{
	        		$masters = 1;
	        	 	$rank[0]['degree'] =  $rank[0]['degree'].$degree_row->NODE_VALUE.', ';
	        	}
	        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
	        	{
	        		$doctoral = 1;
	        	 	$rank[0]['degree'] =  $rank[0]['degree'].$degree_row->NODE_VALUE.', ';
	        	}
	        }
			
			 //phrase
			 $phrase= $this->db->query("SELECT primary_college FROM synonyms where (synonym LIKE '% ".$majorTag." %' OR synonym LIKE '".$majorTag." %' OR synonym LIKE '".$majorTag."') AND colg_id IN (".implode(',',$id).")");
			 if(sizeof($phrase->result())>0)
			 {  
		      $phrase=$phrase->result();
		        $k = 1;
				  for($i=0;$i<sizeof( $phrase);$i++)
				  {
					$phraseViews = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$phrase[$i]->primary_college."'");
					 $phraseViews = $phraseViews->result();
					
							   if($rank[0]['COLL_NAME']==$phrase[$i]->primary_college)
							   {
								    $rank[0]['score'] = $rank[0]['score'] + 10;
							       
							   }
							
					else
					 {
						 $rank[$k]['COLL_NAME'] =  $phrase[$i]->primary_college;
						 $rank[$k]['score'] = 10;
						 $rank[$k]['views']=  $phraseViews[0]->Views;
						 $rank[$k]['id'] =  $phraseViews[0]->COLL_ID;
			             $rank[$k]['encoded_id'] = $this->College_model->id_encode($rank[$k]['id']);
			            $rank[$k]['streams']   =  "";
	           			 $rank[$k]['degree']    =  "";
	           			 $rank[$k]['city']      = $phraseViews[0]->city;

						$stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
		             											    'NODE_NAME   =' => 'Streams/Schools',
		             											    'NODE_VALUE !=' => NULL));
				        foreach($stream_query->result() as $stream_row)
				        {
				        	 $rank[$k]['streams'] =  $rank[$k]['streams'].$stream_row->NODE_VALUE.', ';
				        }
				        /*
							For the undergraduate ,postgraduate and doctoral to count only once.
				        */
						$undergraduate = 0;
						$doctoral = 0;
						$masters = 0;
				        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
				             											    'NODE_NAME   =' => 'Degrees',
				             											    'NODE_VALUE !=' => NULL));
				        foreach($degree_query->result() as $degree_row)
				        {
				        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
				        	{
				        		$undergraduate = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
				        	{
				        		$masters = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
				        	{
				        		$doctoral = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        }
						 $k++;
					 }		
				  }
			 }
	
			 
		}
		else{
		$phrase= $this->db->query("SELECT primary_college FROM synonyms where (synonym LIKE '% ".$majorTag." %' OR synonym LIKE '".$majorTag." %' OR synonym LIKE '% ".$majorTag."' OR synonym LIKE '".$majorTag."') AND colg_id IN (".implode(',',$id).") ");
			 if($phrase->num_rows()>0)
			 {  
		      $phrase=$phrase->result();
		        $k = 0;
				  for($i=0;$i<sizeof( $phrase);$i++)
				  {
					$phraseViews = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$phrase[$i]->primary_college."'");
					 $phraseViews = $phraseViews->result();
					
					 
						 $rank[$k]['COLL_NAME'] =  $phrase[$i]->primary_college;
						 $rank[$k]['score'] = 10;
						 $rank[$k]['views']=  $phraseViews[0]->Views;
						 $rank[$k]['id'] =  $phraseViews[0]->COLL_ID;
			             $rank[$k]['encoded_id'] = $this->College_model->id_encode($rank[$k]['id']);
			             $rank[$k]['streams']   =  "";
	           			 $rank[$k]['degree']    =  "";
	           			 $rank[$k]['city']      = $phraseViews[0]->city;

						$stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
		             											    'NODE_NAME   =' => 'Streams/Schools',
		             											    'NODE_VALUE !=' => NULL));
				        foreach($stream_query->result() as $stream_row)
				        {
				        	 $rank[$k]['streams'] =  $rank[$k]['streams'].$stream_row->NODE_VALUE.', ';
				        }
				        /*
							For the undergraduate ,postgraduate and doctoral to count only once.
				        */
						$undergraduate = 0;
						$doctoral = 0;
						$masters = 0;
				        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
				             											    'NODE_NAME   =' => 'Degrees',
				             											    'NODE_VALUE !=' => NULL));
				        foreach($degree_query->result() as $degree_row)
				        {
				        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
				        	{
				        		$undergraduate = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
				        	{
				        		$masters = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
				        	{
				        		$doctoral = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        }
						 $k++;
					 		
				  }
			 }
		}
		
			 //subtags
			// array_push($tags,"blue","jyttj");
			 $k = sizeof($rank);
			 for($i=0;$i<(sizeof($tags));$i++)
			 {
				 if(1)
				 {
					$tag =$tags[$i];
				  $subtag = $this->db->query("SELECT primary_college FROM synonyms where (synonym LIKE '% ".$tag." %' OR synonym LIKE '".$tag." %' OR synonym LIKE '% ".$tag."' OR synonym LIKE '".$tag."') AND colg_id IN (".implode(',',$id).")");
				 if(sizeof($subtag->result)>=0){
				 $subtag=$subtag->result();
				  for($j=0;$j<sizeof($subtag);$j++)
				  {
					
					 
					 $subtagViews = $this->db->query("SELECT * FROM college WHERE COLL_NAME LIKE '".$subtag[$j]->primary_college."'");
					 $subtagViews =$subtagViews->result();
					  $flag_college=-2;
					if(sizeof($rank)>0){
						   for($l=0;$l<sizeof($rank);$l++)
						   {
							   if($rank[$l]['COLL_NAME']==$subtag[$j]->primary_college)
							   {
								   $flag_college = $l;
							       break;
							   }
							  else
								  $flag_college=-2;
						   }
					}
					 if($flag_college>=0)
					 {
						 
					    $rank[$flag_college]['score'] = $rank[$flag_college]['score'] + str_word_count($tags[$i]);
					 }
					else
					 {
						 
						 $rank[$k]['COLL_NAME'] = $subtag[$j]->primary_college;
						 $rank[$k]['score'] = str_word_count($tags[$i]);
						 $rank[$k]['views']= $subtagViews[0]->Views;
						 $rank[$k]['id'] = $subtagViews[0]->COLL_ID;
			             $rank[$k]['encoded_id'] = $this->College_model->id_encode($rank[$k]['id']);
			            $rank[$k]['streams']   =  "";
	           			 $rank[$k]['degree']    =  "";
	           			 $rank[$k]['city']      = $subtagViews[0]->city;

						$stream_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
		             											    'NODE_NAME   =' => 'Streams/Schools',
		             											    'NODE_VALUE !=' => NULL));
				        foreach($stream_query->result() as $stream_row)
				        {
				        	 $rank[$k]['streams'] =  $rank[$k]['streams'].$stream_row->NODE_VALUE.', ';
				        }
				        /*
							For the undergraduate ,postgraduate and doctoral to count only once.
				        */
						$undergraduate = 0;
						$doctoral = 0;
						$masters = 0;
				        $degree_query = $this->db->get_where('table2',array('COLL_ID     =' => $rank[$k]['id'],
				             											    'NODE_NAME   =' => 'Degrees',
				             											    'NODE_VALUE !=' => NULL));
				        foreach($degree_query->result() as $degree_row)
				        {
				        	if($degree_row->NODE_VALUE == 'Undergraduate' && $undergraduate == 0)
				        	{
				        		$undergraduate = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Masters' && $masters == 0)
				        	{
				        		$masters = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        	else if($degree_row->NODE_VALUE == 'Doctoral' && $doctoral == 0)
				        	{
				        		$doctoral = 1;
				        	 	$rank[$k]['degree'] =  $rank[$k]['degree'].$degree_row->NODE_VALUE.', ';
				        	}
				        }
						 
						 $k++;
					 }					 
				  }
				 }
				 }
				  
			 }
		}
		 //sorting
	   $fee = $this->getfield($rank,104);
	   $salary = $this->getfield($rank,143);
	   //$class_size = $this->getfield($rank,1);
		for($i=0;$i<sizeof($rank);$i++)
		{
			$rank[$i]['fee'] = $fee[$i];
			$rank[$i]['salary'] = $salary[$i];
			//$rank[$i]['class_size'] = $class_size[$i];
		}
		 if($sorting==2)
		 {
			  foreach ($rank as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
						$views[$key]  = $row['views'];
						$salary[$key] = $row['salary'];
				 }
				array_multisort( $fee, SORT_ASC,$views,SORT_DESC,$rank);
		 }
		 else if($sorting==3){
			  foreach ($rank as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
						$views[$key]  = $row['views'];
						$salary[$key] = $row['salary'];
				 }
				array_multisort( $fee, SORT_DESC,$views,SORT_DESC,$rank);
		 }
		 else if($sorting ==4){
		 	foreach ($rank as $key => $row) 
				 {
						$fee[$key]  = $row['fee'];
						$views[$key]  = $row['views'];
						$salary[$key] = $row['salary'];
				 }
				array_multisort( $salary, SORT_ASC,$views,SORT_DESC,$rank);
		 }
		  else
		 {
	      foreach ($rank as $key => $row) 
				 {
						$score[$key]  = $row['score'];
						$views[$key]  = $row['views'];
				 }
				array_multisort( $score, SORT_DESC,$views,SORT_DESC,$rank);
		 }
				/*$t = array();
				for($m = 0;$m<sizeof($rank);$m++)
				{
					if($rank[$m]['COLL_NAME']){
					 $t[$m]['COLL_NAME'] = $rank[$m]['COLL_NAME'];
						$t[$m]['score'] =  $rank[$m]['score'];
						 $t[$m]['views']=  $rank[$m]['views'];
						 $t[$m]['id'] =  $rank[$m]['id'];
			             $t[$m]['encoded_id'] =  $rank[$m]['encoded_id'];
					}
				}*/
				
				return array_values($rank);
	
		
}

function stream_nest($stream)
{
	$degrees = array();
	$major = array();
/*	for($i=0;$i<sizeof($stream);$i++)
	   {
	         $query = $this->db->query("SELECT * FROM NODETABLE where NODE_NAME LIKE  'Yes_".$stream[$i]."'  AND Node_Type LIKE 'Structural' ");
			 $query=$query->row(0);
			 $stream_id[$i] =  $query->Node_ID;
	   }*/
	$stream_id = (array)$stream;
	if(sizeof($stream_id)>0){
	$query =  $this->db->query("SELECT Node_ID FROM NODETABLE where Prev_Node IN(".implode(',',$stream_id).")");
    $query = $query->result();
	
	for ($i=0;$i<sizeof($query);$i++) {
    $query[$i] = $query[$i]->Node_ID;
     }
	foreach ($query as $key => $value) {
    $query[$key] = $value +1;
     }
	 if(sizeof($query)>0)
	 {
	$query = $this->db->query("SELECT * FROM NODETABLE where Node_ID IN(".implode(',',$query).")");
	$query =$query->result();
	for ($i=0;$i<sizeof($query);$i++) {
    $query[$i] = $query[$i]->Node_ID;
     }
	$temp=array();
	$temp= $query;
	//degree available node_id list
	$degrees = $this->node_name_generate($query);
	 if(sizeof($temp)>0){
	$query =  $this->db->query("SELECT Node_ID FROM NODETABLE where Prev_Node IN(".implode(',',$temp).")");
    $query = $query->result();
	for ($i=0;$i<sizeof($query);$i++) {
    $query[$i] = $query[$i]->Node_ID;
     }
	foreach ($query as $key => $value) {
    $query[$key] = $value +1;
     }
	 if(sizeof($query)>0)
	 {
	$query = $this->db->query("SELECT Node_ID FROM NODETABLE where Node_ID IN(".implode(',',$query).")");
	$query =$query->result();
	for ($i=0;$i<sizeof($query);$i++) {
    $query[$i] = $query[$i]->Node_ID;
     }
	
	//degree available node_id list
	$majors_id = $query;
	$major = $this->node_name_generate($query);
	 }
	 }
	}
	}
	return array($degrees,$major,$majors_id);
}

function degree_nest($degree){
	$majors = array();
	$l=0;
	for($i=0;$i<sizeof($degree);$i++)
	   {
	         $query = $this->db->query("SELECT * FROM NODETABLE where NODE_NAME LIKE  'Yes_".$degree[$i]."'  AND Node_Type LIKE 'Structural' ");
			 $query = $query->result();
			 for($k=0;$k<sizeof($query);$k++)
			 {
			 $degree_id[$l] =  $query[$k]->Node_ID;
			 $l++;
			 }
	   }
	$degree_id = array_values(array_filter($degree_id));
	if(sizeof($degree_id)>0){
	$query =  $this->db->query("SELECT Node_ID FROM NODETABLE where Prev_Node IN(".implode(',',$degree_id).")");
    $query = $query->result();
	if(sizeof($query)>0){
	for ($i=0;$i<sizeof($query);$i++) {
    $query[$i] = $query[$i]->Node_ID;
     }
	foreach ($query as $key => $value) {
    $query[$key] = $value +1;
     }
	$query = $this->db->query("SELECT * FROM NODETABLE where Node_ID IN(".implode(',',$query).")");
	$query =$query->result();
	for ($i=0;$i<sizeof($query);$i++) {
    $query[$i] = $query[$i]->Node_ID;
     }
	$temp= $query;
	//degree available node_id list
	$majors_id = $query;
	$majors = $this->node_name_generate($query);
	}
	}

	return array($majors,$majors_id);
}

	function major_nest($major){
		$degree= array();
		$stream = array();
		$query_Node_Name = array();
		$query_prev_node = array();
		$query_Node_id = array();
		
		for($i=0;$i<sizeof($major);$i++)
		   {
		         $query = $this->db->query("SELECT * FROM NODETABLE where Node_ID LIKE  ".$major[$i]." ");
				 $query  =  $query->result();
				 $major_prev_id[$i] = $query[0]->Prev_Node;
				 
		   }
		$temp = $major_prev_id;
		$m=1;
		if(sizeof($temp)>0){
		while($m>=0){
		$query =  $this->db->query("SELECT * FROM NODETABLE where Node_ID IN(".implode(',',$temp).")");
	    $query = $query->result();
		if(sizeof($query)>0){
		for ($i=0;$i<sizeof($query);$i++) {
	    $query_prev_node[$i] = $query[$i]->Prev_Node;
		$query_Node_id[$i] = $query[$i]->Node_ID;
		$query_Node_Name[$i] = $query[$i]->Node_Name;
	     }
		 $temp = $query_prev_node;
		 $m--;
		}
		}
		$degree = $query_Node_Name;
		$degree = array_values(array_unique($degree));
		//streams
		$temp =  $query_prev_node;
		$query2 = array();
		$m=1;
		if(sizeof($temp)>0){
		while($m>=0){
		$query =  $this->db->query("SELECT * FROM NODETABLE where Node_ID IN(".implode(',',$temp).")");
	    $query = $query->result();
		if(sizeof($query)>0){
		for ($i=0;$i<sizeof($query);$i++) {
	    $query2[$i] = $query[$i]->Prev_Node;
		$query_Node_id[$i] = $query[$i]->Node_ID;
		$query_Node_Name[$i] = $query[$i]->Node_Name;
	     }
		}
		 $temp = $query2;
		 $m--;
		}
		$stream = $query_Node_Name;
		}
	}
		return array($degree,$stream);
	}

	function get_tagname($node)
	{
		$param_query = $this->db->get_where('NODETABLE',array('NODE_ID ='=>$node));
		foreach($param_query->result() as $param_row)
		{
			$param_qid = $param_row->Trigger_Ques;
		}
		$tag_param = $this->db->get_where('Tag_Table',array('Q_ID =' => $param_qid));
		$flag=0;
		foreach($tag_param->result() as $tag_row)
		{
			if($flag == 0)
			{
				$tag_name = $tag_row->TAG_NAME;
				$flag = 1;	
			}
			
		}
		return $tag_name;
	}

	function node_name_generate($query)
	{
		
		$result=array();
		for ($i=0;$i<sizeof($query);$i++) {
			$query1 = $this->db->query("SELECT * FROM `NODETABLE` WHERE `Node_ID` =$query[$i]");
		$query1 =$query1->result();
	    $result[$i] = $query1[0]->Node_Name;
	     }
		 return $result;
	}
	function check_tags($val)
	{
		$query = $this->db->query("SELECT primary_college FROM synonyms where synonym LIKE '%$val%'");
		if(sizeof($query->result())>0)
		{
			return 2;
		}
		else{
			return 1;
		}
		
	}
	public function psycho_ranking($rank,$node,$flag)
	{ 
		$k=0;
		 $temp = ARRAY();

		 	$Trigger_Ques = $this->db->query("SELECT Trigger_Ques FROM NODETABLE WHERE Node_ID = $node ");
			$Trigger_Ques = $Trigger_Ques->result();
			$Trigger_Ques = $Trigger_Ques[0]->Trigger_Ques;
			$ques = $this->db->query("SELECT * FROM QUESTIONTABALE WHERE Q_ID = $Trigger_Ques ");
			$ques = $ques->result();
			$flag = $ques[0]->Footer_Flag;
			$header = $ques[0]->Header_val;
			
			

			for($i=0;$i<sizeof($rank);$i++)
			{
				$query = $this->db->query("SELECT MU FROM psycho_table2 where COLL_ID = ".$rank[$i]['id']." AND D_Node = ".$node." AND Stream = 'All' AND Degree = 'All' AND Major = 'All' AND Sample_SZ >=5 ");
				$query =$query->result();
				if($query[0]->MU > 0)
				{
					$rank[$i]['MU'] = round(20*($query[0]->MU));
					$rank[$i]['views'] = $rank[$i]['MU'];
					
					$roundoff_mu[$i] = round($query[0]->MU);
					$option = 'Op'.$roundoff_mu[$i];
					$opt_val = $ques[0]->$option;
					$footer_query = $this->db->query("SELECT OP_Text FROM OPTIONTABLE WHERE OP_ID = $opt_val ");
					$footer_query = $footer_query->result();
					$rank[$i]['header'] = $header;
					$rank[$i]['footer'] = $footer_query[0]->OP_Text;
					$rank[$i]['stats']  = $rank[$i]['MU'];

					if($flag == 0)
					{
						$rank[$i]['stats'] = $footer_query[0]->OP_Text;
						$rank[$i]['footer'] = '';
					}
					$temp[$k] = $rank[$i];
					$k++;
				}

			}
			$rank = array();
			$rank = $temp;
			if($flag == 1)
			{
			foreach ($rank as $key => $row) 
					 {
							$MU[$key]  = $row['MU'];
					 }
					array_multisort( $MU, SORT_ASC,$rank);
			}
			else if($flag == -1)
			{
				foreach ($rank as $key => $row) 
					 {
							$MU[$key]  = $row['MU'];
					 }
					array_multisort( $MU, SORT_DESC,$rank);
			}
			return $rank;
	}
}
?>