<?php
		
Class blogController extends baseController
{
	public function index()
	{
		$this->template->setTitle("blog Index");
		$this->template->render("blog/blog_index");
	}


	public function article($id='')
	{
		$this->template->setTitle("article Page");
		$this->template->render("blog/blog_article");
	}

}

// End of blog Controller
// Filename: blog.php