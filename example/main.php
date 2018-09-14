<?php
$mysqlHost = $argv[1];
$mysqlPort = $argv[2];
$keyspace = $argv[3];
$mysqlUser = 'mysql_user';
$mysqlPassword = 'mysql_password';

$pdo = null;

function loan($fn, ...$args)
{
    global $mysqlHost, $mysqlPort, $keyspace, $mysqlUser, $mysqlPassword;
    try {
        if ($pdo == null) {
            print "Connecting to vtgate: {$mysqlHost}:{$mysqlPort}\n";
            $pdo = new PDO ("mysql:host={$mysqlHost};port={$mysqlPort};dbname={$keyspace};charset=utf8", $mysqlUser, $mysqlPassword );
        }
        $fn($pdo, ...$args);
    } catch (PDOException $e) {
        print "Error! PDO Exception:" . $e->getMessage();
        die();
    }
    return;
}

function listAll()
{
    loan(function($db) {
        foreach($db->query('SELECT * from messages') as $row) {
            print_r($row);
        }
    });
}

function newMessage($message)
{
    loan(function($db) use ($message) {
        $page = (string) rand();
        list($usec, $sec) = explode(" ", microtime());
        $timestamp = (string) (int) ($sec * 1000 + $usec);
        $sql = $db->prepare ( 'insert into messages values(?, ?, ?)' );
        if (!$sql->execute (array (
                $page,
                $timestamp,
                $message
        ))) {
            print 'Failed to insert:';
            print_r($sql->errorInfo());
        }
    });
}

newMessage("test");
listAll();

$pdo = null;

?>
