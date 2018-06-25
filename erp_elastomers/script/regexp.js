var emailfilter=/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i;
function checkmail(e){
    var returnval=emailfilter.test(e.value);
    return returnval;
}


/************************ check non blank string ******************************/
var isNonblank_re    = /\S/;
function isNonblank (s) {
   return String (s).search (isNonblank_re) != -1;
}

/*********************** whole number string only *************************/
// Check if string is a whole number(digits only).
var isWhole_re       = /^\s*\d+\s*$/;
function isWhole (s) {
   return String(s).search (isWhole_re) != -1
}

/********** or   *******************/
// check 0-9 digit
function regIsDigit(fData){
    var reg = new RegExp("^[0-9]$");
    return (reg.test(fData));
}

/*************************** check input string is an integer ***************/
// checks that an input string is an integer, with an optional +/- sign character.
var isInteger_re     = /^\s*(\+|-)?\d+\s*$/;
function isInteger (s) {
   return String(s).search (isInteger_re) != -1
}

/****** or  *******/
// check is number
function regIsNumber(fData)
{
    var reg = new RegExp("^[-]?[0-9]+[\.]?[0-9]+$");
    return reg.test(fData)
}

/**************************** decimanl number ******************************/
// Checks that an input string is a decimal number, with an optional +/- sign character.
var isDecimal_re     = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;
function isDecimal (s) {
   return String(s).search (isDecimal_re) != -1
}

/*************************** currnency *********************************/
// Check if string is currency
var isCurrency_re    = /^\s*(\+|-)?((\d+(\.\d\d)?)|(\.\d\d))\s*$/;
function isCurrency (s) {
   return String(s).search (isCurrency_re) != -1
}

/************************* looks like email address **********************/
// checks that an input string looks like a valid email address.
var isEmail_re       = /^\s*[\w\-\+_]+(\.[\w\-\+_]+)*\@[\w\-\+_]+\.[\w\-\+_]+(\.[\w\-\+_]+)*\s*$/;
function isEmail (s) {
   return String(s).search (isEmail_re) != -1;
}

/*********************** is valid address **************/
// Check if string is a valid email address
function regIsEmail(fData)
{
  var reg = new RegExp("^[0-9a-zA-Z]+@[0-9a-zA-Z]+[\.]{1}[0-9a-zA-Z]+[\.]?[0-9a-zA-Z]+$");
  return reg.test(fData);
}





