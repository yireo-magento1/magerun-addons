<?php
namespace Yireo\Magento\Command\Admin\Role;

use Yireo\Magento\Command\Admin\Role\AbstractAdminRoleCommand;
use N98\Util\Console\Helper\Table\Renderer\RendererFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ListCommand
 */
class ListCommand extends AbstractAdminRoleCommand
{
    protected function configure()
    {
      $this
          ->setName('admin:role:list')
          ->setDescription('List admin roles.')
          ->addOption(
                'format',
                null,
                InputOption::VALUE_OPTIONAL,
                'Output Format. One of [' . implode(',', RendererFactory::getFormats()) . ']'
            )
      ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectMagento($output, true);
        if ($this->initMagento()) {
            $roleList = $this->getRoleModel()->getCollection();
            $table = array();

            foreach ($roleList as $role) {
                $table[] = array(
                    $role->getId(),
                    $role->getRoleName(),
                );
            }

            $this->getHelper('table')
                ->setHeaders(array('id', 'role'))
                ->renderByFormat($output, $table, $input->getOption('format'));
        }
    }
}
