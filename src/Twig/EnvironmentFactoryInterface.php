<?php

namespace PajuranCodes\Template\Renderer\Twig;

use Twig\Environment as TwigEnvironment;

/**
 * An interface to a Twig environment factory.
 *
 * @author pajurancodes
 */
interface EnvironmentFactoryInterface {

    /**
     * Create a Twig environment.
     *
     * @return TwigEnvironment The Twig environment.
     */
    public function createEnvironment(): TwigEnvironment;
}
