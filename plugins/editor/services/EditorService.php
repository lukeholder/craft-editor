<?php

namespace Craft;

/**
 * Editor Service
 *
 * Provides a consistent API for our plugin to access the database
 */
class EditorService extends BaseApplicationComponent
{
    protected $ingredientRecord;

    /**
     * Create a new instance of the Editor Recpies Service.
     * Constructor allows IngredientRecord dependency to be injected to assist with unit testing.
     *
     * @param @ingredientRecord IngredientRecord The ingredient record to access the database
     */
    public function __construct($ingredientRecord = null)
    {
        $this->ingredientRecord = $ingredientRecord;
        if (is_null($this->ingredientRecord)) {
            $this->ingredientRecord = Editor_IngredientRecord::model();
        }
    }

    /**
     * Get a new blank ingredient
     *
     * @param  array                           $attributes
     * @return Editor_IngredientModel
     */
    public function newIngredient($attributes = array())
    {
        $model = new Editor_IngredientModel();
        $model->setAttributes($attributes);

        return $model;
    }

    /**
     * Get all ingredients from the database.
     *
     * @return array
     */
    public function getAllIngredients()
    {
        $records = $this->ingredientRecord->findAll(array('order'=>'t.name'));

        return Editor_IngredientModel::populateModels($records, 'id');
    }

    /**
     * Get a specific ingredient from the database based on ID. If no ingredient exists, null is returned.
     *
     * @param  int   $id
     * @return mixed
     */
    public function getIngredientById($id)
    {
        if ($record = $this->ingredientRecord->findByPk($id)) {
            return Editor_IngredientModel::populateModel($record);
        }
    }

    /**
     * Save a new or existing ingredient back to the database.
     *
     * @param  Editor_IngredientModel $model
     * @return bool
     */
    public function saveIngredient(Editor_IngredientModel &$model)
    {
        if ($id = $model->getAttribute('id')) {
            if (null === ($record = $this->ingredientRecord->findByPk($id))) {
                throw new Exception(Craft::t('Can\'t find ingredient with ID "{id}"', array('id' => $id)));
            }
        } else {
            $record = $this->ingredientRecord->create();
        }

        $record->setAttributes($model->getAttributes());
        if ($record->save()) {
            // update id on model (for new records)
            $model->setAttribute('id', $record->getAttribute('id'));

            return true;
        } else {
            $model->addErrors($record->getErrors());

            return false;
        }
    }

    /**
     * Delete an ingredient from the database.
     *
     * @param  int $id
     * @return int The number of rows affected
     */
    public function deleteIngredientById($id)
    {
        return $this->ingredientRecord->deleteByPk($id);
    }
}
