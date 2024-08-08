<?php
namespace App\Helpers;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Company;

class Helper
{
	/**
	 * Get element name /subject from its id
	 * 
	 * @param string $element
	 * @param int $id
	 * @return string|null
	 */
    public static function getElementName($element, $id)
	{
		switch ($element) {
			case 'ticket':
				$element = Ticket::find($id)->subject;
				break;
			case 'project':
				$element = Project::find($id)->name;
				break;
			case 'user':
				$element = User::find($id)->name;
				break;
			case 'company':
				$element = Company::find($id)->name;
				break;
			default:
				$element = null;
		}
		
		return $element;
	}
}
