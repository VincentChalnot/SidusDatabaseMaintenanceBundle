<?php

namespace Sidus\DatabaseMaintenanceBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MysqlCommand extends ContainerAwareCommand
{
    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('sidus:database:mysql')
            ->addOption('connection', 'c', InputOption::VALUE_OPTIONAL, 'The name of the doctrine connection')
            ->setDescription('Command alias to mysql client with the proper parameters');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Connection $connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection($input->getOption('connection'));
        if ($connection->getDatabasePlatform()->getName() !== 'mysql') {
            throw new \LogicException('Only MySQL database is supported');
        }

        $host = escapeshellarg($connection->getHost());
        $port = escapeshellarg($connection->getPort());
        $username = escapeshellarg($connection->getUsername());
        $password = escapeshellarg($connection->getPassword());
        $database = escapeshellarg($connection->getDatabase());

        $cmd = "mysql -h {$host} -P {$port} -u {$username} -p{$password} {$database}";
        passthru($cmd, $return);

        return $return;
    }
}
