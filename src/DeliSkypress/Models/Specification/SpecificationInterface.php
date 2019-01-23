<?php

namespace DeliSkypress\Models\Specification;

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 * @version 1.0.0
 * @since 1.0.0
 * 
 * @author DeliPress <thomasdeneulin@gmail.com> 
 */
interface SpecificationInterface
{
    /**
     * @version 1.0.0
     * @since 1.0.0
     *
     *
     * @return bool
     */
    public function isSatisfedBy($item);

    /**
     * @version 1.0.0
     * @since 1.0.0
     *
     * @param SpecificationInterface $spec
     */
    public function andX(SpecificationInterface $spec);

    /**
     * @version 1.0.0
     * @since 1.0.0
     *
     * @param SpecificationInterface $spec
     */
    public function orX(SpecificationInterface $spec);

    /**
     * @version 1.0.0
     * @since 1.0.0
     */
    public function notX();
}