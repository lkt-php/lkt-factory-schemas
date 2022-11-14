<?php

namespace Lkt\Factory\Schemas\Tests;

use Lkt\Factory\Schemas\Exceptions\InvalidComponentException;
use Lkt\Factory\Schemas\Exceptions\InvalidFieldNameException;
use Lkt\Factory\Schemas\Exceptions\InvalidSchemaAppClassException;
use Lkt\Factory\Schemas\Exceptions\InvalidSchemaClassNameForGeneratedClassException;
use Lkt\Factory\Schemas\Exceptions\InvalidSchemaNamespaceForGeneratedClassException;
use Lkt\Factory\Schemas\Fields\BooleanField;
use Lkt\Factory\Schemas\Fields\ColorField;
use Lkt\Factory\Schemas\Fields\EmailField;
use Lkt\Factory\Schemas\Fields\HTMLField;
use Lkt\Factory\Schemas\Fields\IntegerField;
use Lkt\Factory\Schemas\Fields\JSONField;
use Lkt\Factory\Schemas\Fields\StringField;
use Lkt\Factory\Schemas\InstanceSettings;
use Lkt\Factory\Schemas\Schema;
use Lkt\Factory\Schemas\Tests\Assets\TestClass;
use PHPUnit\Framework\TestCase;

class SchemaInstanceTest extends TestCase
{
    /**
     * @return void
     * @throws InvalidFieldNameException
     * @throws InvalidSchemaAppClassException
     * @throws InvalidSchemaNamespaceForGeneratedClassException
     * @throws InvalidSchemaClassNameForGeneratedClassException
     * @throws InvalidComponentException
     */
    public function testCreateInstanceConfig()
    {
        $schema = Schema::table('users_instance', 'users-instance')
            ->setInstanceSettings(
                InstanceSettings::define(TestClass::class)
                ->setClassNameForGeneratedClass('GeneratedUser')
                ->setNamespaceForGeneratedClass('Lkt\\Factory\Schemas\\Tests\\Generated')
                ->setWhereStoreGeneratedClass(__DIR__ . '/Assets/Generated')
                ->setBaseComponent('test-component')
            )
            ->addField(StringField::define('name'))
            ->addField(EmailField::define('email'))
            ->addField(HTMLField::define('html'))
            ->addField(BooleanField::define('isActive', 'is_active'))
            ->addField(IntegerField::define('id'))
            ->addField(ColorField::define('favouriteColor', 'favourite_color'))
            ->addField(JSONField::define('data')->setIsAssoc()->setIsCompressed())
        ;

        $this->assertEquals(TestClass::class, $schema->getInstanceSettings()->getAppClass());
        $this->assertEquals('GeneratedUser', $schema->getInstanceSettings()->getClassNameForGeneratedClass());
        $this->assertEquals('Lkt\\Factory\\Schemas\\Tests\\Generated', $schema->getInstanceSettings()->getNamespaceForGeneratedClass());
        $this->assertEquals(__DIR__ . '/Assets/Generated', $schema->getInstanceSettings()->getWhereStoreGeneratedClass());
        $this->assertEquals('', $schema->getInstanceSettings()->getClassToBeExtended());
        $this->assertEquals('test-component', $schema->getInstanceSettings()->getBaseComponent());

        $this->assertEquals(__DIR__ . '/Assets/Generated/GeneratedUser.php', $schema->getInstanceSettings()->getGeneratedClassFullPath());
    }

}