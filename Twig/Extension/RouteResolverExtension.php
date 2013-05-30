<?php

namespace Wardell\RouteResolverBundle\Twig\Extension;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class RouteResolverExtension extends \Twig_Extension
{
	private $router;
	private $context;
	private $parameters;

    /**
     * __construct
     *
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router, SecurityContextInterface $context, $parameters)
    {
        $this->router = $router;
        $this->context = $context;
        $this->parameters = $parameters;
    }

	/**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'resolveRoute' => new \Twig_Function_Method($this, 'resolveRoute', array('is_safe_callback' => array($this, 'isUrlGenerationSafe')))
        );
    }

	/**
	 * resolveRoute
	 *
	 * @param mixed $alias
	 * @param array $routeParameters
	 */
	public function resolveRoute($name, $parameters, $relative = false)
	{
		$affix = '';
		foreach ($this->parameters as $role => $arr) {
			if ($this->context->isGranted($role)) {
				$affix = $arr['affix'];
				break;
			}
		}

		$name = sprintf('%s_%s', $name, $affix);

		return $this->router->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
	}

    /**
     * isUrlGenerationSafe
     *
     * @param \Twig_Node $argsNode
     * @return void
     */
    public function isUrlGenerationSafe(\Twig_Node $argsNode)
    {
        // support named arguments
        $paramsNode = $argsNode->hasNode('parameters') ? $argsNode->getNode('parameters') : (
            $argsNode->hasNode(1) ? $argsNode->getNode(1) : null
        );

        if (null === $paramsNode || $paramsNode instanceof \Twig_Node_Expression_Array && count($paramsNode) <= 2 &&
            (!$paramsNode->hasNode(1) || $paramsNode->getNode(1) instanceof \Twig_Node_Expression_Constant)
        ) {
            return array('html');
        }

        return array();
    }

    public function getName()
    {
        return 'wardell_twig_extension';
    }
}
