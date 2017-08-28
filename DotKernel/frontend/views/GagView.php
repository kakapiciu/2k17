<?php
class Gag_View extends View
{

	public function __construct($tpl)
	{
		$this->tpl = $tpl;
		$this->settings = Zend_Registry::get('settings');
		$this->session = Zend_Registry::get('session');
	}
	public function showGagList($templateFile='', $gagList )
	{ 
		if ($templateFile != '') $this->templateFile = $templateFile;
		$this->tpl->setFile('tpl_main', 'gag/' . $this->templateFile . '.tpl');
		$this->tpl->setBlock('tpl_main','gag_list',"gag_list_block");

		foreach ($gagList as $gag) {
            $this->tpl->setVar("GAG_ID", $gag['id']);
            $this->tpl->setVar("GAG_URLIMAGE", $gag['urlimage']);
            $this->tpl->setVar("GAG_TITLE", $gag['title']);
            $this->tpl->setVar("GAG_LIKES", $gag['likes']);
            $this->tpl->setVar("GAG_DATE", $gag['date']);
            // set the like and dislike buttone condition base on data from db
            if (isset($this->session->user->id) && isset($gag['arrayLikes'])&& !empty($gag['arrayLikes'])){

                foreach ($gag['arrayLikes'] as $like) {

                    if($like['id_user']==$this->session->user->id)
                    {
                        switch ($like['like']) {

                            case '1':
                                $this->tpl->setVar('USER_LIKED', 'color:red;');
                                $this->tpl->setVar('USER_DISLIKE', '');
                                break;
                            case '0':
                                $this->tpl->setVar('USER_LIKED', '');
                                $this->tpl->setVar('USER_DISLIKE', '');
                                break;
                            case '-1':
                                $this->tpl->setVar('USER_LIKED', '');
                                $this->tpl->setVar('USER_DISLIKE', 'color:red;');
                                break;
                        }
                    } else {
                        $this->tpl->setVar('USER_LIKED', '');
                        $this->tpl->setVar('USER_DISLIKE', '');
                    }
                }
            } else {
                $this->tpl->setVar('USER_LIKED', '');
                $this->tpl->setVar('USER_DISLIKE', '');
            }

            $this->tpl->parse("gag_list_block", 'gag_list', true);
		}
	}
	public function showGagEdit($templateFile='', $gag)
	{      
        // Zend_Debug::dump($gag); exit;
		if ($templateFile != '') $this->templateFile = $templateFile;
		$this->tpl->setFile('tpl_main', 'gag/' . $this->templateFile . '.tpl');
	
		$this->tpl->setVar("GAG_DATE",$gag['date']);
        $this->tpl->setVar("GAG_URLIMAGE",$gag['urlimage']);
        $this->tpl->setVar("GAG_TITLE",$gag['title']);
    }
	//add template for adding gag
	public function showAddGag($templateFile="" )
	{
		if ($templateFile != '') $this->templateFile = $templateFile;
		$this->tpl->setFile('tpl_main', 'gag/' . $this->templateFile . '.tpl');
	}
	// showing gag by id
	public function showGagById($templateFile='', $gag)
	{ 
		if ($templateFile != '') $this->templateFile = $templateFile;
		$this->tpl->setFile('tpl_main', 'gag/' . $this->templateFile . '.tpl');
                $this->tpl->setVar("GAG_ID", $gag['id']);
                $this->tpl->setVar("GAG_URLIMAGE", $gag['urlimage']);
                $this->tpl->setVar("GAG_TITLE", $gag['title']);
                $this->tpl->setVar("GAG_LIKES", $gag['likes']);
        // set the like and dislike buttone condition base on data from db
        if (isset($this->session->user->id) && isset($gag['arrayLikes'])&& !empty($gag['arrayLikes'])) {
            foreach ($gag['arrayLikes'] as $like) {

                if ($like['id_user'] == $this->session->user->id) {
                    switch ($like['like']) {

                        case '1':
                            $this->tpl->setVar('USER_LIKED', 'active');
                            $this->tpl->setVar('USER_DISLIKE', '');
                            break;
                        case '0':
                            $this->tpl->setVar('USER_LIKED', '');
                            $this->tpl->setVar('USER_DISLIKE', '');
                            break;
                        case '-1':
                            $this->tpl->setVar('USER_LIKED', '');
                            $this->tpl->setVar('USER_DISLIKE', 'active');
                            break;
                    }
                } else {
                    $this->tpl->setVar('USER_LIKED', '');
                    $this->tpl->setVar('USER_DISLIKE', '');
                }
            }
        }else {
            $this->tpl->setVar('USER_LIKED', '');
            $this->tpl->setVar('USER_DISLIKE', '');
        }

	}
	public function showComment ($templateFile='', $comment){
		if ($templateFile != '') $this->templateFile = $templateFile;
		$this->tpl->setFile('tpl_main', 'gag/' . $this->templateFile . '.tpl');
		foreach ($comment as $context) {
			foreach ($context as $contextKey => $contextValue) {
			$this->tpl->setVar(strtoupper("COMMENT_".$contextKey),$contextValue);
			}
		}

	}
	// showing commend list for Gag
	public function showComments($templateFile='', $commentList)
	{
		if ($templateFile != '') $this->templateFile = $templateFile;
		$this->tpl->setFile('tpl_main', 'gag/' . $this->templateFile . '.tpl');
		$this->tpl->setBlock('tpl_main','comment_list',"comment_list_block");
		$this->tpl->setBlock('comment_list','comment_reply','comment_reply_block');
		$this->tpl->setBlock('comment_list','comment_list_buttones','comment_list_buttones_block');
		$this->tpl->setBlock('comment_reply','comment_reply_buttones','comment_reply_buttones_block');

		foreach ($commentList as $comment) {
            $this->tpl->setVar("COMMENT_URLIMAGE",$comment['urlimage']);
			$this->tpl->setVar("COMMENT_USERNAME",$comment['username']);
			$this->tpl->setVar("COMMENT_ID",$comment['id']);
            $this->tpl->setVar("COMMENT_LIKES",$comment['likes']);
			$this->tpl->setVar("COMMENT_CONTENT",$comment['content']);
			$this->tpl->setVar("COMMENT_DATE",$comment['date']);
            // set the like and dislike buttone condition base on data from db
            if (isset($this->session->user->id) && isset($comment['arrayLikes'])&& !empty($comment['arrayLikes'])) {
                foreach ($comment['arrayLikes'] as $like) {

                    if ($like['id_user'] == $this->session->user->id) {
                        switch ($like['like']) {

                            case '1':
                                $this->tpl->setVar('COMMENT_LIKED', 'active');
                                $this->tpl->setVar('COMMENT_DISLIKE', '');
                                break;
                            case '0':
                                $this->tpl->setVar('COMMENT_LIKED', '');
                                $this->tpl->setVar('COMMENT_DISLIKE', '');
                                break;
                            case '-1':
                                $this->tpl->setVar('COMMENT_LIKED', '');
                                $this->tpl->setVar('COMMENT_DISLIKE', 'active');
                                break;
                        }
                    } else {
                        $this->tpl->setVar('COMMENT_LIKED', '');
                        $this->tpl->setVar('COMMENT_DISLIKE', '');
                    }
                }
            }else {
                $this->tpl->setVar('COMMENT_LIKED', '');
                $this->tpl->setVar('COMMENT_DISLIKE', '');
            }
			if (isset($this->session->user->id)){
				$this->tpl->parse('comment_list_buttones_block','');
				if ($comment['idUser']==$this->session->user->id){
					$this->tpl->parse("comment_list_buttones_block", 'comment_list_buttones', true);
				}
			}
			$this->tpl->parse('comment_reply_block','');

			if(isset($comment['replies'])) {
                foreach($comment['replies'] as $replyKey => $reply) {

                    $this->tpl->setVar('REPLY_USERNAME',$reply['username']);
                    $this->tpl->setVar('REPLY_ID',$reply['id']);
                    $this->tpl->setVar('REPLY_LIKES',$reply['likes']);
                    $this->tpl->setVar('REPLY_CONTENT',$reply['linkUser'].$reply['content']);
					$this->tpl->setVar('REPLY_DATE',$reply['date']);
                    $this->tpl->setVar('REPLY_URLIMAGE',$reply['urlimage']);
					// set the like and dislike buttone condition base on data from db
                    if (isset($this->session->user->id) && isset($reply['arrayLikes']) && !empty($reply['arrayLikes'])) {
                        foreach ($reply['arrayLikes'] as $like) {

                            if ($like['id_user'] == $this->session->user->id) {
                                switch ($like['like']) {

                                    case '1':
                                        $this->tpl->setVar('REPLY_LIKED', 'active');
                                        $this->tpl->setVar('REPLY_DISLIKE', '');
                                        break;
                                    case '0':
                                        $this->tpl->setVar('REPLY_LIKED', '');
                                        $this->tpl->setVar('REPLY_DISLIKE', '');
                                        break;
                                    case '-1':
                                        $this->tpl->setVar('REPLY_LIKED', '');
                                        $this->tpl->setVar('REPLY_DISLIKE', 'active');
                                        break;
                                }
                            } else {
                                $this->tpl->setVar('REPLY_LIKED', '');
                                $this->tpl->setVar('REPLY_DISLIKE', '');
                            }
                        }
                    }else {
                        $this->tpl->setVar('REPLY_LIKED', '');
                        $this->tpl->setVar('REPLY_DISLIKE', '');
                    }

                	if (isset($this->session->user->id)){
	                	$this->tpl->parse('comment_reply_buttones_block','');
						if ($reply['idUser']==$this->session->user->id){
							$this->tpl->parse("comment_reply_buttones_block", 'comment_reply_buttones', true);
						}
					}
					 $this->tpl->parse('comment_reply_block','comment_reply',true);
                    }
                   
                }
			
			$this->tpl->parse("comment_list_block", 'comment_list', true);
		}

	}


}