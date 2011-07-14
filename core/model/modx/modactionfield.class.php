<?php
/**
 * @package modx
 */
/**
 * An abstract representation of MODX manager fields; used for Form Customization
 *
 * @property int $action The modAction this field occurs on
 * @property string $name The name or ID of the field/tab
 * @property string $type Either field or tab, which classifies what type of object this record is
 * @property string $tab Specifies which tab the field is on
 * @property string $form The form DOM ID
 * @property string $other Used on tabs to delineate TV tabs
 * @property int $rank The order in which this field occurs
 *
 * @see modActionDom
 * @see modFormCustomizationSet
 * @package modx
 */
class modActionField extends xPDOSimpleObject {
}