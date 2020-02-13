<?php
require_once __DIR__.'/ErrorCode.php';
class Article{
	
	private $_db;

	//构造方法
	public function __construct($_db){
		$this->_db = $_db;
	}

	//创建文章
	public function create ($title,$content,$userId){
		if(empty($title)){
			throw new Exception('文章标题不能为空',ErroCode::ARTICLE_TITLE_CANNOT_EMPTY);		
		}
		if(empty($content)){
			throw new Exception("文章内容不能为空", ErroCode::ARTICLE_CONTENT_CANNOT_EMPTY);
		}
		$sql = 'insert into `article` (`title`,`content`,`userId`,`createdAt`) values (:title,:content,:userId,:createdAt)'; 
		$createdAt =time();
		$stmt =$this->_db->prepare($sql);
		$stmt->bindParam(':title',$title);
		$stmt->bindParam(':content',$content);
		$stmt->bindParam(':createdAt',$createdAt);
		$stmt->bindParam(':userId',$userId);
		if(!$stmt->execute()){
			throw new Exception("发表文章失败", ErroCode::ARTICLE_CREATE_FAIL);
		}
		return [
		'articleId'=>$this->_db->lastInsertId(),
		'title' =>$title,
		'content'=>$content,
		'userId'=>$userId,
		'createdAt'=>$createdAt

		];

	}

	public function view($articleId){
		if (empty($articleId)) {
			throw new Exception("文章ID不能为空", ErroCode::ARTICLE_ID_CANNOT_EMPTY);	
		}
		$sql = 'select * from `article` where `articleId` =:id';
		$stmt =  $this->_db->prepare($sql);
		$stmt->bindParam(':id',$articleId);
		$stmt->execute();
		$article = $stmt->fetch(PDO::FETCH_ASSOC);
		if(empty($article)){
			throw new Exception("文章不存在", ErroCode::ARTICLE_NOT_FOUND);
		}

		return $article;

	}	

	//编辑文章
	public function edit($articleId,$title,$content,$userId){
			$article = $this->view($articleId);
			if($article['userId']!=$userId){
				throw new Exception("你无权编辑文章", ErroCode::PERMISSION_DENIED);
			}
			$title = empty($title)?$article['title']:$title;
			$content = empty($content)?$article['content']:$content;
			if ($title ===$article['title'] && $content===$article['content']) {
				return $article;
			}
			$sql = 'update `article` set `title`=:title,`content`=:content where `articleId`=:articleId';
			$stmt=$this->_db->prepare($sql);
			$stmt->bindParam(':title',$title);
			$stmt->bindParam(':content',$content);
			$stmt->bindParam(':articleId',$articleId);
			if(!$stmt->execute()){
				throw new Exception("文章编辑失败", ErroCode::ARTICLE_EDIT_FAIL);
			}

			return [
				'articleId'=>$articleId,
				'title'=>$title,
				'content'=>$content,
				'createdAt'=>$article['createdAt']
			];	

	}
	//删除文章
	public function delete($articleId,$userId){

		$article = $this->view($articleId);
		if (article['userId']!=$userId) {
			throw new Exception("你无权操作", ErroCode::PERMISSION_DENIED);
		}

		$sql = 'delete from `article`  where `articleId` =:id AND `userId`=:userId';
		$stmt    = $this->_db->prepare($sql);
		$stmt->bindParam(':articleId',$articleId);
		$stmt->bindParam(':userId',$userId);
		$stmt->execute();
		if(false===$stmt->execute()){
			throw new Exception("删除失败", ErroCode::ARTICLE_DELETE_FAIL);
		}
		return true;


	}

	//读取文章列表
	public function getList($userId,$page=1,$size=5){
		if ($size>100) {
			throw new Exception("分页大小最大值为100", ErroCode::PAGE_SIZE_TO_BIG);
		}
		$sql = 'select * from `article` where `userId` =:userId limit  :limit,:offset';
		$limit = ($page-1)*$size;
		$limit = $limit<0 ?0:$limit;
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':userId',$userId);
		$stmt->bindParam(':limit',$limit);
		$stmt->bindParam(':offset',$size);
		$stmt->execute();
		$data =$stmt->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}

}