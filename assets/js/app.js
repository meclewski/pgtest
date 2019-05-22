/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('jquery-ui');
require('bootstrap');
require('bootstrap-datepicker');
require('datatables.net-bs4');

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');
require('../css/global.scss');
// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
$.fn.datepicker.dates['pl'] = {
    days: ["Niedziela", "Poniedziałek", "Wtorek", "Środa", "Czwartek", "Piątek", "Sobota"],
    daysShort: ["Niedz.", "Pon.", "Wt.", "Śr.", "Czw.", "Piąt.", "Sob."],
    daysMin: ["Ndz.", "Pn.", "Wt.", "Śr.", "Czw.", "Pt.", "Sob."],
    months: ["Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień"],
    monthsShort: ["Sty.", "Lut.", "Mar.", "Kwi.", "Maj", "Cze.", "Lip.", "Sie.", "Wrz.", "Paź.", "Lis.", "Gru."],
    today: "Dzisiaj",
    weekStart: 1,
    clear: "Wyczyść",
    format: "dd.mm.yyyy"
};
$(document).ready(function() {
    // you may need to change this code if you are not using Bootstrap Datepicker
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd',
        language: 'pl'
    }).focus(function() {
        $(this).prop("autocomplete", "off");
});
});
$(document).ready(function() {
    $('.table-data').DataTable(
        {
            "order": [[ 0, "desc" ]],
            "language": {
                "lengthMenu": "Wyświetl _MENU_ rekordów na stronę",
                "zeroRecords": "Przepraszamy, nic nie znaleziono",
                "info": "Wyświetlana strona _PAGE_ z _PAGES_",
                "infoEmpty": "Brak dostępnych rekordów",
                "infoFiltered": "(Odfiltrowane z _MAX_ wszystkich rekordów)",
                "search": "Wyszukaj",
                "paginate": {
                    "previous": "Poprzednia",
                    "next": "Następna"
                }
            }  
        }
    );
} );      

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
