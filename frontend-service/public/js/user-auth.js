window.AdminAuth = {
    checkTokenOrRedirect: function (loginRoute) {
        const token = localStorage.getItem('user_token');
        if (!token) {
            window.location.href = loginRoute;
        }
    },

    handleAuthError: function (response, loginRoute) {
        if (response.status === 401 || response.status === 403) {
            localStorage.removeItem('user_token');
            window.location.href = loginRoute;
        }
    },

    getToken: function () {
        return localStorage.getItem('user_token');
    }
};
