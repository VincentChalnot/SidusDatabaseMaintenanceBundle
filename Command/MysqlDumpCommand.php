<?php
/*
 * This file is part of the Sidus/DatabaseMaintenanceBundle package.
 *
 * Copyright (c) 2021 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sidus\DatabaseMaintenanceBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Dump the database using mysqldump
 */
class MysqlDumpCommand extends Command
{
    public function __construct(
        protected ManagerRegistry $doctrine,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('sidus:database:mysqldump')
            ->addOption('connection', 'c', InputOption::VALUE_OPTIONAL, 'The name of the doctrine connection')
            ->addArgument('path', InputArgument::OPTIONAL, 'The path of the file to dump the database')
            ->setDescription('Command alias to mysqldump with the proper parameters');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Connection $connection */
        $connection = $this->doctrine->getConnection($input->getOption('connection'));
        if ('mysql' !== $connection->getDatabasePlatform()->getName()) {
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
