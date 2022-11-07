<?php

namespace Lkt\Factory\Schemas;

use Lkt\ColumnTypes\Enums\ColumnType;
use Lkt\Factory\Schemas\Exceptions\InvalidComponentException;
use Lkt\Factory\Schemas\Exceptions\InvalidTableException;
use Lkt\Factory\Schemas\Fields\AbstractField;
use Lkt\Factory\Schemas\Fields\BooleanField;
use Lkt\Factory\Schemas\Fields\ColorField;
use Lkt\Factory\Schemas\Fields\DateTimeField;
use Lkt\Factory\Schemas\Fields\EmailField;
use Lkt\Factory\Schemas\Fields\FileField;
use Lkt\Factory\Schemas\Fields\FloatField;
use Lkt\Factory\Schemas\Fields\ForeignKeyField;
use Lkt\Factory\Schemas\Fields\ForeignKeysField;
use Lkt\Factory\Schemas\Fields\HTMLField;
use Lkt\Factory\Schemas\Fields\ImageField;
use Lkt\Factory\Schemas\Fields\IntegerField;
use Lkt\Factory\Schemas\Fields\JSONField;
use Lkt\Factory\Schemas\Fields\PivotField;
use Lkt\Factory\Schemas\Fields\PivotLeftIdField;
use Lkt\Factory\Schemas\Fields\PivotPositionField;
use Lkt\Factory\Schemas\Fields\PivotRightIdField;
use Lkt\Factory\Schemas\Fields\RelatedField;
use Lkt\Factory\Schemas\Fields\RelatedKeysField;
use Lkt\Factory\Schemas\Fields\StringField;
use Lkt\Factory\Schemas\Fields\UnixTimeStampField;
use Lkt\Factory\Schemas\Values\ComponentValue;
use Lkt\Factory\Schemas\Values\TableValue;

final class Schema
{
    private static $stack = [];

    /**
     * @return array
     */
    public static function getStack(): array
    {
        return self::$stack;
    }

    /**
     * @return int
     */
    public static function getCount(): int
    {
        return count(self::$stack);
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public static function add(Schema $schema)
    {
        $code = $schema->getComponent();
        static::$stack[$code] = $schema;
    }

    /**
     * @param string $code
     * @return mixed
     */
    public static function get(string $code)
    {
        return static::$stack[$code];
    }

    /** @var TableValue */
    protected $table;

    /** @var ComponentValue */
    protected $component;

    protected $idColumn = ['id'];
    protected $fields = [];

    // Pivot exclusive data
    protected $pivot = false;
//    protected $composition = [];

    /** @var InstanceSettings */
    protected $instanceSettings;

    /**
     * @param string $table
     * @param string $component
     * @return static
     * @throws InvalidComponentException
     * @throws InvalidTableException
     */
    public static function table(string $table, string $component): self
    {
        return new static($table, $component);
    }

    /**
     * @param string $table
     * @param string $component
     * @return static
     * @throws InvalidComponentException
     * @throws InvalidTableException
     */
    public static function pivotTable(string $table, string $component): self
    {
        return new static($table, $component, true);
    }


//    public function composeWith(string $component, string $fieldConfig): self
//    {
//        $this->composition[$component] = $fieldConfig;
//        return $this;
//    }

    /**
     * @param string $table
     * @param string $component
     * @param bool $isPivot
     * @throws InvalidComponentException
     * @throws InvalidTableException
     */
    public function __construct(string $table, string $component, bool $isPivot = false)
    {
        $this->table = new TableValue($table);
        $this->component = new ComponentValue($component);
        $this->pivot = $isPivot;
        $this->setIdField();
    }

    /**
     * @param string $field
     * @param string $dbCol
     * @return $this
     * @throws Exceptions\InvalidFieldNameException
     */
    public function setIdField(string $field = 'id', string $dbCol = 'id'): self
    {
        $this->idColumn = [$field];
        $this->addField(IntegerField::define('id'));
        return $this;
    }

    /**
     * @param InstanceSettings $config
     * @return $this
     */
    public function setInstanceSettings(InstanceSettings $config): self
    {
        $this->instanceSettings = $config;
        return $this;
    }

    /**
     * @return InstanceSettings|null
     */
    public function getInstanceSettings(): ?InstanceSettings
    {
        return $this->instanceSettings;
    }

    /**
     * @param AbstractField $field
     * @return $this
     */
    public function addField(AbstractField $field): self
    {
        $this->fields[$field->getName()] = $field;
        return $this;
    }

    /**
     * @return array
     * @throws Exceptions\InvalidSchemaAppClassException
     * @throws Exceptions\InvalidSchemaClassNameForGeneratedClassException
     * @throws Exceptions\InvalidSchemaNamespaceForGeneratedClassException
     * @throws InvalidComponentException
     */
    public function toArray(): array
    {
        return [
            'table' => $this->table->getValue(),
            'idColumn' => $this->pivot ? $this->idColumn : $this->idColumn[0],
            'pivot' => $this->pivot,
            'instance' => $this->instanceSettings->toArray(),
            'base' => $this->instanceSettings->hasBaseComponent() ? $this->instanceSettings->getBaseComponent() : '',
            'fields' => $this->fields,
        ];
    }

    /**
     * @param array $data
     * @param string $component
     * @return static
     * @throws Exceptions\InvalidFieldFilePathException
     * @throws Exceptions\InvalidFieldNameException
     * @throws Exceptions\InvalidSchemaAppClassException
     * @throws Exceptions\InvalidSchemaClassNameForGeneratedClassException
     * @throws Exceptions\InvalidSchemaNamespaceForGeneratedClassException
     * @throws InvalidComponentException
     * @throws InvalidTableException
     */
    public static function fromArray(array $data, string $component): self
    {
        if (!isset($data['pivot']) || !$data['pivot']) {
            $ins = self::table($data['table'], $component);
        } else {
            $ins = self::pivotTable($data['table'], $component);
        }

        $instanceCfg = isset($data['instance']) ? $data['instance'] : [];
        $class = isset($instanceCfg['class']) ? trim($instanceCfg['class']) : '';
        $namespace = isset($instanceCfg['namespace']) ? trim($instanceCfg['namespace']) : '';
        $classname = isset($instanceCfg['classname']) ? trim($instanceCfg['classname']) : '';
        $storePath = isset($instanceCfg['storePath']) ? trim($instanceCfg['storePath']) : '';
        $extends = isset($instanceCfg['extends']) ? trim($instanceCfg['extends']) : '';
        $base = isset($data['base']) ? trim($data['base']) : '';

        $instanceSettings = InstanceSettings::define($class)
            ->setNamespaceForGeneratedClass($namespace)
            ->setClassNameForGeneratedClass($classname)
            ->setWhereStoreGeneratedClass($storePath)
            ->setClassToBeExtended($extends);

        if ($base !== '') {
            $instanceSettings->setBaseComponent($base);
        }

        $implements = '';
        if (isset($data['instance']) && isset($data['instance']['implements'])) {
            $implements = trim($data['instance']['implements']);
        }
        if ($implements !== '') {
            $instanceSettings->setInterface($implements);
        }

        if (isset($data['instance']['traits'])) {
            if (is_array($data['instance']['traits'])) {
                foreach ($data['instance']['traits'] as $trait) {
                    $instanceSettings->setTrait(trim($trait));
                }
            } elseif (is_string($data['instance']['traits'])) {
                $instanceSettings->setTrait(trim($data['instance']['traits']));
            }
        }

        $ins->setInstanceSettings($instanceSettings);

        if (!isset($data['pivot']) || !$data['pivot']) {
            $ins->setIdField($data['idColumn']);

            foreach ($data['fields'] as $field => $fieldConfig) {

                $where = isset($fieldConfig['where']) ? $fieldConfig['where'] : [];
                if (!$where) {
                    $where = [];
                }

                $order = isset($fieldConfig['order']) ? $fieldConfig['order'] : [];
                if (!$order) {
                    $order = [];
                }

                $compress = false;
                if (isset($fieldConfig['compress']) && $fieldConfig['compress'] === true) {
                    $compress = true;
                }

                $softTyped = false;
                if (isset($fieldConfig['softTyped']) && $fieldConfig['softTyped'] === true) {
                    $softTyped = true;
                }

                $assoc = false;
                if (isset($fieldConfig['assoc']) && $fieldConfig['assoc'] === true) {
                    $assoc = true;
                }

                switch ($fieldConfig['type']) {
                    case ColumnType::String:
                        $ins->addField(StringField::define($field, trim($fieldConfig['column'])));
                        break;

                    case ColumnType::Email:
                        $ins->addField(EmailField::define($field, trim($fieldConfig['column'])));
                        break;

                    case ColumnType::HTML:
                        $ins->addField(HTMLField::define($field, trim($fieldConfig['column'])));
                        break;

                    case ColumnType::Boolean:
                        $ins->addField(BooleanField::define($field, trim($fieldConfig['column'])));
                        break;

                    case ColumnType::Integer:
                        $ins->addField(IntegerField::define($field, trim($fieldConfig['column'])));
                        break;

                    case ColumnType::Datetime:
                        $ins->addField(DateTimeField::define($field, trim($fieldConfig['column'])));
                        break;

                    case ColumnType::UnixTimeStamp:
                        $ins->addField(UnixTimeStampField::define($field, trim($fieldConfig['column'])));
                        break;

                    case ColumnType::Float:
                        $ins->addField(FloatField::define($field, trim($fieldConfig['column'])));
                        break;

                    case ColumnType::ForeignKeys:
                        $ins->addField(
                            ForeignKeysField::define($field, trim($fieldConfig['column']))
                                ->setComponent($fieldConfig['component'])
                                ->setWhere($where)
                                ->setOrder($order)
                                ->setIsSoftTyped($softTyped)
                        );
                        break;

                    case ColumnType::ForeignKey:
                        $ins->addField(
                            ForeignKeyField::define($field, trim($fieldConfig['column']))
                                ->setComponent($fieldConfig['component'])
                                ->setWhere($where)
                                ->setIsSoftTyped($softTyped)
                        );
                        break;

                    case ColumnType::File:
                        $ins->addField(
                            FileField::define($field, trim($fieldConfig['column']))
                                ->setStorePath(trim($fieldConfig['storePath']))
                                ->setPublicPath(trim($fieldConfig['publicPath']))
                        );
                        break;

                    case ColumnType::Image:
                        $ins->addField(
                            ImageField::define($field, trim($fieldConfig['column']))
                                ->setStorePath(trim($fieldConfig['storePath']))
                                ->setPublicPath(trim($fieldConfig['publicPath']))
                        );
                        break;

                    case ColumnType::Related:
                        $ins->addField(
                            RelatedField::define($field, trim($fieldConfig['column']))
                                ->setComponent($fieldConfig['component'])
                                ->setWhere($where)
                                ->setIsSoftTyped($softTyped)
                        );
                        break;

                    case ColumnType::RelatedKeys:
                        $ins->addField(
                            RelatedKeysField::define($field, trim($fieldConfig['column']))
                                ->setComponent($fieldConfig['component'])
                                ->setWhere($where)
                                ->setOrder($order)
                                ->setIsSoftTyped($softTyped)
                        );
                        break;

                    case ColumnType::Pivot:
                        $ins->addField(
                            PivotField::define($field, trim($fieldConfig['column']))
                                ->setComponent($fieldConfig['component'])
                                ->setWhere($where)
                                ->setOrder($order)
                        );
                        break;

                    case ColumnType::Color:
                        $ins->addField(ColorField::define($field, trim($fieldConfig['column'])));
                        break;

                    case ColumnType::JSON:
                        $ins->addField(
                            JSONField::define($field, trim($fieldConfig['column']))
                                ->setIsAssoc($assoc)
                                ->setIsCompressed($compress)
                        );
                        break;
                }
            }
        } else {
            $leftField = $data['idColumn'][0];
            $leftFieldCnf = $data['fields'][$leftField];
            $ins->addField(
                PivotLeftIdField::define(trim($leftField), trim($leftFieldCnf['column']))->setComponent(trim($leftFieldCnf['component']))
            );

            $rightField = $data['idColumn'][1];
            $rightFieldCnf = $data['fields'][$rightField];
            $ins->addField(
                PivotRightIdField::define(trim($rightField), trim($rightFieldCnf['column']))->setComponent(trim($rightFieldCnf['component']))
            );

            $keys = array_keys($data['fields']);
            $positionField = $keys[2];
            $positionFieldCnf = $data['fields'][$positionField];
            $ins->addField(
                PivotPositionField::define(trim($positionField), trim($positionFieldCnf['column']))
            );
        }

        return $ins;
    }

    /**
     * @return bool
     */
    public function isPivot(): bool
    {
        return $this->pivot === true;
    }

    /**
     * @return array
     * @throws InvalidComponentException
     */
    public function getAllFields(): array
    {
        $r = $this->fields;

        if ($this->instanceSettings instanceof InstanceSettings) {
            if ($this->instanceSettings->hasLegalExtendClass()) {

                $code = $this->instanceSettings->getClassToBeExtended()::GENERATED_TYPE;
                if ($code) {
                    $schema = Schema::get($code);
                    $fields = $schema->getAllFields();
                    foreach ($fields as $column => $field) {
                        if (!array_key_exists($column, $r)) {
                            $r[$column] = $field;
                        }
                    }
                }
            }

            if ($this->instanceSettings->hasBaseComponent()) {
                $baseSchema = Schema::get($this->instanceSettings->getBaseComponent());
                if ($baseSchema) {
                    $fields = $baseSchema->getAllFields();
                    foreach ($fields as $column => $field) {
                        if (!array_key_exists($column, $r)) {
                            $r[$column] = $field;
                        }
                    }
                }
            }
        }
        return $r;
    }

    /**
     * @param string $field
     * @return array
     * @throws InvalidComponentException
     */
    public function getField(string $field): array
    {
        $haystack = $this->getAllFields();
        if (isset($haystack[$field])) {
            return $haystack[$field];
        }
        return [];
    }

    /**
     * @param bool $asString
     * @return string|string[]
     */
    public function getIdColumn(bool $asString = false)
    {
        if ($asString) {
            return implode('-', $this->idColumn);
        }
        return $this->idColumn;
    }

    /**
     * @return string
     */
    public function getComponent(): string
    {
        return $this->component->getValue();
    }

    /**
     * @param string $component
     * @param bool $matchOne
     * @return array
     * @throws InvalidComponentException
     */
    public function getFieldsPointingToComponent(string $component, bool $matchOne = false): array
    {
        $results = array_filter($this->getAllFields(), function ($field) use ($component) {
            return $field['component'] === $component;
        });

        if ($matchOne) {
            $results = array_values($results);
            if (count($results) > 0) {
                $results = $results[0];
            }
        }
        return $results;
    }

    /**
     * @param string $component
     * @param bool $matchOne
     * @return array|mixed
     * @throws InvalidComponentException
     */
    public function getColumnsPointingToComponent(string $component, bool $matchOne = false)
    {
        $results = array_map(function ($item) {
            return $item['column'];
        }, array_filter($this->getAllFields(), function ($field) use ($component) {
            return $field['component'] === $component;
        }));

        if ($matchOne) {
            $results = array_values($results);
            if (count($results) > 0) {
                return $results[0];
            }
        }
        return array_values($results);
    }
}