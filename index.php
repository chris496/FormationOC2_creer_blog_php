<?php

use App\blog\Controller\Comment;
use App\blog\Controller\Post;

require('./vendor/autoload.php');

if (isset($_GET['action']))
{
    // Display all posts
    if ($_GET['action'] == 'allposts')
    {
        $allPosts = new Post();
        $allPosts->allPosts();
    }
    //display a selected post
    elseif ($_GET['action'] == 'getOnePost')
    {
        if (isset($_GET['id']) && $_GET['id'] > 0)
        {
            $getOnePost = new Post();
            $getOnePost->getOnePost();
        }
    }
    //create a new post
    elseif ($_GET['action'] == 'createPost'){
        if (!empty($_POST['title']) && !empty($_POST['chapo'] ) && !empty($_POST['description'] )){
            $createPost = new Post();
            $createPost->createPost($_POST['id'], $_POST['title'], $_POST['chapo'], $_POST['description']);
        }
        else{
            echo 'tous les champs ne sont pas remplis !';
        }
    }
    //create a comment
    elseif ($_GET['action'] == 'createComment'){
        if (isset($_GET['id']) && $_GET['id'] > 0)
        {
            if (!empty($_POST['pseudo']) && !empty($_POST['email'] ) && !empty($_POST['description'] )){
                $createComment = new Comment();
                $createComment->createComment($_GET['id'], $_POST['pseudo'], $_POST['email'], $_POST['description']);
            }
            else{
                echo 'tous les champs ne sont pas remplis !';
            }
        }
        else {
            echo 'Erreur : aucun identifiant de post envoyé';
        }
    }
}
else
{
    $allPosts = new Post();
    $allPosts->allPosts();
    dd( date('Y-m-d H:i:s'));
}
