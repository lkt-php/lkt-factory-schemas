<?php

namespace Lkt\Factory\Schemas\Tests;

use Lkt\Factory\Schemas\Exceptions\InvalidComponentException;
use Lkt\Factory\Schemas\Exceptions\InvalidTableException;
use Lkt\Factory\Schemas\Fields\IdField;
use Lkt\Factory\Schemas\Fields\StringField;
use Lkt\Factory\Schemas\Schema;
use PHPUnit\Framework\TestCase;

class SchemaStackTest extends TestCase
{
    /**
     * @return void
     */
    public function testAddSchemaToStack()
    {
        $this->assertEquals(0, Schema::getCount());

        Schema::add(
            Schema::table('users', 'users')
                ->addField(IdField::define('id'))
                ->addField(StringField::define('name'))
        );
        $this->assertEquals(1, Schema::getCount());

        Schema::add(Schema::pivotTable('users1', 'users1'));
        $this->assertEquals(2, Schema::getCount());
    }

    /**
     * @return void
     */
    public function testInvalidSchema1()
    {
        $this->expectException(InvalidTableException::class);
        Schema::add(Schema::table('', 'component'));
    }

    /**
     * @return void
     */
    public function testInvalidSchema2()
    {
        $this->expectException(InvalidComponentException::class);
        Schema::add(Schema::table('table', ''));
    }

    /**
     * @return void
     */
    public function testLoadSchema()
    {
        /** @var Schema $schema */
        $schema = Schema::get('users');

        $this->assertEquals(['id'], $schema->getIdColumn());
        $this->assertEquals(['id', 'name'], array_keys($schema->getAllFields()));
    }
}