<?php

namespace Lkt\Factory\Schemas;

use Lkt\Factory\Schemas\ComputedFields\AbstractComputedField;
use Lkt\Factory\Schemas\CRUDs\AbstractCRUD;
use Lkt\Factory\Schemas\CRUDs\CreateHandler;
use Lkt\Factory\Schemas\CRUDs\DeleteHandler;
use Lkt\Factory\Schemas\CRUDs\UpdateHandler;
use Lkt\Factory\Schemas\Exceptions\InvalidComponentException;
use Lkt\Factory\Schemas\Exceptions\InvalidTableException;
use Lkt\Factory\Schemas\Exceptions\SchemaNotDefinedException;
use Lkt\Factory\Schemas\Fields\AbstractField;
use Lkt\Factory\Schemas\Fields\ForeignKeyField;
use Lkt\Factory\Schemas\Fields\ForeignKeysField;
use Lkt\Factory\Schemas\Fields\IdField;
use Lkt\Factory\Schemas\Fields\PivotField;
use Lkt\Factory\Schemas\Fields\PivotLeftIdField;
use Lkt\Factory\Schemas\Fields\PivotRightIdField;
use Lkt\Factory\Schemas\Fields\RelatedField;
use Lkt\Factory\Schemas\Fields\RelatedKeysField;
use Lkt\Factory\Schemas\Fields\RelatedKeysMergeField;
use Lkt\Factory\Schemas\Values\ComponentValue;
use Lkt\Factory\Schemas\Values\TableValue;
use function Lkt\Tools\Arrays\getArrayFirstPosition;

final class Schema
{
    /** @var Schema[] */
    private static array $stack = [];

    /**
     * @return Schema[]
     */
    public static function getStack(): array
    {
        return self::$stack;
    }

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
        self::$stack[$code] = $schema;
    }

    /**
     * @throws SchemaNotDefinedException
     */
    public static function get(string $code): self
    {
        if (!self::$stack[$code] instanceof Schema) {
            throw new SchemaNotDefinedException($code);
        }
        return self::$stack[$code];
    }

    public static function exists(string $code): bool
    {
        return self::$stack[$code] instanceof Schema;
    }

    protected ?TableValue $table = null;

    protected ?ComponentValue $component = null;

    protected $databaseConnector = '';

    /** @var AbstractField[] */
    protected $idFields = [];
    protected $idColumns = [];
    protected $idColumnsInTable = [];

    /** @var AbstractField[] */
    protected $fields = [];

    /** @var AbstractCRUD[] */
    protected $crud = [];

    // Pivot exclusive data
    protected $pivot = false;
//    protected $composition = [];

    /** @var InstanceSettings */
    protected $instanceSettings;

    protected $countableField = '';
    protected $itemsPerPage = 0;

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
    }

    public function setCountableField(string $fieldName): self
    {
        $this->countableField = $fieldName;
        return $this;
    }

    public function getCountableField(): string
    {
        return $this->countableField;
    }

    public function hasCountableField(): bool
    {
        return $this->countableField !== '';
    }

    public function setItemsPerPage(int $itemsPerPage): self
    {
        $this->itemsPerPage = $itemsPerPage;
        return $this;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function hasItemsPerPage(): bool
    {
        return $this->itemsPerPage > 0;
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
     * @param AbstractCRUD $crud
     * @return $this
     */
    public function addCRUD(AbstractCRUD $crud): self
    {
        $this->crud[] = $crud;
        return $this;
    }

    /**
     * @return CreateHandler|null
     */
    public function getCreateHandler(): ?CreateHandler
    {
        $data = array_values(array_filter($this->crud, function ($crud) {
            return $crud instanceof CreateHandler;
        }));
        if (count($data) > 0) {
            return $data[0];
        }
        return null;
    }

    /**
     * @return DeleteHandler|null
     */
    public function getDeleteHandler(): ?DeleteHandler
    {
        $data = array_values(array_filter($this->crud, function ($crud) {
            return $crud instanceof DeleteHandler;
        }));
        if (count($data) > 0) {
            return $data[0];
        }
        return null;
    }

    /**
     * @return DeleteHandler|null
     */
    public function getUpdateHandler(): ?UpdateHandler
    {
        $data = array_values(array_filter($this->crud, function ($crud) {
            return $crud instanceof UpdateHandler;
        }));
        if (count($data) > 0) {
            return $data[0];
        }
        return null;
    }


    /**
     * @param AbstractField $field
     * @return $this
     * @throws \Exception
     */
    public function addField(AbstractField $field): self
    {
        $name = $field->getName();
        if (isset($this->fields[$name]) && $this->fields[$name] instanceof AbstractField) {
            throw new \Exception("Field '{$name}' already registered in schema '{$this->getComponent()}'");
        }
        $this->fields[$name] = $field;
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
            'idColumn' => $this->pivot ? $this->idFields : $this->idFields[0],
            'pivot' => $this->pivot,
            'instance' => $this->instanceSettings->toArray(),
            'base' => $this->instanceSettings->hasBaseComponent() ? $this->instanceSettings->getBaseComponent() : '',
            'fields' => $this->fields,
        ];
    }

    /**
     * @return bool
     */
    public function isPivot(): bool
    {
        return $this->pivot === true;
    }

    /**
     * @return AbstractField[]
     * @throws InvalidComponentException
     * @throws SchemaNotDefinedException
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
     * @return AbstractField[]
     */
    public function getNonRelationalFields(): array
    {
        return array_filter($this->getAllFields(), function (AbstractField $field) {
            if ($field instanceof ForeignKeyField
                || $field instanceof ForeignKeysField
                || $field instanceof PivotField
                || $field instanceof RelatedField
                || $field instanceof RelatedKeysField
                || $field instanceof RelatedKeysMergeField) {
                return false;
            }
            return true;
        });
    }

    /**
     * @return AbstractField[]
     */
    public function getRelationalFields(): array
    {
        return array_filter($this->getAllFields(), function (AbstractField $field) {
            if ($field instanceof ForeignKeyField
                || $field instanceof ForeignKeysField
                || $field instanceof PivotField
                || $field instanceof RelatedField
                || $field instanceof RelatedKeysField
                || $field instanceof RelatedKeysMergeField) {
                return true;
            }
            return false;
        });
    }

    /**
     * @return AbstractField[]
     */
    public function getSameTableFields(): array
    {
        return array_filter($this->getAllFields(), function (AbstractField $field) {
            if ($field instanceof PivotField
                || $field instanceof RelatedField
                || $field instanceof RelatedKeysMergeField
                || $field instanceof AbstractComputedField) {
                return false;
            }
            return true;
        });
    }

    /**
     * @return AbstractField[]
     */
    public function getFilterableFields(): array
    {
        return array_filter($this->getAllFields(), function (AbstractField $field) {
            if ($field instanceof ForeignKeyField
                || $field instanceof PivotField
                || $field instanceof RelatedField) {
                return false;
            }
            return true;
        });
    }

    /**
     * @param string $field
     * @return AbstractField|null
     * @throws InvalidComponentException
     * @throws SchemaNotDefinedException
     */
    public function getField(string $field): ?AbstractField
    {
        $haystack = $this->getAllFields();
        if (isset($haystack[$field])) {
            return $haystack[$field];
        }

        // Catch foreign keys cast to integer keys
        $l = strlen($field);
        $endsWithId = substr($field, $l - 2, 2) === 'Id';

        if (!$endsWithId) {
            return null;
        }

        $keyWithoutId = substr($field, 0, $l - 2);
        if (isset($haystack[$keyWithoutId]) && $haystack[$keyWithoutId] instanceof ForeignKeyField) {
            return $haystack[$keyWithoutId];
        }
        return null;
    }

    public function getRelatedField(string $field): ?RelatedField
    {
        $r = $this->getField($field);
        if ($r instanceof RelatedField) {
            return $r;
        }
        return null;
    }

    public function getForeignKeyField(string $field): ?ForeignKeyField
    {
        $r = $this->getField($field);
        if ($r instanceof ForeignKeyField) {
            return $r;
        }
        return null;
    }

    public function getForeignKeysField(string $field): ?ForeignKeysField
    {
        $r = $this->getField($field);
        if ($r instanceof ForeignKeysField) {
            return $r;
        }
        return null;
    }

    public function getForeignKeysFieldPointingToComponent(string $component): ?ForeignKeysField
    {
        $results = $this->getFieldsPointingToComponent($component);
        foreach ($results as $result) {
            if ($result instanceof ForeignKeysField) {
                return $result;
            }
        }
        return null;
    }

    public function getPivotField(string $field): ?PivotField
    {
        $r = $this->getField($field);
        if ($r instanceof PivotField) {
            return $r;
        }
        return null;
    }

    /**
     * @return AbstractField[]
     * @throws InvalidComponentException
     * @throws SchemaNotDefinedException
     */
    public function getIdentifiers(): array
    {
        if (count($this->idFields) > 0) {
            return $this->idFields;
        }

        /** @var AbstractField[] $stack */
        $stack = $this->getAllFields();

        if ($this->isPivot()) {
            $fields = array_filter($stack, function (AbstractField $field) {
                return $field instanceof PivotLeftIdField || $field instanceof PivotRightIdField;
            });

            $this->idColumns = array_keys($fields);
            $this->idFields = array_values($fields);
            $this->idColumnsInTable = array_map(function($field) { return $field->getColumn();}, $fields);
            return $this->idFields;
        }

        $fields = array_filter($stack, function (AbstractField $field) {
            return $field instanceof IdField;
        });

        $this->idColumns = array_keys($fields);
        $this->idFields = array_values($fields);
        $this->idColumnsInTable = array_map(function($field) { return $field->getColumn();}, $fields);
        return $this->idFields;
    }

    /**
     * @return string
     * @throws InvalidComponentException
     */
    public function getIdString()
    {
        $this->getIdentifiers();
        return implode('-', $this->idColumns);
    }

    /**
     * @return string
     * @throws InvalidComponentException
     */
    public function getIdInTableString()
    {
        $this->getIdentifiers();
        return implode('-', $this->idColumnsInTable);
    }

    /**
     * @return array|mixed
     * @throws InvalidComponentException
     */
    public function getIdColumn()
    {
        $this->getIdentifiers();
        return $this->idColumns;
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
     * @return array
     * @throws InvalidComponentException
     */
    public function getFieldsPointingToComponent(string $component): array
    {
        /** @var AbstractField[] $fields */
        $fields = $this->getRelationalFields();
        return array_filter($fields, function ($field) use ($component) {
            if ($field instanceof ForeignKeyField
                || $field instanceof ForeignKeysField
                || $field instanceof RelatedKeysField
                || $field instanceof PivotField
                || $field instanceof RelatedField) {
                return $field->getComponent() === $component;
            }
            return false;
        });
    }

    /**
     * @param string $component
     * @return AbstractField|null
     * @throws InvalidComponentException
     */
    public function getOneFieldPointingToComponent(string $component): ?AbstractField
    {
        $r = $this->getFieldsPointingToComponent($component);
        if (count($r) > 0) {
            return getArrayFirstPosition($r);
        }
        return null;
    }

    /**
     * @param string $component
     * @param bool $matchOne
     * @return array|mixed
     * @throws InvalidComponentException
     */
    public function getColumnsPointingToComponent(string $component, bool $matchOne = false)
    {
        /** @var AbstractField[] $fields */
        $fields = $this->getAllFields();
        $results = array_map(function ($item) {
            return $item->getColumn();
        }, array_filter($fields, function ($field) use ($component) {
            if ($field instanceof ForeignKeyField
                || $field instanceof ForeignKeysField
                || $field instanceof RelatedKeysField
                || $field instanceof PivotField
                || $field instanceof RelatedField) {
                return $field->getComponent() === $component;
            }
            return false;
        }));

        if ($matchOne) {
            $results = array_values($results);
            if (count($results) > 0) {
                return $results[0];
            }
        }
        return array_values($results);
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table->getValue();
    }

    /**
     * @param string $connectorName
     * @return $this
     */
    public function setDatabaseConnector(string $connectorName): self
    {
        $this->databaseConnector = $connectorName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabaseConnector(): string
    {
        return $this->databaseConnector;
    }
}