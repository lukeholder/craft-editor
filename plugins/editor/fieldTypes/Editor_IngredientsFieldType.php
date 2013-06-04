<?php

namespace Craft;

/**
 * Ingredients Fieldtype
 *
 * Allows entries to select associated ingredients
 */
class Editor_IngredientsFieldType extends BaseFieldType
{
    /**
     * Get the name of this fieldtype
     */
    public function getName()
    {
        return Craft::t('Editor Ingredients');
    }



    /**
     * Get this fieldtype's column type.
     *
     * @return mixed
     */
    public function defineContentAttribute()
    {
        // "Mixed" represents a "text" column type, which can be used to store arrays etc.
        return AttributeType::Mixed;
    }

    /**
     * Get this fieldtype's form HTML
     *
     * @param  string $name
     * @param  mixed  $value
     * @return string
     */
    public function getInputHtml($name, $value)
    {
        // call our service layer to get a current list of ingredients
        $ingredients = craft()->editor->getAllIngredients();

        return craft()->templates->render('editor/_fieldtypes/ingredients', array(
            'name'      => $name,
            'options'   => $ingredients,
            'values'    => $value,
        ));
    }
}
