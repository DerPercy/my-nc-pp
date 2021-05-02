<?php

declare(strict_types=1);

namespace OCA\MyPPApp\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

/**
 * Auto-generated migration step: Please modify to your needs!
 */
class Version1000Date20210429135211 extends SimpleMigrationStep {

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 */
	public function preSchemaChange(IOutput $output, Closure $schemaClosure, array $options) {
	}

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 * @return null|ISchemaWrapper
	 */
	public function changeSchema(IOutput $output, Closure $schemaClosure, array $options) {
		$schema = $schemaClosure();

		if (!$schema->hasTable('myppapp_relations')) {
			$table = $schema->createTable('myppapp_relations');
			$table->addColumn('id', 'integer', [
				'autoincrement' => true,
				'notnull' => true,
		 	]);
			$table->addColumn('entity_from', 'integer', [
				'notnull' => true,
			]);
			$table->addColumn('entity_to', 'integer', [
				'notnull' => true,
			]);
			$table->addColumn('relationtype', 'string', [
				'notnull' => true,
				'length' => 50,
			]);

			$table->setPrimaryKey(['id']);
			//$table->addIndex(['user_id'], 'notestutorial_user_id_index');
		}
		return $schema;
	}

	/**
	 * @param IOutput $output
	 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
	 * @param array $options
	 */
	public function postSchemaChange(IOutput $output, Closure $schemaClosure, array $options) {
	}
}
