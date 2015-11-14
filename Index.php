<?php
    require_once 'functions.php';

//Load more adventures when requested
if (isset($_POST['timesRequested'])) {
    $timesRequested = $_POST['timesRequested'];

    $oConn = LoginToDB();

    //Prepare statement, substitute :username with username field input
    $query = $oConn->prepare('SELECT PostID, Username, Title, Upvotes, DatePosted FROM Adventures ORDER BY DatePosted DESC LIMIT :limit, 8');
    $query->bindValue(':limit', $timesRequested * 8, PDO::PARAM_INT);
    $query->execute();
    $rows = $query->fetchAll(PDO::FETCH_ASSOC); //grab all values that match

    echo json_encode($rows);

    //Add a like to an adventure
} else if (isset($_POST['liked'])) {
    echo addLike($_POST['liked']);
} else { //Load index.html

    //Create connection to database, query for username and verify password
    try {
        $oConn = LoginToDB();

        //Prepare statement, substitute :username with username field input
        $query = $oConn->prepare('SELECT PostID, Username, Title, Upvotes, DatePosted FROM Adventures ORDER BY DatePosted DESC LIMIT 8'); // Grab the 8 most recent Adventures
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC); //grab all values that match

        $adventures = $rows;


        //Check for logged in
        $loggedIn = logged_in();

        //Templating
        require_once 'vendor/autoload.php';

        $loader = new Twig_Loader_Filesystem('views');
        $twig = new Twig_environment($loader);
        $template = $twig->loadTemplate('index.html');

        echo $template->render(array(
            'adventures' => $adventures,
            'loggedIn' => $loggedIn['loggedIn'],
            'name' => $loggedIn['first_name'],
            'permissions' => $loggedIn['user_group']
        ));

    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
}