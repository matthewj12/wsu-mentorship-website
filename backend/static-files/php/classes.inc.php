<?php

class Option {
	public string $label;
	public string $value;

	public function __construct($label, $value) {
		$this->label = $label;
		$this->value = $value;
	}
}

class SurveyItem {
	private string $type;
	private string $tblName;
	private string $colName;
	private string $desc;
	private $options;

	public function __construct($type, $colName, $desc, $options=null){
		$this->type    = $type;
		$this->tblName = 'participant';

		if ($type == 'dropdown' || $type == 'checkbox assoc tbl') {
			$this->tblName = "$colName assoc tbl";
			$colName .= ' id';
		}

		// $this->tblName = str_replace(' ', '-', $this->tblName);
		// $this->colName = str_replace(' ', '-', $colName);
		$this->colName = $colName;

		$this->desc = $desc;

		if ($type == 'radio') {
			$this->options = $options;
		}
	}

	public function echoHtml() {
		$extraClass = $this->type == 'checkbox bool' ? ' checkbox-bool' : '';
		$html = "<div class = \"survey-item$extraClass\">";

		$tblNameHyph = str_replace(' ', '-', $this->tblName);
		$colNameHyph = str_replace(' ', '-', $this->colName);
		$nameVal = "$tblNameHyph.$colNameHyph";

		$labelId = "id=\"$tblNameHyph.$colNameHyph.label\"";

		$html .= "<label $labelId class=\"survey-item-label\">\n";
		$html .= "\t$this->desc\n";
		$html .= "</label>\n";

		switch ($this->type) {
			case "radio":
				foreach ($this->options as $option) {
					$idVal   = "$nameVal.$option->value";
					$id      = "id    = \"$idVal\"";
					$name    = "name  = \"$nameVal\"";
					$value   = "value = \"$option->value\"";
					$type    = 'type  = "radio"';
					
					$html .= "<input $name $value $id $type required>\n";
					$html .= "\n";
					
					// ------------------------------------------

					$for   = "for = \"$idVal\"";

					$html .= "<label $for>\n";
					$html .= "\t$option->label";
					$html .= "</label>\n";
				}
				break;

			case "textbox":
				$name   = "name = \"$nameVal\"";
				$id = "$tblNameHyph.$this->colName";
				
				$html .= "<input $name class=\"textbox\" $id type = \"text\" required>";

				break;

			case "dropdown":
				$this->options = readRefTbl($this->tblName);
				$name = "name = \"$nameVal\"";
				$html .= "<select $name id=\"$nameVal\">";

				foreach ($this->options as $option) {
					$idVal  = "$nameVal.$option->value";
					$id     = "id = \"$idVal\"";
					$value  = "value = \"$option->value\"";
					$for    = "for = \"$idVal\"";
					
					$html .= " $<option $value $id required>$option->label</option>";
					$html .= "\n";
				}

				$html .= "</select>";

				break;

			case "checkbox bool":
				$idVal  = "$nameVal.1";
				$id     = "id = \"$idVal\"";
				$name   = "name = \"$idVal\"";
				$value  = "value = \"1\"";
				$for    = "for = \"$idVal\"";
				$type   = 'type = "checkbox"';
				
				$html .= "<input $name $value $id $type>";
				$html .= "\n";

				// ------------------------------------------

				$for   = "for = \"$idVal\"";

				$html .= "<label $for>\n";
				$html .= "\t$this->desc";
				$html .= "</label>";
				$html .= "<br>";

				break;

			case "checkbox assoc tbl":
				// "options" in this context refers to checkboxes
				$this->options = readRefTbl($this->tblName);
				
				foreach ($this->options as $option) {
					$idVal  = "$nameVal.$option->value";
					$id     =    "id = \"$idVal\"";
					$name   =  "name = \"$idVal\"";
					$value  = "value = \"$option->value\"";
					$for    =   "for = \"$idVal\"";
					$type   =  'type = "checkbox"';
					
					$html .= "<input $name $value $id $type>";
					$html .= "\n";

					// ------------------------------------------

					$for   = "for = \"$idVal\"";

					$html .= "<label $for>\n";
					$html .= "\t$option->label";
					$html .= "</label>";
					$html .= "<br>";
				}

				$html .= "</select>";

				break;

			case "textarea":
				$name = "name = \"$nameVal\"";
				$id = "\"$this->colName\"";
				
				$html .= "<textarea $name $id rows = \"8\" cols = \"60\" placeholder=\"Enter optional response\">\n";
				$html .= "</textarea>\n";
				break;
		}

		$html .= "</div>\n";
		$html .= "\n";
		echo $html;
	}
}

class Participant {
	public $participantCols;
	public $assocTblDistinct;
	public $dataPoints = [];

	public function __construct($starid) {
		$this->participantCols = getParticipantFields();
		$this->assocTblDistinct = [
			'gender',
			'hobby',
			'major',
			'pre program',
			'race',
			'religious affiliation',
			'second language',
			'important quality',
			'max matches',
			'preferred gender',
			'preferred hobby',
			'preferred major',
			'preferred pre program',
			'preferred race',
			'preferred religious affiliation',
			'preferred second language'
		];
		
		$this->dataPoints['starid'] = [$starid];

		$allCols = array_merge($this->participantCols, $this->assocTblDistinct);

		$this->dataPoints = getParticipantInfo($this->dataPoints['starid'][0], $allCols);
	}
}