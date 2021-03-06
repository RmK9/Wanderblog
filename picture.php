<?php
/**
 * Created by PhpStorm.
 * User: Sean
 * Date: 12/8/2015
 * Time: 4:05 PM
 */
require_once 'functions.php'; //Grabs any extra functions

try {
    $postID = $_POST['adventureId'];

    if ($postID) {

        $oConn = loginToDB();

        $pictures = array();

        //Prepare statement, substitute :username with username field input
        $query = $oConn->prepare('SELECT Pictures.Path FROM Pictures WHERE Pictures.PostID = :PostID'); //Query for PostID and any comments it may have
        $query->bindValue(':PostID', $postID, PDO::PARAM_STR);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            echo json_encode($rows);

        } else {
            echo json_encode(array('error' => 'no pictures found'));
        }


    }else{
        echo 'Adventure not found';
    }
}catch (PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
} finally{
    $oConn = null;
}