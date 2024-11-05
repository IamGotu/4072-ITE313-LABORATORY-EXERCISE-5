var app = angular.module('socialMediaApp', []);

app.controller('LoginController', ['$scope', '$http', '$window', function($scope, $http, $window) {
    $scope.login = function() {
        $http.post('/auth/login', {
            email: $scope.email,
            password: $scope.password
        }).then(function(response) {
            if (response.data.success) {
                // Redirect to the welcome page
                $window.location.href = '/welcome.html';
            } else {
                // Display specific error message if login fails
                $scope.errorMessage = response.data.message || 'Login failed. Please check your credentials.';
            }
        }).catch(function(error) {
            // Determine the type of error
            if (error.status === 401) {
                $scope.errorMessage = 'Unauthorized: Invalid email or password.';
            } else if (error.status === 404) {
                $scope.errorMessage = 'Error: Login endpoint not found.';
            } else {
                console.error('Login request error:', error);
                $scope.errorMessage = 'An unexpected error occurred during login. Please try again.';
            }
        });
    };

    $scope.goToRegister = function() {
        // Redirect to the registration page
        $window.location.href = 'register.html';
    };
}]);

app.controller('RegisterController', ['$scope', '$http', function($scope, $http) {
    $scope.register = function() {
        $http.post('/auth/register', {
            name: $scope.name,
            email: $scope.email,
            password: $scope.password
        }).then(function(response) {
            if (response.data.success) {
                // Display success message and redirect to login page
                $scope.registerMessage = 'Registration successful. You can now log in.';
                setTimeout(function() {
                    window.location.href = 'index.html';
                }, 2000); // Redirect after 2 seconds
            } else {
                // Display specific error message if registration fails
                $scope.registerMessage = response.data.message || 'Registration failed. Please check your details.';
            }
        }).catch(function(error) {
            // Determine the type of error
            if (error.status === 400) {
                $scope.registerMessage = 'This email is already registered.';
            } else {
                console.error('Registration request error:', error);
                $scope.registerMessage = 'An unexpected error occurred during registration. Please try again.';
            }
        });
    };
}]);