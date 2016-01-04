adminApp.controller('galleryController', function($scope, $http) {
    

    $scope.removePhoto = function(id, tokenName) {
        var dataObj = {
            id : id,
            token: tokenName
        };	
        var res = $http.post('/panel/galerie-zdjec/zdjecie/usun', dataObj);
        res.success(function(data, status, headers, config) {
            if(data === true) {
                $('#photo-' + id).remove();
            }
        });
    };
});