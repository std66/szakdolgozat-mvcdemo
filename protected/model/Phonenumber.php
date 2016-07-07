<?php


class Phonenumber extends Model {
	public static function getModelInformations() {
		return array(
			"ClassName" => __CLASS__,
			"TableName" => "phonenumber",
			"Fields" => array(
				"phonenumber_id" => array(
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
				
				"person_id" => array(
					"NotNull" => TRUE,
					"Type" => "integer",
					"Validation" => array(
						"Validator" => "TypeValidator",
						"Parameters" => array(
							"allowNull" => FALSE,
							"type" => "integer"
						)
					)
				),
				
				"phone" => array(
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
