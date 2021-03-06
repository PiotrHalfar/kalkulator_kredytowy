<?php
// W skrypcie definicji kontrolera nie trzeba dołączać problematycznego skryptu config.php,
// ponieważ będzie on użyty w miejscach, gdzie config.php zostanie już wywołany.

namespace app\controllers;

//zamieniamy zatem 'require' na 'use' wskazując jedynie przestrzeń nazw, w której znajduje się klasa

use app\forms\CalcFormKredyt;
use app\transfer\CalcResultKredyt;

class CalcControlKredyt{

	private $form;   //dane formularza (do obliczeń i dla widoku)
	private $result; //inne dane dla widoku

	/** 
	 * Konstruktor - inicjalizacja właściwości
	 */
	public function __construct(){
		//stworzenie potrzebnych obiektów
		$this->form = new CalcFormKredyt();
		$this->result = new CalcResultKredyt();
	}
	
	/** 
	 * Pobranie parametrów
	 */
	public function getParams(){
		$this->form->amount = getFromRequest('amount');
		$this->form->period = getFromRequest('period');
		$this->form->percent = getFromRequest('percent');
	}
	
	/** 
	 * Walidacja parametrów
	 * @return true jeśli brak błedów, false w przeciwnym wypadku 
	 */
	public function validate() {
		// sprawdzenie, czy parametry zostały przekazane
		if (! (isset ( $this->form->amount ) && isset ( $this->form->period ) && isset ( $this->form->percent ))) {
			// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
			return false; //zakończ walidację z błędem
		}
		
		// sprawdzenie, czy potrzebne wartości zostały przekazane
		if ($this->form->amount == "") {
			getMessages()->addError('Nie podano kwoty');
		}
		if ($this->form->period == "") {
			getMessages()->addError('Nie podano okresu splaty');
		}
		
		// nie ma sensu walidować dalej gdy brak parametrów
		if (! getMessages()->isError()) {
			
			// sprawdzenie, czy $amount i $period są liczbami całkowitymi
			if (! is_numeric ( $this->form->amount )) {
				getMessages()->addError('Pierwsza wartość nie jest liczbą całkowitą');
			}
			
			if (! is_numeric ( $this->form->period )) {
				getMessages()->addError('Druga wartość nie jest liczbą całkowitą');
			}
                        if ( ($this->form->amount >intval(1000000))) 
                        {
                                getMessages()->addError('Maksymalna wartość kredytu to milion złoty');
                        }
                        if ( ($this->form->period >intval(120))) 
                        {
                                getMessages()->addError('Maksymalny okres splaty to 120 miesięcy');
                        }
		}
		
		return ! getMessages()->isError();
	}
	
	/** 
	 * Pobranie wartości, walidacja, obliczenie i wyświetlenie
	 */
	public function action_calcCompute(){
             
		$this->getParams();
		
		if ($this->validate()) {
				
			//konwersja parametrów
			$this->form->amount = floatval($this->form->amount);
			$this->form->period = intval($this->form->period);
                        $this->form->percent = floatval($this->form->percent);
                        
			getMessages()->addInfo('Parametry poprawne.');
			if (inRole('admin')) {	
			//wykonanie obliczen oraz zaokrąglenie wyniku
			$this->result->result = ($this->form->amount / $this->form->period) + (($this->form->amount / $this->form->period)* $this->form->percent/100); //dzielimy kwote przez liczbe miesiecy a nastepnie obliczamy procent z kwoty i dodajemy calosc
                        $this->result->result = round($this->result->result, 2); //zaokraglenie wyniku do 2 liczb po przecinku   
			} else {
						getMessages()->addError('Tylko administrator może wykonać tę operację');
					}
			getMessages()->addInfo('Wykonano obliczenia.');
                        
                        try{
                            $database = new \Medoo\Medoo([
                       
                                'type' => 'mysql',
                                'host' => 'localhost',
                                'database' => 'kalkulator',
                                'username' => 'root',
                                'password' => '',
                                
                                'charset' => 'utf8',
                                'collation' => 'utf8_polish_ci',
                                'port' => 3306,

                                'option' => [
                                        \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
                                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                                ],
 
                            ]);
                            $database->insert("wynik", [
                                "kwota" => $this->form->amount,
                                "okres_splaty" => $this->form->period,
                                "procent" => $this->form->percent,
                                "rata" => $this->result->result,
                                "data" => date("Y-m-d H:i:s")
                                ]);
                        } catch (\PDOException $ex) {
                            getMessages()->addError("DB Error: ".$ex->getMessage());
                        }
		}
		
		$this->generateView();
              
	}
	public function action_calcShow(){
		$this->generateView();
	}
	
	public function generateView()
       {  
                getSmarty()->assign('user',unserialize($_SESSION['user']));
		getSmarty()->assign('form',$this->form);
		getSmarty()->assign('res',$this->result);
		
		getSmarty()->display('CalcKredytView.tpl'); // już nie podajemy pełnej ścieżki - foldery widoków są zdefiniowane przy ładowaniu Smarty
       }
			
}
