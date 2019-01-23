<?php
namespace DeliSkypress\Models\Specification;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

use DeliSkypress\Models\Specification\SpecificationInterface;
use DeliSkypress\Models\Specification\AndX;
use DeliSkypress\Models\Specification\OrX;
use DeliSkypress\Models\Specification\NotX;

/**
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @author DeliPress <thomasdeneulin@gmail.com> 
 */
abstract class AbstractSpecification implements SpecificationInterface
{
    /**
     *
     * @param $item
     *
     * @return bool
     */
    abstract public function isSatisfedBy($item);

    /**
     *
     * @param SpecificationInterface $spec
     *
     * @return SpecificationInterface
     */
    public function andX(SpecificationInterface $spec)
    {
        return new AndX($this, $spec);
    }

    /**
     *
     * @param SpecificationInterface $spec
     *
     * @return SpecificationInterface
     */
    public function orX(SpecificationInterface $spec)
    {
        return new OrX($this, $spec);
    }

    /**
     *
     * @return SpecificationInterface
     */
    public function notX()
    {
        return new NotX($this);
    }
}