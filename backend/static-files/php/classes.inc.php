<?php

class Matching {
	public $mentorStarid;
	public $menteeStarids;

	public function __construct($mentorStarid, $menteeStarids) {
		$this->mentorStarid = $mentorStarid;
		$this->menteeStarids = $menteeStarids;
	}
}

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


			case "date":
				$name   = "name = \"$nameVal\"";
				$id = "$tblNameHyph.$this->colName";
				
				$html .= "<input $name class=\"textbox\" $id type = \"date\" required>";

				break;

			case "dropdown":
				if (substr($this->colName, 0, 17) == 'important quality') {
					// it makes more sense for it to read "same major as me" instead of "major", etc.
					$keys = readRefTbl($this->tblName);
					$this->options = [
						new Option("same gender as me", $keys[0]->value),
						new Option("same major(s) as me", $keys[1]->value),
						new Option("same pre program(s) as me", $keys[2]->value),
						new Option("same religiuos affiliation as me", $keys[3]->value),
						new Option("same hobbies as me", $keys[4]->value),
						new Option("has same value as me for international student question", $keys[5]->value),
						new Option("has same value as me for lgbtq+ question", $keys[6]->value),
						new Option("has same value as me for student athlete question", $keys[7]->value),
						new Option("has same value as me for multilingual question", $keys[8]->value),
						new Option("has same value as me for not born in this country question", $keys[9]->value),
						new Option("has same value as me for transfer student question", $keys[10]->value),
						new Option("has same value as me for first generation college student question", $keys[11]->value),
						new Option("has same value as me for interested in diversity groups question", $keys[12]->value)
					];
				}
				else {
					$this->options = readRefTbl($this->tblName);
				}
				$name = "name = \"$nameVal\"";
				$html .= "<select $name id=\"$nameVal\">";

				foreach ($this->options as $option) {
					$idVal  = "$nameVal.$option->value";
					$id     = "id = \"$idVal\"";
					$value  = "value = \"$option->value\"";
					$for    = "for = \"$idVal\"";
					
					$html .= "<option $value $id required>$option->label</option>";
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
	public $matchings = [];

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

		$this->dataPoints = getParticipantInfo($starid, $allCols);

		$groupStr = $this->dataPoints['is mentor'][0] == '1' ? 'mentor' : 'mentee';
		$groupStrOpposite = $this->dataPoints['is mentor'][0] == '1' ? 'mentee' : 'mentor';

		$sqlQuery = "SELECT * FROM `mentorship` WHERE `$groupStr starid` = '$starid'";
		$stmt = connect()->prepare($sqlQuery);
		$stmt->execute();

		foreach ($stmt->fetchAll() as $row) {
			$this->matchings[] = $row[$groupStrOpposite . ' starid'];
		}
	}
}