  /*
   * 
   * Skrypt autoaktualizacji
   * Copyright © 2015 T&M. Wszelkie prawa zastrzeżone.
   * mykyy17@gmail.com tomaszhycnar@gmail.com
   * 
   */
  
/*
 * 
 *  Wystartowanie strony i załadowanie funkcji getDataJson()
 */
$(document).ready(function () {
           getDataJson();
});
 

/*
 * 
 *  Wysłanie polecenia zapisu pliku json z czasem
 */
function setTimeOnLive() {
            $.ajax({ type: 'POST', url: '/timeto/web/app_dev.php/panel/api/onlive', 
                success: function(data){          /*jeżeli ma zostać wykonany warunek*/
                getDataJson();
                 }
             });    
}


/*
 * 
 *  Odczytanie pliku json i osadzenie go w funkcji data oraz wsadzenie w input o id #czass wartości data
 */
function getDataJson() {
            $.getJSON( "/timeto/web/uploads/data.json", function( data ) {
                console.log(data['date']);
                 $("#czass").val(data['date']);
            });
}


/*
 * 
 *  Pobranie wartości z formularza oraz przesłanie go za do bazy oraz przesłanie wartości to response jak i funkcji setTimeOnLive();
 */
function messengerForm($form, callback) {

            /*
             * Get all form values
             */
            var values = {};
            $.each($form.serializeArray(), function (i, field) {
                values[field.name] = field.value;
            });

            /*
             * Throw the form values to the server!
             */
            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: values,
                dataType: "json",
                success: function (response) {
                   /* $("#czass").val(response['data1']['date']);*/
                    callback(response);
                    setTimeOnLive();
                }

                /*    success     : function(data) {
                 if(data['success']===true){
                 callback( data );
                 }                                      sprawdzenie warunku na innej funkcji w kontrolerze

                 if(data['success']===false){
                 callback( data );
                 }       
                 }*/
            });

}


/*
 * 
 *  jeżeli formularz został przesłany (nacisnięty enter lub przycisk wyślij) restartuje value inputa oraz odświeża diva o id #spr
 */
$(document).ready(function () {
            $('form').submit(function (e) {
                e.preventDefault();
                messengerForm($(this), function (response) {
                    /* $("#sprawdzam").show().delay(3000).fadeOut();*/
                    $("#MessengerType_contents").val("");
                    $('#spr').load(document.URL + ' #spr');
                    /*console.log(JSON.stringify(response));*/
                });
            });
});


/*
 *  po kliknięciu dzieją się cuda
 *  
 */
$("#MessengerType_contents").one("click", function () {
            $("#MessengerType_contents").attr("placeholder", "Czatuj...");
            $('#spr').load(document.URL + ' #spr');
            setInterval(function () {
                 $.getJSON( "/timeto/web/uploads/data.json", function( data ) {
                /*console.log(data['date']);*/
                if ($("#czass").val() === data['date'])
                {
                    console.log('live!');
                }
                else
                {
                   $('#spr').load(document.URL + ' #spr');
                   getDataJson();
                }  
            });
            
            
                /*$('#spr').load(document.URL + ' #spr');*/

            }, 3000);
});