$(document).ready(function () {

    var $api_key = window.postcodeApiKey;

    if ($('#postcode_lookup_field').length > 0) {
        $('#postcode_lookup_field').setupPostcodeLookup({
            api_key: $api_key,
            'dropdown_class': 'my_dropdown',
            // Pass in CSS selectors pointing to your input fields
            output_fields: {
                line_1: '#address_line1',
                line_2: '#address_line2',
                line_3: '#address_line3',
                post_town: '#town',
                postal_county: '#county',
                postcode: '#postcode'
            }
        });
    }

    if ($('#postcode_lookup_field_stage1_petitoner').length > 0) {

        $('#postcode_lookup_field_stage1_petitoner').setupPostcodeLookup({
            api_key: $api_key,
            'dropdown_class': 'my_dropdown2',
            // Pass in CSS selectors pointing to your input fields
            output_fields: {
                line_1: '#petitoner_address_line1',
                line_2: '#petitoner_address_line2',
                line_3: '#petitoner_address_line3',
                post_town: '#petitoner_town',
                postal_county: '#petitoner_county',
                postcode: '#petitoner_postcode'
            }
        });
    }

    if ($('#postcode_lookup_field_stage1_respondent').length > 0) {

        $('#postcode_lookup_field_stage1_respondent').setupPostcodeLookup({
            api_key: $api_key,
            'dropdown_class': 'my_dropdown2',
            // Pass in CSS selectors pointing to your input fields
            output_fields: {
                line_1: '#respondent_address_line1',
                line_2: '#respondent_address_line2',
                line_3: '#respondent_address_line3',
                post_town: '#respondent_town',
                postal_county: '#respondent_county',
                postcode: '#respondent_postcode'
            }
        });
    }


    if ($('#postcode_lookup_field_stage1_marriage').length > 0) {

        $('#postcode_lookup_field_stage1_marriage').setupPostcodeLookup({
            api_key: $api_key,
            'dropdown_class': 'my_dropdown2',
            // Pass in CSS selectors pointing to your input fields
            output_fields: {
                line_1: '#marriage_last_address_line1',
                line_2: '#marriage_last_address_line2',
                line_3: '#marriage_last_address_line3',
                post_town: '#marriage_last_town',
                postal_county: '#marriage_last_county',
                postcode: '#marriage_last_postcode'
            }
        });
    }

    /*$('.js #the-main-content').animate({
        opacity: 1
    }, 1400)*/




});