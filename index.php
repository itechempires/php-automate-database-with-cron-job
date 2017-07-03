<?php

require __DIR__ . '/vendor/autoload.php';

use Spatie\DbDumper\Databases\MySql;


class DatabaseBackup
{

    protected $host, $username, $password, $database, $email;

    function __construct($host, $username, $password, $database, $email)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->email = $email;

        $this->initMySQLDBBackup();
        $this->sendEmail();
    }

    public function initMySQLDBBackup()
    {
        $file_name = $this->database . '_' . date('Y_m_d', time()) . '.sql';

        MySql::create()
            ->setDbName($this->database)
            ->setUserName($this->username)
            ->setPassword($this->password)
            ->dumpToFile($file_name);
    }

    public function sendEmail()
    {
        $transport = (new Swift_SmtpTransport('smtp.example.org', 25))
            ->setUsername('your username')
            ->setPassword('your password');

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message())
            ->setSubject('Database Backup Notification')
            ->setFrom(['support@yourdomain.com' => 'Support'])
            ->setTo([$this->email])
            ->setBody('Database Backup executed successfully!');;


        return $mailer->send($message);
    }
}

$host = 'localhost';
$username = 'USERNAME';
$password = 'PASSWORD';
$database = 'DATABASE_NAME';
$email = 'YOUR_EMAIL_ADDRESS';

(new DatabaseBackup($host, $username, $password, $database, $email));