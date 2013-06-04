<?php

namespace Craft;

use Mockery as m;
use PHPUnit_Framework_TestCase;

class Editor_IngredientsControllerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // unfortunately we need to stub out some methods on parent class
        $this->controller = m::mock('Craft\Editor_IngredientsController[redirectToPostedUrl,renderRequestedTemplate,returnJson]');

        // inject service dependencies
        $this->editor = m::mock('Craft\EditorService');
        $this->editor->shouldReceive('getIsInitialized')->andReturn(true);
        craft()->setComponent('editor', $this->editor);

        $this->user = m::mock('Craft\UsersService');
        $this->user->shouldReceive('getIsInitialized')->andReturn(true);
        craft()->setComponent('user', $this->user);

        $this->request = m::mock('Craft\HttpRequestService');
        $this->request->shouldReceive('getIsInitialized')->andReturn(true);
        craft()->setComponent('request', $this->request);
    }

    public function testSaveIngedient()
    {
        $this->request->shouldReceive('getRequestType')->once()
            ->andReturn('POST');

        $this->request->shouldReceive('getPost')->with('ingredientId')->once()
            ->andReturn(5);

        $mockModel = m::mock('Craft\Editor_IngredientModel');
        $this->editor->shouldReceive('getIngredientById')->with(5)->once()
            ->andReturn($mockModel);

        $attributes = array('name' => 'example');
        $this->request->shouldReceive('getPost')->with('ingredient')->once()
            ->andReturn($attributes);
        $mockModel->shouldReceive('setAttributes')->with($attributes);

        $this->editor->shouldReceive('saveIngredient')->with($mockModel)->once()
            ->andReturn(true);
        $mockModel->shouldReceive('getAttribute')->with('id')->once()
            ->andReturn(5);

        $this->user->shouldReceive('setNotice');
        $this->controller->shouldReceive('redirectToPostedUrl');

        $this->controller->actionSaveIngredient();
    }

    public function testSaveIngredientNew()
    {
        $this->request->shouldReceive('getRequestType')->once()
            ->andReturn('POST');

        $this->request->shouldReceive('getPost')->with('ingredientId')->once()
            ->andReturn(null);

        $mockModel = m::mock('Craft\Editor_IngredientModel');
        $this->editor->shouldReceive('newIngredient')->once()
            ->andReturn($mockModel);

        $attributes = array('name' => 'example');
        $this->request->shouldReceive('getPost')->with('ingredient')->once()
            ->andReturn($attributes);
        $mockModel->shouldReceive('setAttributes')->with($attributes);

        $this->editor->shouldReceive('saveIngredient')->with($mockModel)->once()
            ->andReturn(true);
        $mockModel->shouldReceive('getAttribute')->with('id')->once()
            ->andReturn(5);

        $this->user->shouldReceive('setNotice');
        $this->controller->shouldReceive('redirectToPostedUrl');

        $this->controller->actionSaveIngredient();
    }

    public function testSaveIngedientError()
    {
        $this->request->shouldReceive('getRequestType')->once()
            ->andReturn('POST');

        $this->request->shouldReceive('getPost')->with('ingredientId')->once()
            ->andReturn(5);

        $mockModel = m::mock('Craft\Editor_IngredientModel');
        $this->editor->shouldReceive('getIngredientById')->with(5)->once()
            ->andReturn($mockModel);

        $attributes = array('name' => 'example');
        $this->request->shouldReceive('getPost')->with('ingredient')->once()
            ->andReturn($attributes);
        $mockModel->shouldReceive('setAttributes')->with($attributes);

        $this->editor->shouldReceive('saveIngredient')->with($mockModel)->once()
            ->andReturn(false);

        $this->user->shouldReceive('setError');
        $this->controller->shouldReceive('renderRequestedTemplate')
            ->with(array('ingredient' => $mockModel));

        $this->controller->actionSaveIngredient();
    }

    public function testDeleteIngredient()
    {
        $this->request->shouldReceive('getRequestType')->once()
            ->andReturn('POST');
        $this->request->shouldReceive('isAjaxRequest')->once()
            ->andReturn(true);

        $this->request->shouldReceive('getRequiredPost')->with('id')->once()
            ->andReturn(5);
        $this->editor->shouldReceive('deleteIngredientById')->with(5)->once();

        $this->controller->shouldReceive('returnJson')->with(array('success' => true))->once();

        $this->controller->actionDeleteIngredient();
    }
}
