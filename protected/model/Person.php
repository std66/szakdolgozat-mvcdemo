<?php

/**
 * A Person tábla egy rekordját reprezentáló modell.
 *
 * @author Tamás
 * @property int $category_id A kategória azonosítója
 * @property string $name A kategória neve
 * @property string $description A kategória leírása
 */
class Person extends Model {
	public static function getModelInformations() {
		return array(
			"ClassName" => __CLASS__,
			"TableName" => "person",
			"Fields" => array(
				"person_id" => array(
					"AutoIncrement" => TRUE,
					"PrimaryKey" => TRUE,
					"Type" => "integer",
					"Validation" => array(
						"Validator" => "TypeValidator",
						"Parameters" => array(
							"allowNull" => FALSE,
							"type" => "integer"
						)
					)
				),
				
				"name" => array(
					"NotNull" => TRUE,
					"Type" => "string",
					"Validation" => array(
						"Validator" => "StringValidator",
						"Parameters" => array(
							"allowNull" => FALSE,
							"allowEmpty" => FALSE,
							"maxLength" => 45
						)
					)
				),
				
				"address" => array(
					"Type" => "string",
					"NotNull" => TRUE,
					"Validation" => array(
						"Validator" => "StringValidator",
						"Parameters" => array(
							"allowNull" => FALSE,
							"allowEmpty" => FALSE,
							"maxLength" => 45
						)
					)
				),
			)
		);
	}
}
