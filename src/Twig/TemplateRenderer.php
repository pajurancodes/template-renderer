<?php

namespace PajuranCodes\Template\Renderer\Twig;

use PajuranCodes\Template\Renderer\{
    TemplateRendererInterface,
    ContextCollectionInterface,
    TemplateRenderer as BaseTemplateRenderer,
};
use Twig\Environment as TwigEnvironment;

/**
 * @author pajurancodes
 */
class TemplateRenderer extends BaseTemplateRenderer implements TemplateRendererInterface {

    /**
     *
     * @param TwigEnvironment $twigEnvironment A Twig environment.
     * @param ContextCollectionInterface $contextCollection A collection of values passed to 
     * template files as context parameters.
     */
    public function __construct(
        private readonly TwigEnvironment $twigEnvironment,
        ContextCollectionInterface $contextCollection
    ) {
        parent::__construct($contextCollection);
    }

    /**
     * @inheritDoc
     */
    public function render(string $templateName, array $context = []): string {
        $builtTemplateName = $this->buildTemplateName($templateName);

        $this->saveContextValuesToContextCollection($context);

        return $this->twigEnvironment->render(
                $builtTemplateName,
                $this->contextCollection->all()
        );
    }

}
