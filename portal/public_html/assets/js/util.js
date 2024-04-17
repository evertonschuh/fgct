function validateCPF(value){

	if ((value != "111.111.111-11") && (value != "222.222.222-22") && (value != "333.333.333-33") && (value != "444.444.444-44") &&	(value != "555.555.555-55") && 
		(value != "666.666.666-66") && (value != "777.777.777-77") && (value != "888.888.888-88") && (value != "999.999.999-99") && (value != "000.000.000-00"))
	{
		var i;  
		//var c = s.substr(0,3) + s.substr(4,3) + s.substr(8,3); 
		var dv = value.substr(12,2); 
		var d1 = 0; 
	
		if(document.layers && parseInt(navigator.appVersion) == 4)
		{
			x = value.substring(0,3);
			x += value.substring (4,3);
			x += value.substring (8,3);
			c = x; 
		}
		else 
		{
		   c = value.replace (".","");
		   c = c.replace (".","");
		   c = c.replace ("-","");
		}
		
		for (i = 0; i < 9; i++) 
			d1 += c.charAt(i)*(10-i); 
			
		if (d1 == 0)
			return false; 
		
		d1 = 11 - (d1 % 11); 
		if (d1 > 9) d1 = 0; 
		
		if (dv.charAt(0) != d1) 
			return false; 
		
		d1 *= 2; 
		
		for (i = 0; i < 9; i++) 
			d1 += c.charAt(i)*(11-i); 
		
		d1 = 11 - (d1 % 11);   
		
		if (d1 > 9) d1 = 0;  
		
		if (dv.charAt(1) != d1)  
			return false; 
		
		return true;	
	}
	return false; 
}


function validateCNPJ(value)
{
	if(document.layers && parseInt(navigator.appVersion) == 4)
	{
		x = value.substring(0,2);
		x += value.substring(3,6);
		x += value.substring(7,10);
		x += value.substring(11,15);
		x += value.substring(16,18);
		c = x; 
	}
	else 
	{
	   c = value.replace(".","");
	   c = c.replace(".","");
	   c = c.replace("-","");
	   c = c.replace("/","");
	}
	var nonNumbers = /\D/;
	if (nonNumbers.test(c))
		return false; 
	
	var a = [];
	var b = new Number;
	var d = [6,5,4,3,2,9,8,7,6,5,4,3,2];
	for (i=0; i<12; i++)
	{
	   a[i] = c.charAt(i);
	   b += a[i] * d[i+1];
	}
	
	if ((x = b % 11) < 2) 
		a[12] = 0 
	else
		a[12] = 11-x 

	b = 0;
	for (y=0; y<13; y++)
		b += (a[y] * d[y]); 

	if ((x = b % 11) < 2)  
		a[13] = 0;
	else 
		a[13] = 11-x; 
		
	if ((c.charAt(12) != a[12]) || (c.charAt(13) != a[13]))
		return false; 

	return true;	
}

function validatePIS(value)
{
	var ftap='3298765432';
	var total=0;
	var i;
	var c;
	var resto=0;
	var numPIS=0;
	var strResto='';

	c = value.replace(".","");
	c = c.replace(".","");
	c = c.replace("-","");

	numPIS = c;

	for (i = 0; i <= 9; i++) {
		resultado = (numPIS.slice(i, i + 1)) * (ftap.slice(i, i + 1));
		total = total + resultado;
	}

	resto = (total % 11)

	if (resto != 0) {
		resto = 11 - resto;
	}

	if (resto == 10 || resto == 11) {
		strResto = resto + '';
		resto = strResto.slice(1, 2);
	}

	if (resto != (numPIS.slice(10, 11))) {
		return false;
	}

	return true;
}

function validateDate(value) 
{  
	var expReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/(19|20)?\d{2}$/;
	var aRet = true;
	if ((value) && (value.match(expReg)) && (value != '')) 
	{       
		var dia = value.substring(0,2);
		var mes = value.substring(3,5);
		var ano = value.substring(6,10);
		if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia > 30)
			return false; 
		else if ((ano % 4) != 0 && mes == 2 && dia > 28)
			return false; 
		else if ((ano%4) == 0 && mes == 2 && dia > 29)
			return false; 
	}  
	else 
		return false; 
		
	return true;	
		
}

function validateIdade(value){
	var expReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/(19|20)?\d{2}$/;
	var aRet = true;
	
	if ((value) && (value.match(expReg)) && (value != '')) 
	{       
		var dia = value.substring(0,2);
		var mes = value.substring(3,5);
		var ano = value.substring(6,10);
		var diamin = new Date().getDate();
		var mesmin = new Date().getMonth()+1;
		var anomin = new Date().getFullYear()-14;

		if ( ano > anomin ){
			return false;
		}
		else if ( ano == anomin ){
			if ( mes > mesmin ){
				return false;
			}
			else if ( mes == mesmin ){
				if ( dia > diamin ){
					return false;
				}
			}
		}
	}
	else 
		return false; 

	return true;
}

function validateHoje(value) 
{  
	var expReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/(19|20)?\d{2}$/;
	var aRet = true;
	
	if ((value) && (value.match(expReg)) && (value != '')) 
	{       
		var dia = value.substring(0,2);
		var mes = value.substring(3,5);
		var ano = value.substring(6,10);
		var diamin = new Date().getDate();
		var mesmin = new Date().getMonth()+1;
		var anomin = new Date().getFullYear();

		if ( ano < anomin ){
			return false;
		}
		else if ( ano == anomin ){			
			if ( mes < mesmin ){
				return false;
			}
			else if ( mes == mesmin ){
				if ( dia <= diamin ){
					return false;
				}
			}
		}
	}
	else 
		return false; 
	
	return true;	

		
}

function validateMaxDate(value,compare)
{  

	var expReg = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/(19|20)?\d{2}$/;
	var aRet = true;
	
	if ((value) && (value.match(expReg)) && (value != '') && (compare) && (compare.match(expReg)) && (compare != '') ) 
	{       
		var dia = value.substring(0,2);
		var mes = value.substring(3,5)-1;
		var ano = value.substring(6,10);
		
		var diacompare = compare.substring(0,2);
		var mescompare = compare.substring(3,5)-1;
		var anocompare = compare.substring(6,10);			
		
		var x = new Date(ano,mes,dia);
		var y = new Date(anocompare,mescompare,diacompare);

		if ( x > y )
			return false; 
	}
	else 
		return false; 
	
	return true;	

		
}


function uniqid() {
    var ts=String(new Date().getTime()), i = 0, out = '';
    for(i=0;i<ts.length;i+=2) {        
       out+=Number(ts.substr(i, 2)).toString(36);    
    }
    return ('d'+out);
}

function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true;
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inválida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
        objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}

function SomenteNumero(e)
{
    var tecla=(window.event)?event.keyCode:e.which;
    if((tecla > 47 && tecla < 58)) return true;
    else
	{
    	if (tecla != 8) return false;
    else return true;
    }
}