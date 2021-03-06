<?php
declare(strict_types=1);

namespace Lcobucci\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ParameterBagTest extends TestCase
{
    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Compiler\ParameterBag::__construct
     * @covers \Lcobucci\DependencyInjection\Compiler\ParameterBag::set
     */
    public function setShouldConfigureAParameter(): void
    {
        $pass = new ParameterBag();
        $pass->set('test', 1);

        self::assertEquals(new ParameterBag(['test' => 1]), $pass);
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Compiler\ParameterBag::__construct
     * @covers \Lcobucci\DependencyInjection\Compiler\ParameterBag::get
     */
    public function getShouldReturnTheValueOfTheParameter(): void
    {
        $pass = new ParameterBag(['test' => 1]);

        self::assertEquals(1, $pass->get('test'));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Compiler\ParameterBag::get
     *
     * @uses \Lcobucci\DependencyInjection\Compiler\ParameterBag::__construct
     */
    public function getShouldReturnTheDefaultValueWhenParameterDoesNotExist(): void
    {
        $pass = new ParameterBag();

        self::assertEquals(1, $pass->get('test', 1));
    }

    /**
     * @test
     *
     * @covers \Lcobucci\DependencyInjection\Compiler\ParameterBag::process
     *
     * @uses \Lcobucci\DependencyInjection\Compiler\ParameterBag::__construct
     */
    public function invokeShouldAppendAllConfiguredParametersOnTheBuilder(): void
    {
        $builder = new ContainerBuilder();
        $pass    = new ParameterBag(['test' => 1]);

        $pass->process($builder);
        self::assertEquals(1, $builder->getParameter('test'));
    }
}
