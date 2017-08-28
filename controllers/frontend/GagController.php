<?php

$session = Zend_Registry::get('session');

$gagView = new Gag_View($tpl);
$gagModel= new Gag();

$pageTitle = $option->pageTitle->action->{$registry->requestAction};
if (!empty($session->user->id)|| isset($session->user->id)){

$userIdLoged=$session->user->id;
} else {
$userIdLoged='';
}
switch ($registry->requestAction)
{
	default:
	case 'list-by-rank':

 		$list= $gagModel->getGagList($userIdLoged);
 		$gagView->showGagList('gag_list', $list);
		break;
	case 'list-by-date':
		$list= $gagModel->getGagList($userIdLoged,'date DESC');
		$gagView->showGagList('gag_list', $list);
	break;
		case 'list-by-user':
 		$list= $gagModel->getGagList($userIdLoged,'',$userIdLoged);
 		$gagView->showGagList('gag_list_by_user', $list);
		break;
	case 'edit-gag':
		$gag= $gagModel->getGagOrComById($registry->request["id"],$userIdLoged);
		if ($_SERVER['REQUEST_METHOD'] === "POST") {	
			$data=['title'=>$_POST["title"],
					];
			$gagModel->updateGag($data,$registry->request["id"]);
			$registry->session->message['txt'] = $option->infoMessage->gagUpdate;
			$registry->session->message['type'] = 'info';
			header('Location: '.$registry->configuration->website->params->url.'/gag/list-by-user');
			exit;
		}
		$gagView->showGagEdit('gag_edit',$gag);
		break;
	case 'add':
		// adding a new gag action
		if ($_SERVER['REQUEST_METHOD'] === "POST")
				{
					$uploaddir = 'externals/gags/';
					$uploadfile = $uploaddir . basename($_FILES['picture']['name']);
					move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile);
					$data=['title'=>htmlentities($_POST["title"]),
							'userId'=>$session->user->id,
							'urlimage'=>$registry->configuration->website->params->url.'/'.$uploadfile,
					];
				$gagModel->addGag($data);
				$registry->session->message['txt'] = $option->infoMessage->gagAdd;
				$registry->session->message['type'] = 'info';
				header('Location: '.$registry->configuration->website->params->url.'/gag/list');
				exit;
				}
		$gagView->showAddGag('gag_add');
		break;
	case 'show':
		$singelGagData=$gagModel->getGagOrComById($registry->request["id"],$userIdLoged);
		$commentsList=$gagModel->getCommentByArticleId($registry->request["id"],$userIdLoged);
		$gagView->showGagById('complet_gag',$singelGagData);
		$gagView->showComments('complet_gag',$commentsList);
		break;
	case 'comment':
		if (!empty($session->user->id)){
			if (isset($_POST['parent_id'])||!empty($_POST['parent_id']))
			{  
					 if (array_key_exists('id', $_POST)) {
		                        $comment = [
		                                'content' => htmlentities($_POST['conntent'])
		                                ];
		                        $gagModel->editCommentById($comment, $_POST['gagId']);
		                    } else {
		                    	$match='';
		                    	$parentComment=$gagModel->getCommentById($_POST['parent_id']);

								$data=['idUser'=>$session->user->id,
								'content'=>htmlentities($_POST["conntent"]),
								'idPost'=>$_POST['gagId'],
								'parent_id'=>$_POST['parent_id']
								];
		                    	if (preg_match('/^@'.$parentComment['username'].' /', $_POST['conntent'], $match)) {
		                    	$data['content']=preg_replace('/^@'.$parentComment['username'].' /','', $_POST['conntent'],1);
		                    	$data['linkUser']="<a>".$match[0]."</a>";
		                    	}
								$gagModel->addComment($data);
                                $newComment=$gagModel->getLastComment($_POST['gagId'],$session->user->id);
                         $result=['success'=>"true",
                                     "likes"=>'0',
                                     'commentId'=>$newComment['id'],
                                     'parent_id'=>$newComment['parent_id'],
                                     'date'=>$newComment['date'],
                                     'username'=>$newComment['username'],
                                     'conntent'=> $newComment['linkUser'].$newComment['content'],
                                     'urlimage'=> $newComment['urlimage'],
                                     "id"=>1];
                         echo Zend_Json::encode($result);
                         exit;
                     }
				}
		} else {
				$_SESSION['saveUrl']=$_SERVER["HTTP_REFERER"];
				echo Zend_Json::encode(false);
				exit;
			}
		break;
	case 'delete-gag':
		if($_SERVER['REQUEST_METHOD'] === "POST")
		{
			
			
			if ('on' == $_POST['confirm'])
			{
				// delete gag
				$gagModel->deleteGag($registry->request['id']);
				$registry->session->message['txt'] = $option->infoMessage->gagDelete;
				$registry->session->message['type'] = 'info';
			}
			else
			{
				$registry->session->message['txt'] = $option->errorMessage->gagDeleteFail;
				$registry->session->message['type'] = 'error';
			}
			header('Location: '.$registry->configuration->website->params->url. '/' . $registry->requestModule . '/' . $registry->requestController. '/list/');
			exit;
		}
		if (!$registry->request['id'])
		{
			header('Location: '.$registry->configuration->website->params->url. '/' . $registry->requestModule . '/' . $registry->requestController. '/list/');
			exit;
		}
		$data = $gagModel->getGagOrComById($registry->request['id'],$userIdLoged);
		// delete page confirmation
		$gagView->showGagById('delete_gag', $data);
		break;
	case 'delete-comment':
	//delete comment and hes replays and redirect to gag
		if($_SERVER['REQUEST_METHOD'] === "POST")
		{
			
			
			if ('on' == $_POST['confirm'])
			{
				// delete gag
				$gagModel->deleteComment($registry->request['id']);
				// $registry->session->message['txt'] = $option->infoMessage->gagDelete;
				// $registry->session->message['type'] = 'info';
			}
			else
			{
				// $registry->session->message['txt'] = $option->errorMessage->gagDeleteFail;
				// $registry->session->message['type'] = 'error';
			}
			header('Location: '.$_SESSION['saveUrl']);
			unset($_SESSION['saveUrl']);
			exit;
		}
		if (!$registry->request['id'])
		{
			header('Location: '.$_SESSION['saveUrl']);
			unset($_SESSION['saveUrl']);
			exit;
		}
		$_SESSION['saveUrl']=$_SERVER["HTTP_REFERER"];
		$data = $gagModel->getCommentById($registry->request['id']);
		// delete page confirmation
		$gagView->showComment('delete_comment', $data);
		break;
	case 'like':
	// add or update the like and check if user is loged in else redirect him to login 
	if (!empty($session->user->id)){
		if (isset($_POST['id'])||!empty($_POST['id']))
		{ 

				$like=$gagModel->getLike($_POST['id'],$session->user->id,$_POST['type']);
				
				if (!empty($like) && !empty($like)) 
				{
						if($like['like']=='1')
						{
							$editLike=['like'=>'0'];

                            $gagModel->editLike($editLike,$like['id_post'],$like['id_user'],$like['type']);

                            $singelGagData=$gagModel->getGagOrComById($_POST['id'],$userIdLoged , $_POST['type']);
                            $result=['success'=>"true",
                            	"likes"=>$singelGagData['likes'],
                            	'postId'=>$_POST['id'],
                                "id"=>0];
                        } elseif($like['like']=='-1') {
                            $editLike=['like'=>'0'];

                            $gagModel->editLike($editLike,$like['id_post'],$like['id_user'],$like['type']);
                            $singelGagData=$gagModel->getGagOrComById($_POST['id'],$userIdLoged ,$_POST['type']);
                            $result=['success'=>"true",
                            "likes"=>$singelGagData['likes'],
                            'postId'=>$_POST['id'],
                                "id"=>-1];
                        } else {
                            $editLike=['like'=>'1'];

                            $gagModel->editLike($editLike,$like['id_post'],$like['id_user'],$like['type']);
                            $singelGagData=$gagModel->getGagOrComById($_POST['id'],$userIdLoged ,$_POST['type']);
                            $result=['success'=>"true",
                            "likes"=>$singelGagData['likes'],
                            'postId'=>$_POST['id'],
                                "id"=>1];
                        }
				}
				else 
				{

						$data=['id_post'=>$_POST['id'],
								'id_user'=>$session->user->id,
								'type'=>$_POST['type'],
								'like'=>"1",
								];
						$gagModel->addLikeOrDislikeGag($data);
						$singelGagData=$gagModel->getGagOrComById($_POST['id'],$userIdLoged ,$_POST['type']);
						$result=['success'=>"true",
						"likes"=>$singelGagData['likes'],
						'postId'=>$_POST['id'],
								"id"=>1];
				 	 }
			echo Zend_Json::encode($result);
			exit;
			}
	} else {
			$_SESSION['saveUrl']=$_SERVER["HTTP_REFERER"];
			echo Zend_Json::encode(false);
			exit;
		}
		break;
    case 'dislike':
    // add or update the dislike and check if user is loged in else redirect him to login 
    	if (!empty($session->user->id)){
	        if (isset($_POST['id'])||!empty($_POST['id']))
	        {
	            $like=$gagModel->getLike($_POST['id'],$session->user->id,$_POST['type']);
	            if (isset($like) && !empty($like))
	            {
	                if($like['like']=='-1')
	                {
	                    $editLike=['like'=>'0'];

	                    $gagModel->editLike($editLike,$like['id_post'],$like['id_user'],$like['type']);
	                    $singelGagData=$gagModel->getGagOrComById($_POST['id'],$userIdLoged ,$_POST['type']);
	                    $result=['success'=>"true",
	                    "likes"=>$singelGagData['likes'],
	                    'postId'=>$_POST['id'],
	                        "id"=>0];
	                } elseif($like['like']=='0') {
	                    $editLike=['like'=>'-1'];

	                    $gagModel->editLike($editLike,$like['id_post'],$like['id_user'],$like['type']);
	                    $singelGagData=$gagModel->getGagOrComById($_POST['id'],$userIdLoged ,$_POST['type']);
	                    $result=['success'=>"true",
	                    "likes"=>$singelGagData['likes'],
	                    'postId'=>$_POST['id'],
	                        "id"=>-1];
	                } else {
	                    $editLike=['like'=>'0'];

	                    $gagModel->editLike($editLike,$like['id_post'],$like['id_user'],$like['type']);
	                    $singelGagData=$gagModel->getGagOrComById($_POST['id'],$userIdLoged ,$_POST['type']);
	                    $result=['success'=>"true",
	                    "likes"=>$singelGagData['likes'],
	                    'postId'=>$_POST['id'],
	                        "id"=>1];
	                }
	            }
	            else
	            {

	                $data=['id_post'=>$_POST['id'],
	                    'id_user'=>$session->user->id,
	                    'type'=>$_POST['type'],
	                    'like'=>"-1",
	                ];
	                $gagModel->addLikeOrDislikeGag($data);
	                $singelGagData=$gagModel->getGagOrComById($_POST['id'],$userIdLoged ,$_POST['type']);
	                $result=['success'=>"true",
	                "likes"=>$singelGagData['likes'],
	                'postId'=>$_POST['id'],
	                    "id"=>-1];
	            }
	        echo Zend_Json::encode($result);
	        exit;
	        }
        } else {
        	$_SESSION['saveUrl']=$_SERVER["HTTP_REFERER"];
			echo Zend_Json::encode(false);
			exit;
		}
        break;
}
