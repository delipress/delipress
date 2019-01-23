/**
 * Fonction pour arrondir un nombre.
 *
 * @param	{String}	type	Le type d'arrondi.
 * @param	{Number}	value	Le nombre à arrondir.
 * @param	{Integer}	exp		L'exposant (le logarithme en base 10 de la base pour l'arrondi).
 * @returns	{Number}			La valeur arrondie.
 */
function decimalAdjust(type, value, exp) {

    if (typeof exp === 'undefined' || +exp === 0) {
        return Math[type](value);
    }
    value = +value;
    exp = +exp;

    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
        return NaN;
    }

    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
}

export function round10(value, exp) {
    return decimalAdjust('round', value, exp);
}

export function floor10(value, exp) {
    return decimalAdjust('floor', value, exp);
}

export function ceil10(value, exp) {
    return decimalAdjust('ceil', value, exp);
}