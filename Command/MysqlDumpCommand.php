<?php

namespace Sidus\DatabaseMaintenanceBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MysqlDumpCommand extends ContainerAwareCommand
{
    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('sidus:database:mysqldump')
            ->addOption('connection', 'c', InputOption::VALUE_OPTIONAL, 'The name of the doctrine connection')
            ->addArgument('path', InputArgument::OPTIONAL, 'The path of the file to dump the database')
            ->setDescription('Command alias to mysqldump with the proper parameters');
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

        $path = '';
        if ($input->getArgument('path')) {
            $path = " > '".escapeshellarg($input->getArgument('path'))."'";
        }
        $host = escapeshellarg($connection->getHost());
        $port = escapeshellarg($connection->getPort());
        $username = escapeshellarg($connection->getUsername());
        $password = escapeshellarg($connection->getPassword());
        $database = escapeshellarg($connection->getDatabase());

        $cmd = "mysqldump -h {$host} -P {$port} -u {$username} -p{$password} {$database}{$path}";
        passthru($cmd, $return);

        return $return;
    }
}
