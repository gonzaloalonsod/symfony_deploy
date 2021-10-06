<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

class DashboardController extends AbstractDashboardController
{
    public static function getSubscribedServices(): array
    {
        return array_merge(parent::getSubscribedServices(), [
            ProjectRepository::class => ProjectRepository::class
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $projectsDeployer = $this->get(ProjectRepository::class)->findByDeploymentTool(1);
        $projectsCapistrano = $this->get(ProjectRepository::class)->findByDeploymentTool(2);

        return $this->render('admin/dashboard/index.html.twig', [
            'projectsDeployer' => $projectsDeployer,
            'projectsCapistrano' => $projectsCapistrano,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Deploy');
    }

    public function configureCrud(): Crud
    {
        return Crud::new();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('User', 'fas fa-folder-open', User::class);
        yield MenuItem::linkToCrud('Project', 'fas fa-folder-open', Project::class);
    }

    /**
     * @Route("/admin/deployer/deploy/{project}", name="admin_deployer_deploy")
     */
    public function deployerDeployAction(
        KernelInterface $kernel,
        Project $project = null
    ): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        if ($project) {
            $input = new ArrayInput([
                'command' => 'deployer:deploy',
                'depDirectory' => $project->getDepDirectory(),
                'name' => $project->getName(),
                'script' => $project->getScript(),
            ]);
        } else {
            $input = new ArrayInput([
                'command' => 'deployer:deploy',
                // (optional) define the value of command arguments
                // 'fooArgument' => 'barValue',
                // (optional) pass options to the command
                // '--message-limit' => $messages,
            ]);
        }

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true // true for decorated
        );
        $application->run($input, $output);

        // return the output
        $converter = new AnsiToHtmlConverter();
        $content = $output->fetch();

        return $this->render('deployer/deploy.html.twig', [
            'content' => $converter->convert($content)
        ]);
    }

    /**
     * @Route("/admin/capistrano/deploy", name="admin_capistrano_deploy")
     */
    public function capistranoDeployAction(KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'capistrano:deploy',
            // (optional) define the value of command arguments
            // 'fooArgument' => 'barValue',
            // (optional) pass options to the command
            // '--message-limit' => $messages,
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput(
            OutputInterface::VERBOSITY_NORMAL,
            true // true for decorated
        );
        $application->run($input, $output);

        // return the output
        $converter = new AnsiToHtmlConverter();
        $content = $output->fetch();

        return $this->render('capistrano/deploy.html.twig', [
            'content' => $converter->convert($content)
        ]);
    }
}
