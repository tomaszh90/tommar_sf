adminApp.controller('schoolsController', function($scope, $sce, $http, $attrs) {
    
    $scope.ctrlUrl = $attrs.url;
    $scope.selectWoj = $attrs.woj;
    
    $scope.selectMiasta = function() {
        var dataObj = {
            id_woj : $scope.selectWoj
        };	
        var res = $http.post($scope.ctrlUrl, dataObj);
        res.success(function(data, status, headers, config) {
            if(data === false) {
                console.log('Błąd pobierania miast z wybranego województwa!');
            } else {
                var schoolMiasto = document.getElementById("school_miasto");
                schoolMiasto.options.length = 0;
                
                for (var k in data) {
                    if (data.hasOwnProperty(k)) {
                        var option = document.createElement("option");
                        option.value = data[k];
                        option.text = k;
                        schoolMiasto.add(option);
                    }
                }
            }
        });
    };
   
    
});


//$('#school_wojewodztwo').change(function () {
//    console.log($('#school_miasto').val());
//});