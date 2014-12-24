<?php
namespace Yireo\Magento\Command\Admin\Role;

use Yireo\Magento\Command\Admin\Role\AbstractAdminRoleCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CreateCommand
 */
class CreateCommand extends AbstractAdminRoleCommand
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('admin:role:create')
            ->addArgument('rolename', InputArgument::OPTIONAL, 'Role name')
            ->setDescription('Create admin role with all privileges.')
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
            if (($rolename = $input->getArgument('rolename')) == null) {
                $rolename = $dialog->ask($output, '<question>Role name:</question>');
            }

            // Check for existing role name
            $roleFound = $this->getRoleModel()->load($rolename, 'role_name');
            if ($roleFound->getId() > 0) {
                $output->writeln('<error>Role name is already in use</error>');
                return;
            }

            try {
                $role = $this->getRoleModel();
                $role->setName($rolename)
                    ->setRoleType('G')
                    ->save();
                    
                // Give "all" privileges to role
                $resourceAll = ($this->_magentoMajorVersion == self::MAGENTO_MAJOR_VERSION_2) ?
                    \Mage_Backend_Model_Acl_Config::ACL_RESOURCE_ALL : 'all';
                $this->getRulesModel()
                    ->setRoleId($role->getId())
                    ->setResources(array($resourceAll))
                    ->saveRel();

                $output->writeln('<info>Role was successfully created</info>');
            } catch (\Exception $e) {
                    $output->writeln('<error>' . $e->getMessage() . '</error>');
            }
        }
    }
}
