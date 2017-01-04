<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection;

use Lcobucci\DependencyInjection\Compiler\ParameterBag;
use Lcobucci\DependencyInjection\Config\ContainerConfiguration;
use Lcobucci\DependencyInjection\Generators\Xml as XmlGenerator;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
final class ContainerBuilder implements Builder
{
    /**
     * @var ContainerConfiguration
     */
    private $config;

    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var ParameterBag
     */
    private $parameterBag;

    /**
     * @param ContainerConfiguration|null $config
     * @param Generator|null $generator
     * @param ParameterBag|null $parameterBag
     */
    public function __construct(
        ContainerConfiguration $config = null,
        Generator $generator = null,
        ParameterBag $parameterBag = null
    ) {
        $this->parameterBag = $parameterBag ?: new ParameterBag();
        $this->generator = $generator ?: new XmlGenerator();
        $this->config = $config ?: new ContainerConfiguration();

        $this->setDefaultConfiguration();
    }

    /**
     * Configures the default parameters and appends the handler
     */
    protected function setDefaultConfiguration()
    {
        $this->parameterBag->set('app.devmode', false);

        $this->config->addPass($this->parameterBag);
    }

    /**
     * {@inheritdoc}
     */
    public function setGenerator(Generator $generator): Builder
    {
        $this->generator = $generator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFile(string $file): Builder
    {
        $this->config->addFile($file);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPass(
        CompilerPassInterface $pass,
        string $type = PassConfig::TYPE_BEFORE_OPTIMIZATION
    ): Builder {
        $this->config->addPass($pass, $type);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function useDevelopmentMode(): Builder
    {
        $this->parameterBag->set('app.devmode', true);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDumpDir(string $dir): Builder
    {
        $this->config->setDumpDir($dir);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter(string $name, $value): Builder
    {
        $this->parameterBag->set($name, $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addPath(string $path): Builder
    {
        $this->config->addPath($path);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseClass(string $class): Builder
    {
        $this->config->setBaseClass($class);

        return $this;
    }

    protected function createDumpCache(): ConfigCache
    {
        return new ConfigCache(
            $this->config->getDumpFile(),
            $this->parameterBag->get('app.devmode')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getContainer(): ContainerInterface
    {
        return $this->generator->generate(
            $this->config,
            $this->createDumpCache()
        );
    }
}
