<?php
namespace Ajax\php\ubiquity\utils;

use Ajax\semantic\widgets\dataform\DataForm;
use Ubiquity\contents\validation\ValidatorsManager;
use Ubiquity\orm\OrmUtils;

class DataFormHelper {

	/**
	 * Adds the instance members as form fields.
	 *
	 * @param DataForm $form
	 */
	public static function defaultFields(DataForm $form): void {
		$form->setFields(OrmUtils::getFormAllFields($form->getModel()));
	}

	/**
	 * Adds the default ui constraints to the form.
	 *
	 * @param DataForm $form
	 */
	public static function defaultUIConstraints(DataForm $form): void {
		self::addDefaultUIConstraints($form, OrmUtils::getFieldTypes($form->getModel()), ValidatorsManager::getUIConstraints($form->getModelInstance()));
	}

	/**
	 *
	 * @param DataForm $form
	 * @param array $fieldTypes
	 * @param array $uiConstraints
	 */
	public static function addDefaultUIConstraints(DataForm $form, array $fieldTypes, array $uiConstraints): void {
		foreach ($fieldTypes as $property => $type) {
			$rules = $uiConstraints[$property] ?? [];
			if ($hasRules = \count($rules) > 0) {
				$form->setValidationParams([
					"on" => "blur",
					"inline" => true
				]);
			}
			$noPName = false;
			$noPType = false;
			switch ($property) {
				case 'password':
					$form->fieldAsInput($property, [
						'inputType' => 'password'
					] + $rules);
					break;
				case 'email':
				case 'mail':
					$form->fieldAsInput($property, $rules);
					break;
				default:
					$noPName = true;
			}

			switch ($type) {
				case 'tinyint(1)':
				case 'bool':
				case 'boolean':
					$form->fieldAsCheckbox($property, \array_diff($rules['rules'] ?? [], [
						'empty'
					]));
					break;
				case 'int':
				case 'integer':
					$form->fieldAsInput($property, [
						'inputType' => 'number'
					] + $rules);
					break;
				case 'date':
					$form->fieldAsInput($property, [
						'inputType' => 'date'
					] + $rules);
					break;
				case 'datetime':
					$form->fieldAsInput($property, [
						'inputType' => 'datetime-local'
					] + $rules);
					break;
				case 'text':
					$form->fieldAsTextarea($property, $rules);
					break;
				default:
					$noPType = true;
			}
			if ($hasRules && $noPName && $noPType) {
				$form->fieldAsInput($property, $rules);
			}
		}
	}
}