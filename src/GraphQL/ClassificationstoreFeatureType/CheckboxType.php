<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\DataHubBundle\GraphQL\ClassificationstoreFeatureType;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Pimcore\Bundle\DataHubBundle\GraphQL\FeatureDescriptor;
use Pimcore\Bundle\DataHubBundle\GraphQL\TypeInterface\CsFeature;

class CheckboxType extends ObjectType
{
    protected static $instance;

    /**
     * @return CheckboxType
     */
    public static function getInstance()
    {

        if (!isset(self::$instance)) {
            $fields = Helper::getCommonFields();
            $fields['checked'] = [
                'type' => Type::boolean(),
                'resolve' => static function ($value = null, $args = [], $context = [], ResolveInfo $resolveInfo = null) {
                    if ($value instanceof FeatureDescriptor) {
                        return $value->getValue();
                    }
                }
            ];

            $config =
                [
                    'name' => "csFeatureCheckbox",
                    'interfaces' => [CsFeature::getInstance()],
                    'fields' => $fields

                ];
            self::$instance = new static($config);
        }

        return self::$instance;
    }


}
