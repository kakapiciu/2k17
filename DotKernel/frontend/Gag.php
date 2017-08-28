<?php
class Gag extends Dot_Model
{

	public function __construct()
	{
		parent::__construct();
	}
	//get a list of gags
	public function getGagList($userId='',$dateDesc='',$postForUser='')
	 { 
	 	if($postForUser!==''){
			$select = $this->db->select()
							   ->from('post')
							   ->where('userId = ?',$postForUser)
							   ->order($dateDesc);
	 	}else {
			$select = $this->db->select()
							   ->from('post')
							   ->order($dateDesc);
	 		
	 	}
 		$result=$this->db->fetchAll($select);
 		$comepletedData = [];
		foreach ($result as $gag) {
			$comepletedData[$gag['id']]['date'] = $gag['date'];
			$comepletedData[$gag['id']]['title'] = $gag['title'];
			$comepletedData[$gag['id']]['id'] = $gag['id'];
			$comepletedData[$gag['id']]['urlimage'] = $gag['urlimage'];
			$comepletedData[$gag['id']]['likes']=0;
			$likes= $this->getLikeByPost($gag['id'], "post");
			// set the key likes with the sum of all likes and get the like for the curent user loged
			 if (isset($likes) && !empty($likes)){
					foreach ($likes as $key=> $like) {
						$comepletedData[$gag['id']]['likes'] +=$like['like'];
						if($like['id_user']==$userId){

		                    foreach ($like as $key1=>$value){
		                        $comepletedData[$gag["id"]]['arrayLikes'][$key][$key1]=$value;

		                    }
						}
	                }
	            }
        }
    if ($dateDesc==='') {
	uasort($comepletedData, 'sort_by_likes');
    }
		

		return $comepletedData;
	}
	
	
	// get details and likes for one post
	public function getGagOrComById($id ,$userId='',$type="post")
    {
        $tabel=$type;
        if ($type==="com"){
            $tabel="comment";
        }
        $select = $this->db->select()
            ->from($tabel)
            ->where('id= ?',$id);

        $likes= $this->getLikeByPost($id, $type);
        $result=$this->db->fetchRow($select);
        $result['likes']=0;
        // set the key likes with the sum of all likes and get the like for the curent user loged
        if (isset($likes) && !empty($likes)){
	        foreach ($likes as $key => $like) {
	            $result["likes"]+=$like['like'];
	            if($like['id_user']==$userId){
		            foreach ($like as $key1=>$value){
		                $result["arrayLikes"][$key][$key1]=$value;
		            }
		        }
	        }
    	}
        return $result;

    }
    //get comment by id
    public function getCommentById($id)
	{	
		$select = $this->db->select()
						   ->from('comment')
						   ->where('comment.id = ?', $id)
						   ->join('user', 'comment.idUser = user.id', ['username' => 'username',
																	'urlimage'=>'urlimage'
																	]);
		$result=$this->db->fetchRow($select);
		return $result;

	}
	//get the comment by the gag id
	public function getLastComment($gagId,$userId)
	{
		$select=$this->db->select()
						->from('comment')
						->where('idPost = ?', $gagId)
                        ->where('idUser = ?', $userId)
						->join('user', 'comment.idUser = user.id', ['username' => 'username',
																	'urlimage'=>'urlimage',
																	])
                        ->order('date DESC');
		$result=$this->db->fetchRow($select);
		return $result;
	}
	//gets all comments parents of an post
	public function getCommentsParents($gagId)
	{
		$select=$this->db->select()
						->from('comment')
						->where('idPost = ?', $gagId)
						->where('parent_id = ?', 0)
						->join('user', 'comment.idUser = user.id', ['username' => 'username',
																	'urlimage'=>'urlimage',
																	]);
		$result=$this->db->fetchAll($select);
		return $result;
	}
	// making an array with comments and replys
	public function getCommentByArticleId($id,$userId='')
	{
		$comepletedData = [];
		$parentsComments= $this->getCommentsParents($id);
		foreach ($parentsComments as $key => $value) {
			$replies = $this->getCommentReplytByCommentId($value['id'],$userId);
			$comepletedData[$value['id']]['content'] = $value['content'];
			$comepletedData[$value['id']]['idUser'] = $value['idUser'];
			$comepletedData[$value['id']]['urlimage'] = $value['urlimage'];
			$comepletedData[$value['id']]['username'] = $value['username'];
			$comepletedData[$value['id']]['date'] = $value['date'];
			$comepletedData[$value['id']]['parent_id'] = $value['parent_id'];
			$comepletedData[$value['id']]['id'] = $value['id'];
            $comepletedData[$value['id']]['likes']=0;
            $likes=$this->getLikeByPost($value['id'], "com");
            // set the key likes with the sum of all likes and get the like for the curent user loged
            if (isset($likes) && !empty($likes)){

	            foreach ($likes as $like) {
	                $comepletedData[$value['id']]['likes']+=$like['like'];
	                if($like['id_user']==$userId){
		                foreach ($like as $keyLike=>$valueLike){
		                    $comepletedData[$value['id']]["arrayLikes"][$key][$keyLike]=$valueLike;
		                }
		            }
	            }
            }

			if(isset($replies) && !empty($replies))
			{
				$comepletedData[$value['id']]['replies'] = $replies;
	        	uasort($comepletedData[$value['id']]['replies'], 'sort_by_likes');
            }
        }

        uasort($comepletedData, 'sort_by_likes');
		return $comepletedData;
	}
	//get coment reply by coment id
	public function getCommentReplytByCommentId($id,$userId='')
	{
		$select = $this->db->select()
	                    ->from('comment')
	                    ->where('parent_id = ?', $id)
	                    ->join('user','user.id = comment.idUser',['username' => 'username',
																	'urlimage'=>'urlimage',
																	]);
	    $result = $this->db->fetchAll($select);
	    foreach ($result as $key => $reply){
            $result[$key]['likes']=0;
            $likes=$this->getLikeByPost($reply['id'], "com");
            // set the key likes with the sum of all likes and get the like for the curent user loged
             if (isset($likes) && !empty($likes)){
	            foreach ($likes as $likeKey => $like) {
	                $result[$key]['likes']+=$like['like'];
	                if($like['id_user']==$userId){
		                foreach ($like as $keyValue=>$value){
		                    $result[$key]["arrayLikes"][$likeKey][$keyValue]=$value;
		                }
		            }
	            }
	        }
    }
    
	    return $result;
	}
	//get all notiifications for one user
	public function getNews($id){
		$select = $this->db->select()
	                    ->from('news')
	                    ->where('id_user_post = ?', $id)
	                    ->join('user','user.id = news.id_user_made',['username' => 'username',
																	'urlimage'=>'urlimage',
																	]);
	                   
	    $result = $this->db->fetchAll($select);
	    return $result;

	}
	// add a new Gag with post method
	public function addGag($data)
	{

		$this->db->insert('post',$data);
	}
	// add a new comment for an atricle with post method
	public function addComment($data)
	{
		$this->db->insert('comment',$data);
	}
	//update gag
	public function updateGag($data , $id)
	{
		$this->db->update('post', $data, 'id = '.$id);
	}
	// delete gag
	public function deleteGag($id)
	{
		$this->db->delete('post', 'id = ' . $id);
		$deleteLikeWhere = array(
		    'id_post = ?' => $id,
		    'type = ?' => "post"
		);
		$this->db->delete('postLike', $deleteLikeWhere);
		$this->db->delete('comment', 'idPost = '. $id);
	}
	//delet comment
	public function deleteComment($id)
	{
		$this->db->delete('comment', 'id = ' . $id);
		$this->db->delete('comment', 'parent_id = ' . $id);
		$deleteLikeWhere = array(
		    'id_post = ?' => $id,
		    'type = ?' => "com"
		);
		$this->db->delete('postLike', $deleteLikeWhere);
	}
	 //updates a comment into the table comment
    public function editCommentById($a, $commentId)
    {
        $this->db->update('comment', $a, 'id = ' . $commentId);
    }
    //add Like or dislke on gag
    public function addLikeOrDislikeGag($data)
    {
    	$this->db->insert('postLike',$data);
    }
    //edit like on gag
    public function editLike($a,$idType,$idUser , $type)
    {
    	$where = array(
		    'id_post = ?' => $idType,
		    'id_user = ?' => $idUser,
		    'type = ?' => $type
		);
		$this->db->update('postLike', $a, $where);
    }
    // get like on id post and id user
    public function getLike ($postId, $userId, $type)
    {
    	$select = $this->db->select()
						   ->from('postLike')
						   ->where('id_post= ?', $postId)
						   ->where('id_user= ?', $userId)
						   ->where('type= ?', $type);;

		$result=$this->db->fetchRow($select);

		return $result;
    }
    // get like on id post
    public function getLikeByPost ($postId , $type)
    {
    	$select = $this->db->select()
						   ->from('postLike')
						   ->where('id_post= ?', $postId)
                            ->where('type = ?', $type);
		$result=$this->db->fetchAll($select);
		return $result;
    }
}
// sort funtion
function sort_by_likes($a, $b)
	{
	    return $b['likes'] - $a['likes'];
	}