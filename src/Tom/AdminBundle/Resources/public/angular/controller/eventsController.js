adminApp.controller('eventsController', function($scope, $sce, $http, $attrs) {
    $scope.removeEventDate = function(id, tokenName, item) {
        var dataObj = {
            id : id,
            token: tokenName
        };	
        var res = $http.post('/panel/wydarzenia/data/usun', dataObj);
        res.success(function(data, status, headers, config) {
            if(data === true) {
                $('#event_eventDates_' + item).parent().parent().remove();
            }
        });
    };
});