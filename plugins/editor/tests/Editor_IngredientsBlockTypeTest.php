<?php

namespace Craft;

use Mockery as m;
use PHPUnit_Framework_TestCase;

class Editor_IngredientsFieldTypeTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->fieldtype = new Editor_IngredientsFieldType();

        // inject service dependencies
        $this->editor = m::mock('Craft\EditorService');
        $this->editor->shouldReceive('getIsInitialized')->andReturn(true);
        craft()->setComponent('editor', $this->editor);

        $this->templates = m::mock('Craft\TemplatesService');
        $this->templates->shouldReceive('getIsInitialized')->andReturn(true);
        craft()->setComponent('templates', $this->templates);
    }

    public function testGetName()
    {
        $result = $this->fieldtype->getName();

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }

    public function testGetInputHtml()
    {
        $this->editor->shouldReceive('getAllIngredients')->once()->andReturn(array());

        $this->templates->shouldReceive('render')->once();
    }
}
