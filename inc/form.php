<?php # form helper functions
if(count(get_included_files())==1)  exit; //direct access not permitted

const USA_STATES = array(
	'AL'=>'Alabama',
	'AK'=>'Alaska',
	'AZ'=>'Arizona',
	'AR'=>'Arkansas',
	'CA'=>'California',
	'CO'=>'Colorado',
	'CT'=>'Connecticut',
	'DE'=>'Delaware',
	'DC'=>'D.C.',
	'FL'=>'Florida',
	'GA'=>'Georgia',
	'HI'=>'Hawaii',
	'ID'=>'Idaho',
	'IL'=>'Illinois',
	'IN'=>'Indiana',
	'IA'=>'Iowa',
	'KS'=>'Kansas',
	'KY'=>'Kentucky',
	'LA'=>'Louisiana',
	'ME'=>'Maine',
	'MD'=>'Maryland',
	'MA'=>'Massachusetts',
	'MI'=>'Michigan',
	'MN'=>'Minnesota',
	'MS'=>'Mississippi',
	'MO'=>'Missouri',
	'MT'=>'Montana',
	'NE'=>'Nebraska',
	'NV'=>'Nevada',
	'NH'=>'New Hampshire',
	'NJ'=>'New Jersey',
	'NM'=>'New Mexico',
	'NY'=>'New York',
	'NC'=>'North Carolina',
	'ND'=>'North Dakota',
	'OH'=>'Ohio',
	'OK'=>'Oklahoma',
	'OR'=>'Oregon',
	'PA'=>'Pennsylvania',
	'RI'=>'Rhode Island',
	'SC'=>'South Carolina',
	'SD'=>'South Dakota',
	'TN'=>'Tennessee',
	'TX'=>'Texas',
	'UT'=>'Utah',
	'VT'=>'Vermont',
	'VA'=>'Virginia',
	'WA'=>'Washington',
	'WV'=>'West Virginia',
	'WI'=>'Wisconsin',
	'WY'=>'Wyoming'
);

const COUNTRIES = array('United States', 'Canada', 'Japan', 'Korea', 'Other');

const REGEX_ZIP = '/^\d{5}([ -]\d{4})?$|^\w\d\w[ -]?\d\w\d$/'; //U.S. or Canada zip codes
const REGEX_PHONE = '/^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$/';

const FORM_INPUT = [ //name => [minlength, maxlength, type, placeholder, pattern]
	//minLength > 0 = required field
	'email'      => [5,  100, 'email',    'Email'],
	'email2'     => [5,  100, 'email',    'Confirm Email'],
	'pswd'       => [1,  100, 'password', 'Password'],
	'pswd2'      => [1,  100, 'password', 'Confirm Password'],
	'first_name' => [1,   20, 'text',     'First Name'],
	'last_name'  => [1,   30, 'text',     'Last Name'],
	'name'       => [1,   50, 'text',     'Name'],
	'company'    => [0,   50, 'text',     'Company'],
	'street'     => [0,   50, 'text',     'Street Address'],
	'street2'    => [0,   50, 'text',     'Apt, Suite, PO Box'],
	'city'       => [0,   40, 'text',     'City'],
	'state'      => [0,   50, 'select',   'State'],
	'zip'        => [0,   10, 'tel',      'Zip', REGEX_ZIP],
	'country'    => [0,   20, 'select',   'Country'],
	'phone'      => [0,   20, 'tel',      'Phone', REGEX_PHONE],
	'phone2'     => [0,   20, 'tel',      'Phone 2', REGEX_PHONE],
	'remarks'    => [0, 2000, 'textarea', 'Comments'],
	'notes'      => [0, 2000, 'textarea', 'Private Admin Notes'],

/* //x old Kona Earth code
	'card_number'  => [1,   23, 'tel',    'Card number'],
	'card_name'    => [1,   50, 'text',   'Exact name on card'],
	'card_month'   => [1,    2, 'select', 'MM'],
	'card_year'    => [1,    4, 'select', 'Year'],
	'card_code'    => [1, 9999, 'number', 'CVV'],

	//product
	'sku'     => [3,     7,       'text',   'SKU', REGEX_SKU],
	'price'   => [0.01,  9999.99, 'number', 'Price'],
	'qty'     => [-2,    9999,    'number', 'Qty'],
	'net_lbs' => [0.001, 999,     'number', 'Net Lbs'],
	'volume'  => [0,     9999,    'number', 'Volume'],
	'icon'    => [0,     255,     'text',   'Icon'],
	'title'   => [1,     255,     'text',   'Description'],

	//picker
//	'pick_date'  => [1, 0, 'date'],  //implemented inline (because of $minDate)
	'pick_field' => [0, 50, 'text', 'Field picked'],
//	'pick_price' => [0.01, 9999.99, 'number', '$/lb'],  //implemented inline (so isn't required)
	'picker'     => [1, 50, 'text', 'name'],
	'bags'       => [1, 999, 'number', '# bags'],
	'pounds'     => [0.1, 99999, 'number', 'weight'],
*/
];


//---------------------------------------------------------------------------
//FormInput - output a form's <input> fields
function FormInput($key, $val) {
	$keySuffix = preg_replace('/^.*_/', '', $key); //after '_'  Ex: bill_state, card_month...
	$keyLookup = preg_match('/^(bill|ship)_/', $key) ? $keySuffix : $key; //remove bill_, state_

	if(!isset(FORM_INPUT[$keyLookup]))  return ''; //fail silently
	$minLen      = FORM_INPUT[$keyLookup][0];
	$maxLen      = FORM_INPUT[$keyLookup][1];
	$inputType   = FORM_INPUT[$keyLookup][2];
	$placeholder = FORM_INPUT[$keyLookup][3];
	$pattern     = FORM_INPUT[$keyLookup][4] ?? '';

	$out = '';
	$name = " id=\"$key\" name=\"$key\"";
	$type = " type=\"$inputType\"";
	$desc = " placeholder=\"$placeholder\"";
	$min  = ($minLen > 0) ? " minlength=\"$minLen\"" : '';
	$max  = ($maxLen > 0) ? " maxlength=\"$maxLen\"" : '';
	$invalid = ($val === false) ? ' class="invalid"' : '';
	$reqd = (strncmp($key, 'ship_', 5) !== 0  &&  $minLen > 0) ? ' required' : '';

	switch($inputType) {
	case 'select':
		//create $aOptions array
		$useArrayKeys = false;
		switch($keySuffix) {
			case 'state':    $aOptions = USA_STATES;  $useArrayKeys = true;  break;
			case 'country':  $aOptions = COUNTRIES;   break;
			case 'month':    $aOptions = range(1,12); break;
			case 'year':     $year = (int)Date('Y');
			                 $aOptions = range($year, $year+10);  break;
			default:         $aOptions = [];
		}

		//generate <select> with all <option>
		if(!$reqd  &&  empty($val))  $reqd = ' class="select-placeholder"';
		$out = "<select{$name}{$reqd}{$invalid}>";
		$out .= '<option value="" disabled'. (empty($val) ? ' selected' : '') .">$placeholder</option>";
		if($useArrayKeys) {
			//for associative array, use both key and value
			foreach($aOptions as $k=>$v) {
				$out .= "<option value=\"$k\""
					. (($val === $k  ||  $val === $v) ? ' selected' : '')
					. '>'. (($keySuffix === 'state')  ?  "$k - $v"  :  $v) .'</option>';
			} //foreach
		} else {
			//for sequential array, use only value
			foreach($aOptions as $v) {
				//note: $val and $v might be different types, use loose comparison (==)
				$out .= "<option value=\"$v\"" . (($val == $v) ? ' selected' : '') .">$v</option>";
			}
		} //else
		$out .= "</select>\n";
		break;

	case 'textarea':
		$out = "<textarea{$name}{$desc}{$min}{$max}{$reqd}{$invalid}>$val</textarea>\n";
		break;

	case 'number':
		$min = " min=\"$minLen\"";
		$max = " max=\"$maxLen\"";
		if(is_float($minLen)) {
			list($whole, $decimal) = sscanf($minLen, '%d.%s');
			$min = " min=\"$whole\"";
			$max .= " step=\"0.$decimal\"";
		}
		//fallthrough to <input>

	default: //<input>
		static $autofocus = ' autofocus'; //first input gets autofocus
		$value = $val  ?  " value=\"{$val}\""  :  '';
		$pattern = $pattern  ?  (' pattern="'. trim($pattern, '/') .'"')  :  '';
		$out = "<input{$name}{$desc}{$type}{$min}{$max}{$reqd}{$invalid}{$pattern}{$value}{$autofocus}>\n";
		$autofocus = '';
	} //switch
	return $out;
} //FormInput


//---------------------------------------------------------------------------
//FormValidate - trim long values, set missing/invalid values = false
function FormValidate($aForm) {
	foreach($aForm as $key=>$val) {
		$keySuffix = preg_replace('/^.*_/', '', $key); //after '_'  Ex: bill_state, card_month...
		$keyLookup = preg_match('/^(bill|ship)_/', $key) ? $keySuffix : $key; //remove bill_, state_

		if(!isset(FORM_INPUT[$keyLookup]))  continue; //no validation
		$minLen      = FORM_INPUT[$keyLookup][0];
		$maxLen      = FORM_INPUT[$keyLookup][1];
		$inputType   = FORM_INPUT[$keyLookup][2];
		$pattern     = FORM_INPUT[$keyLookup][4] ?? '';
		if((strncmp($key, 'ship_', 5) === 0  ||  $minLen === 0)  &&  empty($val))
			continue; //if optional & empty, no validation necessary

		//trim long strings
		$aForm[$key] = mb_strimwidth($val, 0, $maxLen, '...');

		//required field must meet minlength
		if($minLen > 0  &&  strlen($val) < $minLen)
			$aForm[$key] = false;

		//validate fields with regular expressions
		if(!empty($pattern)  &&  !preg_match($pattern, $aForm[$key]))
			$aForm[$key] = false;

		//validate email
		if($inputType === 'email'  &&  !filter_var($val, FILTER_VALIDATE_EMAIL))
			$aForm[$key] = false;

		//validate <select>
		if($inputType === 'select') {
			switch($keyLookup) {
				case 'state':      if(!array_key_exists($val, USA_STATES))  $aForm[$key] = false;  break;
				case 'country':    if(!in_array($val, COUNTRIES, true))     $aForm[$key] = false;  break;
				case 'card_month': if($val < 1  ||  $val > 12)              $aForm[$key] = false;  break;
				case 'card_year':  $year = (int)Date('Y');
				                   if($val < $year  ||  $val > $year+10)    $aForm[$key] = false;  break;
			} //switch
		} //if <select>
	} //foreach
	return $aForm;
} //FormValidate
