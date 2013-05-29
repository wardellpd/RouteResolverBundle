<?php

namespace Application\UserBundle\Twig\Extension;

class CustomExtension extends \Twig_Extension
{

    //protected $environment;

	/**
	 * @param Twig_Environment $environment
	 */
    //public function __construct (\Twig_Environment $environment)
    //{
        //$this->environment = $environment;
    //}

	/**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        //return array(
            //new \Twig_SimpleFilter('extension', array($this, 'getFileExtension')),
            //new \Twig_SimpleFilter('mimetypeName', array($this, 'getMimetypeName')),
        //);
    }

	/**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'resolveRoute' => new \Twig_Function_Method($this, 'resolveRoute')
        );
    }

	public function resolveRoute($alias, $routeParameters = array())
	{
		ld($routeParameters); exit;
	}

    public function getName()
    {
        return 'user_twig_extension';
    }
}
