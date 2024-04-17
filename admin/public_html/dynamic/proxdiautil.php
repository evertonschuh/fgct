<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class FbtIncludesProxDiaUtil {
	
	
	protected $_feriados;
	
	/*
	* proxDiaUtil()
	* Retorna o próximo dia útil em relação a data.
	*
	* @data   -> Variável que recebe a data. 
	* 			Formato: DD/MM/AAAA
	*/
	
	function __construct()
	{
		
	}
	
	function proxDiaUtil($data = null)
	{
		// Separa a data
		$dt = explode('-', $data);
		$dia = $dt[2];
		$mes = $dt[1];
		$ano = $dt[0];
		
		$this->setFeriados($ano);
		/*
		
		(1) Pega uma data de referência (variável), compara com o datas definidas pelo sistema (feriados e finais de semana) 
			e retorna a próxima data um dia útil
		(2) As datas do sistema são: [1] sábados; [2] domingos; [3] feriados fixos; [4] feriados veriáveis; [5] dias opcionais (ex: quarta de cinza)
		(3) Retorno o próximo/imediato dia útil.
		
		*/
		
		// 1 - verifica se a data referente é um final de semana (sábado ou domingo); 
		// se sábado acrescenta mais 1 dia e faz nova verificação
		// se domingo acrescenta mais 1 dia e faz nova verificação
		$fsem = date('D', mktime(0,0,0,$mes,$dia,$ano));
		
		$i = 1;
		switch($fsem)
		{
			case 'Sat': 
				return $this->proxDiaUtil(date('Y-m-d', mktime(0,0,0,$mes,$dia+$i,$ano))); 
			break;
			
			case 'Sun':
				return $this->proxDiaUtil(date('Y-m-d', mktime(0,0,0,$mes,$dia+$i,$ano)));
			break;
			
			default:
				// 2 - verifica se a data referente é um feriado
				//echo 'entrou';
				if(in_array($data, $this->_feriados)== true)
				{
					return $this->proxDiaUtil(date('Y-m-d', mktime(0,0,0,$mes,$dia+$i,$ano)));
				}
				else
				{
					// Retorna o dia útil
					return $data;
				}
			break;
		}	
		
	
	}

	/*
	* Feriados()
	* Gera um array com as datas dos feriados com referência no ano da data pesquisada.
	*
	* @ano   -> Variável que recebe o ano base para o cálculo;
	*/
	private function setFeriados($ano = null)
	{	
		//echo date('Y-m-d', mktime(0,0,0,'01','01',$ano));
		$this->_feriados = array
		(
		  // Armazena feriados fíxos
			
			date('Y-m-d', mktime(0,0,0,'01','01',$ano)), // 01/01 Ano novo
			date('Y-m-d', mktime(0,0,0,'02','02',$ano)), // 02/02 Nossa Sra dos Navegantes
			date('Y-m-d', mktime(0,0,0,'04','21',$ano)), // 21/04 Tiradentes
			date('Y-m-d', mktime(0,0,0,'05','01',$ano)), // 01/05 Dia do trabalho
			date('Y-m-d', mktime(0,0,0,'09','07',$ano)), // 07/09 Independencia
			date('Y-m-d', mktime(0,0,0,'10','12',$ano)), // 12/10 Aparecida
			date('Y-m-d', mktime(0,0,0,'11','02',$ano)), // 02/11 Finados
			date('Y-m-d', mktime(0,0,0,'11','15',$ano)), // 15/11 Proclamação
			//date('Y-m-d', mktime(0,0,0,'12','24',$ano)), // 24/12 Véspera de Natal
			date('Y-m-d', mktime(0,0,0,'12','25',$ano)), // 25/12 Natal
			//date('Y-m-d', mktime(0,0,0,'12','31',$ano)), // 31/12 Véspera de Ano novo
			
			// Armazena feriados variáveis
			//flxFeriado($ano, 'pascoa', $r = 1), // Páscoa - Sempre domingo
			$this->getFlexFeriado($ano, 'carn_sab', $r = 1), // Carnaval - Sempre sábado
			$this->getFlexFeriado($ano, 'carn_dom', $r = 1), // Carnaval - Sempre domingo
			$this->getFlexFeriado($ano, 'carn_seg', $r = 1), // Carnaval - Segunda
			$this->getFlexFeriado($ano, 'carn_ter', $r = 1), // Carnaval - Terça
			//strtoupper(flxFeriado($ano, 'carn_qua', $r = 1)), // Carnaval - Quarta de cinza
			$this->getFlexFeriado($ano, 'sant_sex', $r = 1), // Sexta Santa
			$this->getFlexFeriado($ano, 'corp_chr', $r = 1)  // Corpus Christi
		);
		
		
		
		//return $feriados;
	}

	/*
	* flxFeriado()
	* Calcula os dias de feriados variáveis. Com base na páscoa.
	*
	* @ano   -> Variável que recebe o ano base para o cálculo;
	* @tipo  -> Tipo de dados
	* 			[carn_sab]: Sábado de carnaval;
	* 			[carn_dom]: Domingo de carnaval;
	* 			[carn_seg]: Segunda-feira de carnaval;
	* 			[carn_ter]: Terça-feira de carnaval;
	* 			[carn_qua]: Quarta-feira de carnaval;
	* 			[sant_sex]: Sexta-feira santa;
	* 			[corp_chr]: Corpus Christi;
	*/

	private function getFlexFeriado($ano, $tipo = NULL)
	{
		$a=explode("-", $this->getCalPascoa($ano));
		switch($tipo)
		{
			case 'carn_sab': $d = $a[2]-50; break;
			case 'carn_dom': $d = $a[2]-49; break;
			case 'carn_seg': $d = $a[2]-48; break;
			case 'carn_ter': $d = $a[2]-47; break;
			case 'carn_qua': $d = $a[2]-46; break;
			case 'sant_sex': $d = $a[2]-2; break;
			case 'corp_chr': $d = $a[2]+60; break;
			case NULL: 
			case 'pascoa': $d = $a[0]; break;
		}
		return date('Y-m-d', mktime(0,0,0,$a[1],$d,$a[0]));
	}


	/*
	* calPascoa()
	* Calcula o domingo da pascoa. Base para todos os feriádos móveis.
	*
	* @ano   -> Variável que recebe o ano base para o cálculo ;
	*/

	private function getCalPascoa($ano)
	{
		$A = ($ano % 19);
		$B = (int)($ano / 100);
		$C = ($ano % 100);
		$D = (int)($B / 4);
		$E = ($B % 4);
		$F = (int)(($B + 8) / 25);
		$G = (int)(($B - $F + 1) / 3);
		$H = ((19 * $A + $B - $D - $G + 15) % 30);
		$I = (int)($C / 4);
		$K = ($C % 4);
		$L = ((32 + 2 * $E + 2 * $I - $H - $K) % 7);
		$M = (int)(($A + 11 * $H + 22 * $L) / 451);
		$P = (int)(($H + $L - 7 * $M + 114) / 31);
		$Q = (($H + $L - 7 * $M + 114) % 31) + 1;
		return date('Y-m-d', mktime(0,0,0,$P,$Q,$ano));
	}


}

