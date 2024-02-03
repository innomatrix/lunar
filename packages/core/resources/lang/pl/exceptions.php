<?php

return [
    'non_purchasable_item' => 'Model ":class" nie implementuje interfejsu kupowalnego.',
    'cart_line_id_mismatch' => 'Ta linia koszyka nie należy do tego koszyka',
    'invalid_cart_line_quantity' => 'Oczekiwano co najmniej ":1" sztuk, znaleziono ":quantity".',
    'maximum_cart_line_quantity' => 'Ilość nie może przekroczyć :quantity.',
    'carts.shipping_missing' => 'Wymagany jest adres dostawy',
    'carts.billing_missing' => 'Wymagany jest adres rozliczeniowy',
    'carts.billing_incomplete' => 'Adres rozliczeniowy jest niekompletny',
    'carts.order_exists' => 'Zamówienie dla tego koszyka już istnieje',
    'carts.shipping_option_missing' => 'Brak opcji dostawy',
    'missing_currency_price' => 'Brak ceny dla waluty ":currency"',
    'fieldtype_missing' => 'Typ pola ":class" nie istnieje',
    'invalid_fieldtype' => 'Klasa ":class" nie implementuje interfejsu FieldType.',
    'discounts.invalid_type' => 'Kolekcja może zawierać tylko ":expected", znaleziono ":actual"',
    'disallow_multiple_cart_orders' => 'Koszyki mogą mieć tylko jedno powiązane zamówienie.',    
];
