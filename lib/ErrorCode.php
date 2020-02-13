<?php
class ErroCode{
	const USERNAME_EXISTS=1;//用户存在
	const PASSWORD_CANNOT_EMPTY=2;//密码不能为空
	const USERNAME_CANNOT_EMPTY=3;//用户名不能为空
	const REGISTER_FAIL = 4;//用户注册失败
	const USERNAME_OR_PASWORD_INVALID=5;//用户名或密码错误
 	const ARTICLE_TITLE_CANNOT_EMPTY=6;//文章名不能为空
 	const ARTICLE_CONTENT_CANNOT_EMPTY=7;//文章内容不能为空
 	const ARTICLE_CREATE_FAIL=8;//文章发表失败
 	const ARTICLE_ID_CANNOT_EMPTY=9; //文章ID不能为空
 	const ARTICLE_NOT_FOUND=10; //文章不存在
 	const PERMISSION_DENIED=11; //无权编辑文章
 	const ARTICLE_EDIT_FAIL=12; //文章编辑失败
 	const PAGE_SIZE_TO_BIG =13;
}