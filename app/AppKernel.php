<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Sentry\SentryBundle\SentryBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            //Own bundles
            new AppBundle\AppBundle(),
            new UserBundle\UserBundle(),
            new CostoBundle\CostoBundle(),

            //Third party libraries

            new FOS\UserBundle\FOSUserBundle(),
            new PUGX\MultiUserBundle\PUGXMultiUserBundle(),
            new SC\DatetimepickerBundle\SCDatetimepickerBundle(),
            new JavierEguiluz\Bundle\EasyAdminBundle\EasyAdminBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new cspoo\Swiftmailer\MailgunBundle\cspooSwiftmailerMailgunBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),               //upload engine
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Hackzilla\Bundle\TicketBundle\HackzillaTicketBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Troopers\AlertifyBundle\TroopersAlertifyBundle(),
            new Sg\DatatablesBundle\SgDatatablesBundle(),

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Hautelook\AliceBundle\HautelookAliceBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
