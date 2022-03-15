<?php

namespace App\blog\Controller;

use App\blog\Model\PostManager;
use App\blog\Model\UserManager;
use App\blog\Model\CommentManager;

class Post extends Controller
{
    //display all posts
    public function allPosts()
    {
        $user = $this->isAdmin();
        $postsManager = new PostManager();
        $posts = $postsManager->getPosts();
        return $this->twig->display('index.html.twig', [
            'posts' => $posts,
            'user' => $user
        ]);
    }

    //display a selected post
    public function getOnePost($id)
    {
        $superglobals = new SuperGlobals();
        $get = $superglobals->getGET();

        $user = $this->isAdmin();
        $postManager = new PostManager();
        //dd($get['id']);
        $post = $postManager->getPost($id);

        // display comments of post
        $commentsManager = new CommentManager();
        $comments = $commentsManager->getComments($id);

        return $this->twig->display('onePost.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'user' => $user
        ]);
    }

    //create a new post
    public function createPost($title, $chapo, $description)
    {
        $superglobals = new SuperGlobals();
        $files = $superglobals->getFILES();

        $user = $this->isAdmin();
        $postsManager = new PostManager();
        $posts = $postsManager->getPosts();

        $title = htmlspecialchars($title);
        $chapo = htmlspecialchars($chapo);
        $description = htmlspecialchars($description);

        if (isset($files['photo']) && $files['photo']['error'] == 0) {
            if ($files['photo']['size'] <= 4000000) {
                $fileInfo = pathinfo($files['photo']['name']);
                $extension = $fileInfo['extension'];
                $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
                if (in_array($extension, $allowedExtensions)) {
                    $uniqueName = uniqid('', true);
                    $file = $uniqueName . "." . $extension;
                    move_uploaded_file($files['photo']['tmp_name'], 'uploads/' . basename($file));
                }
            }
        };

        if (!empty($title) && !empty($chapo) && !empty($description)) {
            $user = $this->isAdmin();
            $id = $user['id'];
            $postManager = new PostManager();
            $postManager->createPost($id, $title, $chapo, $description, $file);
            return header('Location: index.php');
            /*return $this->twig->display('index.html.twig', [
                'posts' => $posts,
                'user' => $user
            ]);*/
        } 
        return $this->twig->display('administration.html.twig', [
            'vide' => true
        ]);
    }

    //page update post
    public function pageUpdatePost($id)
    {
        $superglobals = new SuperGlobals();
        $get = $superglobals->getGET();

        $user = $this->isAdmin();
        $postManager = new PostManager();
        $post = $postManager->getPost($id);

        return $this->twig->display('updateOnePost.html.twig', [
            'post' => $post,
            'user' => $user
        ]);
    }

    //update a post
    public function updatePost($id, $title, $chapo, $description)
    {
        $superglobals = new SuperGlobals();
        $files = $superglobals->getFILES();

        $postsManager = new PostManager();
        $posts = $postsManager->getPosts();

        $title = htmlspecialchars($title);
        $chapo = htmlspecialchars($chapo);
        $description = htmlspecialchars($description);

        $postManager = new PostManager();
        $post = $postManager->getPost($id);

        if (isset($files['photo']) && $files['photo']['error'] == 0) {
            $fichier = './uploads/' . $post['picture'];
            if (file_exists($fichier)) {
                unlink($fichier);
            }
            if ($files['photo']['size'] <= 4000000) {
                $fileInfo = pathinfo($files['photo']['name']);
                $extension = $fileInfo['extension'];
                $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
                if (in_array($extension, $allowedExtensions)) {
                    $uniqueName = uniqid('', true);
                    $file = $uniqueName . "." . $extension;
                    move_uploaded_file($files['photo']['tmp_name'], 'uploads/' . basename($file));
                }
            }
        };

        if (!empty($title) && !empty($chapo) && !empty($description)) {
            $postManager->updatePost($id, $title, $chapo, $description, $file);
            $user = $this->isAdmin();
            return $this->twig->display('index.html.twig', [
                'posts' => $posts,
                'user' => $user
            ]);
        }
        //dd('test');
        return $this->twig->display('updateOnePost.html.twig', [
            'vide' => true
        ]);
    }

    //delete a post
    public function deletePost($id)
    {
        $user = $this->isAdmin();
        
        $superglobals = new SuperGlobals();
        $get = $superglobals->getGET();

        $postsManager = new PostManager();
        $posts = $postsManager->getPosts();

        $commentsManager = new CommentManager();
        $allComments = $commentsManager->getAllComments();

        $UsersManager = new UserManager();
        $allUsers = $UsersManager->getAllUsers();
        
        $postManager = new PostManager();
        $post = $postManager->getPost($id);

        $fichier = './uploads/' . $post['picture'];
        if (file_exists($fichier)) {
            unlink($fichier);
        }

        $postManager->deletePost($id);
        return header('Location: ../pageAdministration');
        /*return $this->twig->display('administration.html.twig', [
            'user' => $user,
            'allUsers' => $allUsers,
            'posts' => $posts,
            'allComments' => $allComments
        ]);*/
    }

    //delete a picture
    public function deletePicture($id)
    {
        $superglobals = new SuperGlobals();
        $get = $superglobals->getGET();

        $postManager = new PostManager();
        $post = $postManager->getPost($id);

        $fichier = './uploads/' . $post['picture'];
        if (file_exists($fichier)) {
            unlink($fichier);
        }

        $postManager->deletePicture($id);
        return header('Location: pageUpdatePost/' . $id);
    }
}
