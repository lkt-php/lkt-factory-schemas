<?php

namespace Lkt\Factory\Schemas\Tests;

use Lkt\Factory\Schemas\Exceptions\InvalidFieldNameException;
use Lkt\Factory\Schemas\Fields\BooleanField;
use Lkt\Factory\Schemas\Fields\ColorField;
use Lkt\Factory\Schemas\Fields\EmailField;
use Lkt\Factory\Schemas\Fields\HTMLField;
use Lkt\Factory\Schemas\Fields\IntegerField;
use Lkt\Factory\Schemas\Fields\JSONField;
use Lkt\Factory\Schemas\Fields\StringField;
use Lkt\Factory\Schemas\Schema;
use PHPUnit\Framework\TestCase;

class FieldsTest extends TestCase
{
    /**
     * @return void
     * @throws InvalidFieldNameException
     */
    public function testCreateFieldsObjects()
    {
        $schema = Schema::table('users_fields', 'users-fields')
            ->addField(StringField::define('name'))
            ->addField(EmailField::define('email'))
            ->addField(HTMLField::define('html'))
            ->addField(BooleanField::define('isActive', 'is_active'))
            ->addField(IntegerField::define('id'))
            ->addField(ColorField::define('favouriteColor', 'favourite_color'))
            ->addField(JSONField::define('data')->setIsAssoc()->setIsCompressed())
        ;

        $keys = array_keys($schema->getAllFields());
        sort($keys);

        $expected = ['id', 'name', 'data', 'email', 'html', 'isActive', 'favouriteColor'];
        sort($expected);

        $this->assertEquals($expected, $keys);
    }
    /**
     * @return void
     */
    public function testCreateInvalidFields()
    {
        $this->expectException(InvalidFieldNameException::class);
        Schema::table('users_fields', 'users-fields')
            ->addField(new StringField(''))
            ->addField(new IntegerField('id'))
        ;
    }
}