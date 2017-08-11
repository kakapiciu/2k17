<?php
class Gag extends Dot_Model
{

	public function __construct()
	{
		parent::__construct();
	}
	public function getGagList($page=1)
	{
		$select = $this->db->select()
						   ->from('post');
 		$dotPaginator = new Dot_Paginator($select, $page, $this->settings->resultsPerPage);
		return $dotPaginator->getData();
	}
	// i have modified method name form getGag to getGagById
	public function getGagById($id)
	{	
		$select = $this->db->select()
						   ->from('post')
						   ->where('id= ?',$id);
 		// $dotPaginator = new Dot_Paginator($select, $page, $this->settings->resultsPerPage);
		$result=$this->db->fetchAll($select);
		return $result;

	}
	public function getCommentById($id)
	{	
		$select = $this->db->select()
						   ->from('comment')
						   ->where('id= ?',$id);
 		// $dotPaginator = new Dot_Paginator($select, $page, $this->settings->resultsPerPage);
		$result=$this->db->fetchAll($select);
		return $result;

	}
	//get the comment by the gag id
	public function getComments($gagId)
	{
		$select=$this->db->select()
						->from('comment')
						->where('idPost = ?', $gagId)
						->join('user', 'comment.idUser = user.id', ['username' => 'username']);
		$result=$this->db->fetchAll($select);
		return $result;
	}
	public function getCommentsParents($gagId)
	{
		$select=$this->db->select()
						->from('comment')
						->where('idPost = ?', $gagId)
						->where('parent_id = ?', 0)
						->join('user', 'comment.idUser = user.id', ['username' => 'username']);
		$result=$this->db->fetchAll($select);
		return $result;
	}
	// making an array with comments and replys
	public function getCommentByArticleId($id)
	{
		$comepletedData = [];
		$parentsComments= $this->getCommentsParents($id);
		foreach ($parentsComments as $key => $value) {
			$replies = $this->getCommentReplytByCommentId($value['id']);
			$comepletedData[$value['id']]['content'] = $value['content'];
			$comepletedData[$value['id']]['idUser'] = $value['idUser'];
			$comepletedData[$value['id']]['username'] = $value['username'];
			$comepletedData[$value['id']]['date'] = $value['date'];
			$comepletedData[$value['id']]['parent_id'] = $value['parent_id'];
			$comepletedData[$value['id']]['id'] = $value['id'];
			if(isset($replies) && !empty($replies))
			{
				$comepletedData[$value['id']]['replies'] = $replies;
			}
		}
		return $comepletedData;
	}
	//get coment reply by coment id
	public function getCommentReplytByCommentId($id)
	{
		$select = $this->db->select()
	                    ->from('comment',array('content','date','idUser', 'id','parent_id'))
	                    ->where('parent_id = ?', $id)
	                    ->join('user','user.id = comment.idUser','username');
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
	public function updateGag($data , $id)
	{
		$this->db->update('post', $data, 'id = '.$id);
	}
	public function deleteGag($id)
	{
		$this->db->delete('post', 'id = ' . $id);
	}

	public function deleteComment($id)
	{
		$this->db->delete('comment', 'id = ' . $id);
		$this->db->delete('comment', 'parent_id = ' . $id);
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
    public function editLike($a,$likeId)
    {
    	$this->db->update('postLike', $a, 'id = ' . $likeId);
    }
    // get like 
    public function getLike ($postId, $userId)
    {
    	$select = $this->db->select()
						   ->from('postLike')
						   ->where('id_post= ?', $postId)
						   ->where('id_user= ?', $userId);
		$result=$this->db->fetchAll($select);
		return $result[0];
    }
}