<?php

class PersonController extends Controller {
	public function actionList() {
		$People = array();
		
		/* @var $Person Person */
		foreach (Person::getAll() as $Person) {
			$People[] = $Person->toArray();
		}
		
		$this->display("list", array(
			"people" => $People
		));
	}
	
	public function actionNew() {
		if (isset($_POST["form_submitted"])) {
			$Person = new Person();
			$Person->importAttributes($_POST["person"]);
			$Person->save();
		}
		
		$this->redirect("person/list");
	}
	
	public function actionDelete($person_id) {
		Phonenumber::deleteByAttributes(array(
			"person_id" => $person_id
		));
		Person::deleteByPk($person_id);
		
		$this->redirect("person/list");
	}
}
