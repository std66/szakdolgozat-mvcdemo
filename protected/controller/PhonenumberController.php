<?php

class PhonenumberController extends Controller {
	public function actionList($person_id) {
		$Person = Person::getByPk($person_id);
		
		$Numbers = array();
		/* @var $Phone Phonenumber */
		foreach (Phonenumber::getAll() as $Phone) {
			$Numbers[] = $Phone->toArray();
		}
		
		$this->display("list", array(
			"numbers" => $Numbers,
			"person" => $Person->toArray()
		));
	}
	
	public function actionNew() {
		if (isset($_POST["form_submitted"])) {
			$Phone = new Phonenumber();
			$Phone->importAttributes(array(
				"person_id" => (int)$_POST["phone"]["person_id"],
				"phone" => $_POST["phone"]["phone"]
			));
			$Phone->save();
			
			var_dump(Application::DB()->error);
		}
		
		$this->redirect("phonenumber/list", array(
			"person_id" => $_POST["phone"]["person_id"]
		));
	}
	
	public function actionDelete($phonenumber_id) {
		$Phone = Phonenumber::getByPk($phonenumber_id);
		Phonenumber::deleteByPk($phonenumber_id);
		
		$this->redirect("phonenumber/list", array(
			"person_id" => $Phone->person_id
		));
	}
}
