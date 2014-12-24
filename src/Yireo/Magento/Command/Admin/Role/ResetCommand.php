<?php
namespace Yireo\Magento\Command\Admin\Role;

use Yireo\Magento\Command\Admin\Role\AbstractAdminRoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ResetCommand
 */
class ResetCommand extends AbstractAdminRoleCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('admin:role:reset')
            ->addArgument('id', InputArgument::OPTIONAL, 'Role name of ID')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force')
            ->setDescription('Reset admin role to include all privileges again.')
        ;
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectMagento($output);
        if ($this->initMagento()) {
            $dialog = $this->getHelperSet()->get('dialog');

            // Role name
            if (($id = $input->getArgument('id')) == null) {
                $id = $dialog->ask($output, '<question>Role ID or name:</question>');
            }

            if (is_numeric($id)) {
                $role = $this->getRoleModel()->load($id);
            } else {
                $role = $this->getRoleModel()->load($id, 'role_name');
            }

            if (!$role->getId()) {
                $output->writeln('<error>Role was not found</error>');
                return;
            }

            $shouldReset = $input->getOption('force');
            if (!$shouldReset) {
                $shouldReset = $dialog->askConfirmation($output, '<question>Are you sure?</question> <comment>[n]</comment>: ', false);
            }

            if ($shouldReset) {
                try {
                    $resourceAll = ($this->_magentoMajorVersion == self::MAGENTO_MAJOR_VERSION_2) ?
                        \Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL : 'all';
                    $this->getRulesModel()
                        ->setRoleId($role->getId())
                        ->setResources(array($resourceAll))
                        ->saveRel();
                    $output->writeln('<info>Role was successfully resetted</info>');
                } catch (\Exception $e) {
                    $output->writeln('<error>' . $e->getMessage() . '</error>');
                }
            } else {
                $output->writeln('<error>Aborting reset</error>');
            }
        }
    }
}
