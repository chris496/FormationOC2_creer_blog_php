<?php
namespace App\blog\Controller;

use App\blog\Model\CommentManager;

class comment
{
    //create a new comment
    public function createComment($postId, $pseudo, $email, $description)
    {
        $commentManager = new CommentManager();
        $newComment = $commentManager->postComment($postId, $pseudo, $email, $description);
        header('Location: index.php?action=getOnePost&id=' . $postId);
        return $newComment;
    }
}