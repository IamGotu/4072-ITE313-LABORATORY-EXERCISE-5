var app = angular.module('socialMediaApp', []);

// Login Controller
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

// Registration Controller
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

// Profile Controller
app.controller('ProfileController', ['$scope', '$http', '$window', function($scope, $http, $window) {
    // Initialize the user object and other variables
    $scope.user = {};
    $scope.showEditForm = false;
    $scope.errorMessage = '';
    $scope.successMessage = '';
    $scope.passwordMismatch = false;

    // Fetch user profile data
    $http.get('/profile').then(function(response) {
        if (response.data.success) {
            $scope.user = response.data.user;
        } else {
            $scope.errorMessage = response.data.message || 'Error fetching profile data.';
        }
    }).catch(function(error) {
        console.error('Profile request error:', error);
        $scope.errorMessage = 'An error occurred while fetching profile data.';
    });

    // Update profile function
    $scope.updateProfile = function() {
        // Ensure new password matches confirmation
        if ($scope.newPassword !== $scope.confirmPassword) {
            $scope.passwordMismatch = true;
            return;
        }
    
        // Proceed with submitting the form data
        var formData = new FormData();
        formData.append('name', $scope.user.name);
        formData.append('email', $scope.user.email);
        if ($scope.newPassword) {
            formData.append('currentPassword', $scope.currentPassword);
            formData.append('newPassword', $scope.newPassword);
        }
    
        $http.post('/profile/update', { name: $scope.user.name, email: $scope.user.email, currentPassword: $scope.currentPassword, newPassword: $scope.newPassword }).then(function(response) {
        }).then(function(response) {
            if (response.data.success) {
                $scope.user = response.data.user;
                $scope.successMessage = 'Profile updated successfully!';
            } else {
                $scope.errorMessage = response.data.message;
            }
        }).catch(function(error) {
            console.error('Update profile request error:', error);
            $scope.errorMessage = 'An error occurred while updating profile data.';
        });
    };    

    // Function to handle logout
    $scope.logout = function() {
        $http.post('/auth/logout').then(function(response) {
            if (response.data.success) {
                $window.location.href = 'index.html'; // Redirect to login page
            } else {
                $scope.errorMessage = response.data.message;
            }
        }).catch(function(error) {
            console.error('Logout request error:', error);
            $scope.errorMessage = 'An error occurred during logout.';
        });
    };
}]);